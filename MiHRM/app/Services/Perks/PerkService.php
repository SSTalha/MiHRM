<?php
namespace App\Services\Perks;

use App\Constants\Messages;
use Throwable;
use App\Models\Perk;
use App\Helpers\Helpers;
use App\Models\Employee;
use App\Models\PerksRequest;
use App\Models\PerksAssignment;
use App\Jobs\PerkRequestStatusJob;
use Illuminate\Support\Facades\Auth;
use App\DTOs\PerkDTOs\RequestPerkDTO;
use Symfony\Component\HttpFoundation\Response;

class PerkService
{
    public function createPerk($request)
    {
        try {
            $perk = Perk::create($request->validated());
            return Helpers::result("Perk created successfully.", Response::HTTP_CREATED, $perk);
        } catch (Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function requestPerks($request)
    {
        try {
            $employee = Auth::user()->employee; 

            $requestedPerkTitles = Perk::whereIn('id', $request['requested_perks'])
                                        ->pluck('title') 
                                        ->toArray();     

            $totalAllowance = Perk::whereIn('id', $request['requested_perks'])->sum('allowance');
            $dto = new RequestPerkDTO($requestedPerkTitles, $employee->id, $totalAllowance);
            $perkRequest = PerksRequest::create($dto->toArray());

            return Helpers::result(Messages::PerkRequestSubmitted, Response::HTTP_CREATED, $perkRequest);
        } catch (Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function handlePerkRequest($request)
    {
        try {
            $perkRequestId = $request['perk_request_id']; 
            $status = $request['status'];  

            $user = Auth::user(); 
            $perkRequest = PerksRequest::findOrFail($perkRequestId);  

            if ($user->hasRole('hr') && $perkRequest->employee->user->hasRole('hr')) {
                return Helpers::result(Messages::CannotApproveOwnRequest, Response::HTTP_FORBIDDEN);
            }

            $perkRequest->update(['status' => $status]);

            if ($status === 'approved') {
                foreach ($perkRequest->requested_perks as $perkTitle) {
                    $perk = Perk::where('title', $perkTitle)->first();
                    if ($perk) {
                        PerksAssignment::create([
                            'employee_id' => $perkRequest->employee_id,
                            'perk_id' => $perk->id,  
                        ]);
                    }
                }

                $employee = Employee::findOrFail($perkRequest->employee_id);
                $employee->update([
                    'pay' => $employee->pay + $perkRequest->total_allowance,  
                ]);
            }
            PerkRequestStatusJob::dispatch($perkRequest->employee->user, $status);
            return Helpers::result(Messages::PerkRequestStatus, Response::HTTP_OK, $perkRequest);
        } catch (Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllPerks($request)
    {
        try {
            $perks = Perk::all(['id', 'title', 'allowance']); 
            if ($perks->isEmpty()) {
                return Helpers::result(Messages::NoPerks, Response::HTTP_NOT_FOUND);
            }
            return Helpers::result(Messages::PerksFetched, Response::HTTP_OK, $perks);
        } catch (Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAllPerkRequests($request)
    {
        try {
            $user = Auth::user();  
            if ($user->hasRole('admin')) {
                $perkRequests = PerksRequest::with(['employee.user'])->get();
            }
            elseif ($user->hasRole('hr')) {
                $perkRequests = PerksRequest::whereHas('employee.user', function ($query) {
                    $query->whereHas('roles', function ($roleQuery) {
                        $roleQuery->where('name', 'employee');  
                    });
                })->with('employee.user')->get();
            } else {
                return Helpers::result(Messages::NotAuthorized, Response::HTTP_FORBIDDEN);
            }

            $data = $perkRequests->map(function ($perkRequest) {
                return [
                    'id' => $perkRequest->id,
                    'employee_id' => $perkRequest->employee_id,
                    'employee_name' => $perkRequest->employee->user->name,  // Get employee name from users table
                    'requested_perks' => $perkRequest->requested_perks,
                    'status' => $perkRequest->status,
                    'total_allowance' => $perkRequest->total_allowance
                ];
            });

            if ($data->isEmpty()) {
                return Helpers::result("No perk requests found.", Response::HTTP_NOT_FOUND);
            }
            return Helpers::result(Messages::PerksFetched, Response::HTTP_OK, $data);
        } catch (Throwable $e) {
            return Helpers::error($request, Messages::ExceptionMessage, $e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

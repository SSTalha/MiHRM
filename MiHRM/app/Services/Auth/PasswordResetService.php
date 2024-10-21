<?php

namespace App\Services\Auth;
use App\Constants\Messages;
use Hash;
use App\Models\User;
use App\Helpers\Helpers;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use \App\Models\PasswordReset;
use App\DTOs\AuthDTOs\PasswordDTO;
use App\Jobs\SendPasswordResetLinkJob;

class PasswordResetService
{
    /**
     * Summary of sendPasswordResetLink
     * @param mixed $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function sendPasswordResetLink($request){
        try{
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return Helpers::result(Messages::UserNotFound, Response::HTTP_NOT_FOUND);
            }

            $token = Str::random(40);
            PasswordReset::createToken($request->email, $token);

            $resetUrl =env('FRONTEND_URL').'/password-reset/' .'?token='. $token . '&email=' . urlencode($request->email);
            SendPasswordResetLinkJob::dispatch($user, $resetUrl);

            return Helpers::result(Messages::PasswordLinkSend, Response::HTTP_OK);
        }catch (\Throwable $e) 
        {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Summary of passwordReset
     * @param mixed $email
     * @param mixed $password
     * @param mixed $token
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function passwordReset($request){
        try{

            $dto = new PasswordDTO($request);
            $resetData = PasswordReset::where('email', $dto->email)->first();

            if (!$resetData || $resetData->token !== $dto->token) {
                return Helpers::result(Messages::InvalidCredentials, Response::HTTP_BAD_REQUEST);
            }

            $user = User::where('email', $dto->email)->first();

            if (!$user) {
                return Helpers::result(Messages::UserNotFound, Response::HTTP_NOT_FOUND);
            }

            $user->password = Hash::make($dto->password);
            $user->save();

            PasswordReset::where('email', $dto->email)->delete();
            return Helpers::result(Messages::PasswordSetSuccess, Response::HTTP_OK);
        }catch (\Throwable $e) 
        {
            return Helpers::error($request, Messages::ExceptionMessage, $e , Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
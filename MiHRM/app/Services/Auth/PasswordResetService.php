<?php

namespace App\Services\Auth;
use App\DTOs\AuthDTOs\PasswordDTO;
use App\Helpers\Helpers;
use App\Jobs\SendPasswordResetLinkJob;
use App\Models\User;
use \App\Models\PasswordReset;
use Illuminate\Http\Response;
use Hash;
use Str;

class PasswordResetService
{
    /**
     * Summary of sendPasswordResetLink
     * @param mixed $email
     * @throws \Exception
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function sendPasswordResetLink($email){
        try{
            $user = User::where('email', $email)->first();

            if (!$user) {
                return Helpers::result('User not found.', Response::HTTP_NOT_FOUND);
            }

            $token = Str::random(40);
            PasswordReset::createToken($email, $token);

            // $resetUrl = url('/password-reset?email=' . urlencode($user->email) . '&token=' . $token);
            $resetUrl =env('FRONTEND_URL').'/password-reset/' .'?token='. $token . '&email=' . urlencode($email);
            SendPasswordResetLinkJob::dispatch($user, $resetUrl);

            return Helpers::result('Password Link sent', Response::HTTP_OK);
        }catch(\Exception $e){
            return Helpers::result('Error while sending link', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
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
                return Helpers::result('Invalid token or email.', Response::HTTP_BAD_REQUEST);
            }

            $user = User::where('email', $dto->email)->first();

            if (!$user) {
                return Helpers::result('User not found.', Response::HTTP_NOT_FOUND);
            }

            $user->password = Hash::make($dto->password);
            $user->save();

            PasswordReset::where('email', $dto->email)->delete();
            return Helpers::result('Password reset successfull', Response::HTTP_OK);
        }catch(\Exception $e){
            return Helpers::result('Error while reseting password', Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\UpdatePasswordRequest;
use App\Models\PasswordResetToken;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Traits\HttpResponses;

class ChangePasswordController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Post(
     *     path="/api/auth/reset-password",
     *     summary="Process password reset",
     *     tags={"Password Reset"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "resetToken", "password", "password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", description="Email address for password reset"),
     *             @OA\Property(property="resetToken", type="string", description="Token received for password reset"),
     *             @OA\Property(property="password", type="string", format="password", description="New password"),
     *
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Password has been updated."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid email or token",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Either your email or token is wrong."),
     *         ),
     *     ),
     * )
     */
    public function passwordResetProcess(UpdatePasswordRequest $request)
    {
        return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
    }

    // Verify if token is valid
    private function updatePasswordRow(UpdatePasswordRequest $request)
    {
        return PasswordResetToken::where([
            'email' => $request->email,
            'token' => $request->resetToken
        ]);
    }

    // Token not found response
    private function tokenNotFoundError()
    {
        return $this->error("", "Either your email or token is wrong.", 422);
    }

    // Reset password
    private function resetPassword(UpdatePasswordRequest $request)
    {
        $user = User::whereEmail($request->email)->first();
        // update password
        $user->update([
            'password' => bcrypt($request->password)
        ]);
        $this->updatePasswordRow($request)->delete();

        // reset password response
        return $this->success("Password has been updated.");
    }
}

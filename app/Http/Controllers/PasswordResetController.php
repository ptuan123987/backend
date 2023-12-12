<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Mail\SendMailReset;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;
/**
 * @OA\Tag(
 *     name="Password Reset"
 * )
 */
class PasswordResetController extends Controller
{
    use HttpResponses;

    /**
     * @OA\Post(
     *     path="/api/auth/forgot-password",
     *     summary="Send password reset email",
     *     tags={"Password Reset"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", description="Email address for password reset"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset email sent successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Reset Email is sent successfully. Please check your inbox."),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Email not found in the database",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Email doesn't found on our database"),
     *         ),
     *     ),
     * )
     */
    public function sendEmail(Request $request)
    {
        // this is validate to fail send mail or true
        if (!$this->validateEmail($request->email)) {
            return $this->error("", "Email does\'t found on our database", 401);
        }
        $this->send($request->email);
        return $this->success("Reset Email is send successfully, please check your inbox");
    }

    public function send($email)
    {
        $token = $this->createToken($email);
        Mail::to($email)->send(new SendMailReset($token, $email));
    }

    public function createToken($email)
    {
        $oldToken = PasswordResetToken::where('email', $email)->first();

        if ($oldToken) {
            return $oldToken->token;
        }

        $token = Str::random(40);
        $this->saveToken($token, $email);
        return $token;
    }

    // this function save new password
    public function saveToken($token, $email)
    {
        PasswordResetToken::insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
    }
    //this is a function to get your email from database
    public function validateEmail($email)
    {
        return !!User::where('email', $email)->first();
    }
}

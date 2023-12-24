<?php

namespace App\Services;
use App\Jobs\SendWelcomeEmail;
use App\Traits\HttpResponses;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\PasswordRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendEmailRegistration;
use App\Mail\SendWelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
class AuthService
{
    use HttpResponses;
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(RegisterRequest $request) {
        $user = $this->userRepository->createUser($request);
        SendWelcomeEmail::dispatch("Welcome!", $user->email, $user->name);
        return $user;
    }


    public function login(LoginRequest $request) {
        $user = Auth::user();

        // access_token lives 60'
        $accessToken = JWTAuth::fromUser($user);
        //refresh_token lives  20160'
        $refreshToken = JWTAuth::fromUser($user, ['exp' => config('jwt.refresh_ttl')]);

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];

    }
    public function loginAdmin(LoginRequest $request) {
        $user = Auth::user();

        // access_token lives 60'
        $accessToken = JWTAuth::fromUser($user);
        //refresh_token lives  20160'
        $refreshToken = JWTAuth::fromUser($user, ['exp' => config('jwt.refresh_ttl')]);

        return [
            'admin_token' => $accessToken,
            'refresh_token' => $refreshToken,
        ];

    }

    public function changePassword(PasswordRequest $request) {
        $request->validated($request->all());
        $user = $this->userRepository->updatePassword($request);
        return $user;
    }

}

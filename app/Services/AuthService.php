<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(LoginRequest $request) {
        $user = $this->userRepository->findByEmail($request->email);
        return new UserResource($user);
    }
    public function register(RegisterRequest $request) {
        $user = $this->userRepository->createUser($request);
        return new UserResource($user);
    }
    public function logout() {
        Auth::user()->currentAccessToken()->delete();
    }


}

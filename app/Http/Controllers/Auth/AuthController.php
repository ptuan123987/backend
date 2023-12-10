<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;
use App\Services\AuthService;

class AuthController extends Controller
{
    use HttpResponses;
    protected $authService;

    public function __construct(AuthService $authService, )
    {
        $this->authService = $authService;

    }

    public function login(LoginRequest $request) {

        $request->validated($request->all());
        if(!Auth::attempt($request->only('email','password'))) {
            return $this->error('','Credentials do not match', 401);
        }
        $user = $this->authService->login($request);

        return $this->success(new UserResource($user));
    }

    public function register(RegisterRequest $request)  {
        $request->validated($request->all());

        $user = $this->authService->register($request);

        return $this->created(new UserResource($user));
    }
    public function logout() {
        $this->authService->logout();
        return $this->success('','Logout successfully');
    }
}

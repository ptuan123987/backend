<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    use HttpResponses;
    protected $authService;

    public function __construct(AuthService $authService )
    {
        $this->authService = $authService;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }


    public function login(LoginRequest $request){
    	$request->validated($request->all());

        if (! $token = auth()->attempt($request->only('email','password'))) {
            return $this->error('','Credentials do not match', 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     */
    public function register(RegisterRequest $request) {
        $request->validated($request->all());

        $user = $this->authService->register($request);

        return $this->created($user,"User successfully registered");
    }


    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }


    public function changePassWord(PasswordRequest $request) {
        $request->validated($request->all());

        $user = $this->authService->changePassword($request);

        return $this->created($user,"User successfully changed password");
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="auth",
 *     description="Authentication operations"
 * )
 *
 */
class AuthController extends Controller
{
    use HttpResponses;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"auth"},
     *     summary="Login",
     *     description="Login a user and return a JWT token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="ptuan123@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Ptuan1234@"),
     *         ),
     *     ),
     *     @OA\Response(response="200", description="Successful login"),
     *     @OA\Response(response="401", description="Unauthorized"),
     * )
     */
    public function login(LoginRequest $request)
    {
        $request->validated($request->all());

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->error('', 'Credentials do not match', 401);
        }
        $response = $this->authService->login($request);
        return $this->success($response);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"auth"},
     *     summary="Register",
     *     description="Register a new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="display_name", type="string", example="Phan Văn Tuấn"),
     *             @OA\Property(property="email", type="string", format="email", example="ptuan123@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Ptuan1234@"),
     *         ),
     *     ),
     *     @OA\Response(response="201", description="User successfully registered"),
     *     @OA\Response(response="422", description="Unprocessable Entity"),
     * )
     */
    public function register(RegisterRequest $request)
    {


        $request->validated($request->all());

        $response = $this->authService->register($request);
        return $this->created("", "User successfully registered");
    }

    /**
     * @OA\Post(
     *     path="/api/user/refresh",
     *     tags={"auth"},
     *     summary="Refresh token",
     *     description="Refresh the JWT token",
     *     @OA\Response(response="200", description="Token refreshed successfully"),
     *      security={{"bearerAuth":{}}} )
     * )
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * @OA\Get(
     *     path="/api/user/me",
     *     tags={"auth"},
     *     summary="User profile",
     *     description="Get the authenticated user profile",
     *     @OA\Response(response="200", description="User profile retrieved successfully"),
     *     security={{"bearerAuth":{}}} )
     *
     * )
     */

    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * @OA\Post(
     *     path="api/user/change-password",
     *     tags={"auth"},
     *     summary="Change password",
     *     description="Change the password of the authenticated user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="current_password", type="string", format="password", example="old_password"),
     *             @OA\Property(property="new_password", type="string", format="password", example="new_password"),
     *         ),
     *     ),
     *     @OA\Response(response="201", description="User password changed successfully"),
     *      security={{"bearerAuth":{}}} )
     * )
     */
    public function changePassWord(PasswordRequest $request)
    {
        $response = $this->authService->changePassword($request);
        return $this->created("", "User successfully changed password");
    }


    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'expires_in' => config('jwt.ttl'),
        ]);
    }
}

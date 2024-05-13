<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="admin",
 *     description="Admin Authentication operations"
 * )
 *
 */
class AdminController extends Controller
{

    use HttpResponses;

    protected $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->middleware('jwt.admin', ['except' => ['login']]);
    }
     /**
     * @OA\Post(
     *     path="/api/admin/login",
     *     tags={"admin"},
     *     summary="Login",
     *     description="Login a admin and return a JWT token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="admin@gmail.com"),
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
}

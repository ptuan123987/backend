<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * * @OA\Info(
 *     version="1.0",
 *     title="Udemy API",
 *     description="BY Phan Văn Tuấn ",
 *     @OA\Contact(name="Swagger API Team")
 * ),
 * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 *     description="Login with email and password from user module to get the authentication token",
 *     in="header",
 *     name="Authorization",
 *     ),
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="API server"
 * )
 *  * @OA\Server(
 *     url="https://api-study.salyr.online",
 *     description="production server"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}

<?php

namespace App\Http\Controllers;

use App\Services\EnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class EnrollmentController extends Controller
{
    protected $enrollmentService;
    public function __construct(EnrollmentService $enrollmentService){
        $this->middleware('auth:api');
        $this->enrollmentService = $enrollmentService;
    }
    public function acceptUserToCourse(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $userId = $user->id;
        $courseId = $request->input('course_id');
        Log::info($courseId);

        $result = $this->enrollmentService->acceptUserToCourse($userId, $courseId);

        if ($result) {
            return response()->json(['message' => 'User accepted into course successfully'], 200);
        } else {
            return response()->json(['message' => 'User is already enrolled in this course'], 422);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaidCourseController extends Controller
{
    public function index(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();
        $userId = $user->id;
        $pageNum = $request->input('pageNum', 1);
        $pageSize = $request->input('pageSize', 15);

        $payments = Payment::where('user_id', $userId)
         ->where('status', '1')
         ->pluck('course_id');

        $courses = Course::whereIn('id', $payments)
        ->paginate($pageSize, ['*'], 'page', $pageNum);
        return CourseResource::collection($courses);
    }

    public function checkPaidCourse($courseId) {
        $user = JWTAuth::parseToken()->authenticate();
        $userId = $user->id;
        Log::info($userId);
        $payment = Payment::where('user_id', $userId)
                        ->where('course_id', $courseId)
                        ->where('status', '1')
                        ->exists();
        if ($payment) {
            return response()->json(['status' => $payment], 200);
        }
        return response()->json(['error message' => 'course have not paid'], 403);
    }
}

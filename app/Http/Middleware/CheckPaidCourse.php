<?php

namespace App\Http\Middleware;

use App\Models\Lecture;
use App\Models\Payment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckPaidCourse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $lectureId = $request->id;
        $user = JWTAuth::parseToken()->authenticate();
        $userId = $user->id;
        $lecture = Lecture::findOrFail($lectureId);

        $courseId = $lecture->chapter->course_id;

        $payment = Payment::where('user_id', $userId)
                          ->where('course_id', $courseId)
                          ->where('status', '1')
                          ->first();

        if (!$payment) {
            return response()->json(
                [
                    'message' => 'You have not paid for this course.',
                    'payment_status' => 0,
                ], 403);
        }
        return $next($request);
    }
}

<?php
namespace App\Services;

use App\Models\Enrollment;

class EnrollmentService {
    public function acceptUserToCourse($userId, $courseId) {
        $existingEnrollment = Enrollment::where('user_id', $userId)
                                ->where('course_id', $courseId)->first();

        if (!$existingEnrollment) {
            Enrollment::create([
                'user_id' => $userId,
                'course_id' => $courseId,
                'status' => 1,
            ]);
            return true;
        }

        return false;
    }
}

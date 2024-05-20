<?php
namespace App\Services;

use App\Models\Enrollment;

class EnrollmentService {
    public function acceptUserToCourse($userId, $courseId) {
        $existingEnrollment = Enrollment::where('user_id', $userId)
                                ->where('course_id', $courseId)->first();

        if ($existingEnrollment) {
            $existingEnrollment->status = 1;
            $existingEnrollment->save();
            return true;
        } else {
            // Create new enrollment if not existing
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

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleLearningReminderRequest;
use App\Mail\LearningTimeReminder;
use App\Models\ScheduleLearningReminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class ScheduleLearningReminderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleLearningReminderRequest $request)
    {
        Log::info("Storing new schedule learning reminder");

        $user = JWTAuth::parseToken()->authenticate();
        Log::info("User: " . $user->id);

        // Clear existing reminders if required
        $user->schedule_learning_reminders()->delete();

        foreach ($request->schedules as $scheduleData) {
            $user->schedule_learning_reminders()->create($scheduleData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification times created.'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleLearningReminderRequest $request )
    {
        Log::info("Updating schedule learning reminder");

        $user = JWTAuth::parseToken()->authenticate();
        Log::info("User: " . $user->id);

        $user->schedule_learning_reminders()->delete();

        foreach ($request->schedules as $scheduleData) {
            $user->schedule_learning_reminders()->create($scheduleData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notification times updated.'
        ]);
    }
    public function fetchScheduleByUser() {
        $user = JWTAuth::parseToken()->authenticate();
        $schedules = ScheduleLearningReminder::where('user_id', $user->id)->get();
        return response()->json([
            "schedules" =>  $schedules
        ]);
    }
}

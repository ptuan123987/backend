<?php

namespace App\Console;

use App\Jobs\SendLearningTimeReminder;
use App\Models\ScheduleLearningReminder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            try {
                $reminders = ScheduleLearningReminder::all();

                foreach ($reminders as $reminder) {
                    $user = $reminder->user;
                    $notificationTime = Carbon::createFromFormat('H:i:s', $reminder->time);


                    $currentTime = Carbon::now();

                    // Adjust for different frequencies
                    if ($reminder->frequency === 'daily') {
                        Log::info("message daily". $reminder->message );

                        if ($currentTime->diffInMinutes($notificationTime, false) == 0) {
                            if (isset($reminder->message)) {
                                SendLearningTimeReminder::dispatch($user->email,$reminder->message);
                            } else {
                                SendLearningTimeReminder::dispatch($user->email,'');
                            }
                        }
                    } elseif ($reminder->frequency === 'weekly') {
                        Log::info("message weekly" );

                        $currentDay = $currentTime->dayOfWeek;
                        if (in_array($currentDay, $reminder->days) && $currentTime->diffInMinutes($notificationTime, false) == 0) {
                            if (isset($reminder->message)) {
                                SendLearningTimeReminder::dispatch($user->email,$reminder->message);
                            } else {
                                SendLearningTimeReminder::dispatch($user->email,'');
                            }
                        }
                    } elseif ($reminder->frequency === 'monthly') {
                        Log::info("message monthly" );

                        $currentDay = $currentTime->day;
                        if (in_array($currentDay, $reminder->days) && $currentTime->diffInMinutes($notificationTime, false) == 0) {
                            if (isset($reminder->message)) {
                                SendLearningTimeReminder::dispatch($user->email,$reminder->message);
                            } else {
                                SendLearningTimeReminder::dispatch($user->email,'');
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to send learning time reminder: ' . $e->getMessage());
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

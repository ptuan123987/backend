<?php

namespace App\Http\Controllers;

use App\Models\LoginRecords;
use App\Http\Requests\StoreLoginRecordsRequest;
use App\Http\Requests\UpdateLoginRecordsRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginRecordsController extends Controller
{
    public function countDailyLogins(Request $request) {
        $days = $request->input('days', 14);

        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($days - 1 )->startOfDay();

        $currentDate = $startDate->copy();
        $dailyData = [];

        while ($currentDate <= $endDate) {
            $loginCount = LoginRecords::whereDate('login_time', $currentDate)->count();
            $dailyData[$currentDate->toDateString()] = $loginCount;
            $currentDate->addDay();
        }

        return response()->json([
            'logins_records' => $dailyData
        ]);
    }

    public function countMonthlyLogins(Request $request) {
        $months = $request->input('months', 12);

        $endMonth = Carbon::now()->endOfMonth();
        $startMonth = Carbon::now()->subMonths($months - 1)->startOfMonth();

        $monthlyData = [];
        $currentMonth = $endMonth->copy();

        while ($currentMonth >= $startMonth) {
            $month = $currentMonth->format('Y-m');
            $loginCount = LoginRecords::whereYear('login_time', $currentMonth->year)
                                      ->whereMonth('login_time', $currentMonth->month)
                                      ->count();
            $monthlyData[$month] = $loginCount;
            $currentMonth->subMonth();
        }
        $reversedMonthlyData = array_reverse($monthlyData);

        return response()->json([
            'logins_records' => $reversedMonthlyData
        ]);
    }


    public function countYearlyLogins(Request $request) {
        $years = $request->input('years', 5);

        $endYear = Carbon::now()->startOfYear();
        $startYear = Carbon::now()->subYears($years - 1)->startOfYear();

        $yearlyData = [];
        $currentYear = $endYear->copy();

        while ($currentYear >= $startYear) {
            $year = $currentYear->format('Y');
            $loginCount = LoginRecords::whereYear('login_time', $currentYear->year)
                                      ->count();
            $yearlyData[$year] = $loginCount;
            $currentYear->subYear();
        }

        return response()->json([
            'logins_records' => $yearlyData
        ]);
    }


    public function countWeeklyLogins(Request $request) {
        $year = $request->input('year', Carbon::now()->year);
        $week = $request->input('week', Carbon::now()->week);

        $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        $weeklyLogins = LoginRecords::whereBetween('login_time', [$startOfWeek, $endOfWeek])->count();

        return response()->json([
            'weekly_logins' => $weeklyLogins
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AnalyticRevenueController extends Controller
{
    public function revenueDaily(Request $request)
    {
        $days = $request->input('days', 14);

        $endDate = Carbon::now()->endOfDay();
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $currentDate = $startDate->copy();
        $dailyRevenue = [];

        while ($currentDate <= $endDate) {
            $revenue = Payment::whereDate('created_at', $currentDate)->sum('amount');
            $dailyRevenue[$currentDate->toDateString()] = $revenue;
            $currentDate->addDay();
        }

        return response()->json([
            'revenue' => $dailyRevenue
        ]);
    }

    public function revenueMonthly(Request $request)
    {
        $months = $request->input('months', 6);

        $endDate = Carbon::now()->endOfMonth();
        $startDate = Carbon::now()->subMonths($months - 1)->startOfMonth();

        $currentMonth = $startDate->copy();
        $monthlyRevenue = [];

        while ($currentMonth <= $endDate) {
            $revenue = Payment::whereYear('created_at', $currentMonth->year)
                ->whereMonth('created_at', $currentMonth->month)
                ->sum('amount');
            $monthlyRevenue[$currentMonth->format('Y-m')] = $revenue;
            $currentMonth->addMonth();
        }

        return response()->json([
            'revenue' => $monthlyRevenue
        ]);
    }

    public function revenueYearly(Request $request)
    {
        $years = $request->input('years', 5);

        $endDate = Carbon::now()->endOfYear();
        $startDate = Carbon::now()->subYears($years - 1)->startOfYear();

        $currentYear = $startDate->copy();
        $yearlyRevenue = [];

        while ($currentYear <= $endDate) {
            $revenue = Payment::whereYear('created_at', $currentYear->year)->sum('amount');
            $yearlyRevenue[$currentYear->year] = $revenue;
            $currentYear->addYear();
        }

        return response()->json([
            'revenue' => $yearlyRevenue
        ]);
    }
}

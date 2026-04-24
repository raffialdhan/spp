<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $todayRevenue = Payment::where('status', 'success')
            ->whereDate('payment_date', now())
            ->sum('amount');
            
        $pendingCount = Payment::where('status', 'pending')->count();
        
        $totalArrears = 1500000; // Dummy for now
        
        $pendingPayments = Payment::with(['student', 'fee'])
            ->where('status', 'pending')
            ->orderBy('payment_date', 'desc')
            ->take(5)
            ->get();
            
        $recentSuccess = Payment::with(['student', 'fee'])
            ->where('status', 'success')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        return view('staff.dashboard', compact(
            'todayRevenue',
            'pendingCount',
            'totalArrears',
            'pendingPayments',
            'recentSuccess'
        ));
    }
}

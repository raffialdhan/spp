<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = $user->student; // Assumes relationship exists

        if (!$student) {
            // Handle if user has role student but no student record
            return view('dashboard', [
                'name' => $user->name,
                'totalPaid' => 0,
                'activeStaff' => User::where('role', 'staff')->count(),
                'currentBill' => 0,
                'totalArrears' => 0,
                'recentPayments' => collect()
            ]);
        }

        $totalPaid = Payment::where('student_id', $student->id)
            ->where('status', 'success')
            ->sum('amount');
            
        $activeStaff = User::where('role', 'staff')->count();
        
        $currentBill = $student->fee ? $student->fee->amount : 0;
        
        $totalArrears = 1500000; // Dummy logic for now

        $recentPayments = Payment::with('fee')
            ->where('student_id', $student->id)
            ->orderBy('payment_date', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'student',
            'totalPaid',
            'activeStaff',
            'currentBill',
            'totalArrears',
            'recentPayments'
        ));
    }
}

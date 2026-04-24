<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Fee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        if (!$student) {
            return redirect()->route('student.dashboard');
        }

        // Current implementation: Unpaid bills are fees that don't have a 'success' payment from this student
        // This is a simplified logic. In a real app, you'd have a 'bills' table generated monthly.
        // For now, let's show all active fees that the student hasn't paid successfully yet.
        
        $paidFeeIds = Payment::where('student_id', $student->id)
            ->where('status', 'success')
            ->pluck('fee_id')
            ->toArray();

        $unpaidBills = Fee::where('is_active', true)
            ->whereNotIn('id', $paidFeeIds)
            ->get();
            
        $paidBills = Payment::with('fee')
            ->where('student_id', $student->id)
            ->where('status', 'success')
            ->orderBy('payment_date', 'desc')
            ->get();
            
        $totalArrears = $unpaidBills->sum('amount');

        return view('bills', compact('unpaidBills', 'paidBills', 'totalArrears'));
    }
}

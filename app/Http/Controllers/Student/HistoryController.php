<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        if (!$student) {
            return redirect()->route('student.dashboard');
        }

        $query = Payment::with('fee')
            ->where('student_id', $student->id);

        if ($request->filled('search')) {
            $query->whereHas('fee', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhere('id', 'like', '%' . $request->search . '%');
        }

        $payments = $query->orderBy('payment_date', 'desc')->paginate(10);

        return view('history', compact('payments'));
    }
}

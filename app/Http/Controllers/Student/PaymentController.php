<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::user()->student;
        $fees = Fee::where('is_active', true)->get();
        $selectedFeeId = $request->query('fee_id');

        return view('payment', compact('fees', 'student', 'selectedFeeId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fee_id' => 'required|exists:fees,id',
            'proof_img' => 'required|image|max:5120', // 5MB max
            'note' => 'nullable|string|max:500',
        ]);

        $student = Auth::user()->student;
        if (!$student) {
            return back()->withErrors(['msg' => 'Data siswa tidak ditemukan.']);
        }

        $fee = Fee::findOrFail($request->fee_id);

        $path = null;
        if ($request->hasFile('proof_img')) {
            $path = $request->file('proof_img')->store('payments', 'public');
            $path = '/storage/' . $path;
        }

        Payment::create([
            'student_id' => $student->id,
            'fee_id' => $fee->id,
            'amount' => $fee->amount,
            'payment_date' => now(),
            'status' => 'pending',
            'proof_img' => $path,
            'note' => $request->note,
        ]);

        return redirect()->route('student.payment')->with('success_sent', true);
    }
}

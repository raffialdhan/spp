<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function index()
    {
        $pending = Payment::with(['student.classRoom', 'fee'])
            ->where('status', 'pending')
            ->orderBy('payment_date', 'asc')
            ->get();
            
        $pendingCount = $pending->count();
        $successCount = Payment::where('status', 'success')->whereDate('updated_at', now())->count();
        $rejectedCount = Payment::where('status', 'rejected')->whereDate('updated_at', now())->count();

        return view('staff.verification', compact('pending', 'pendingCount', 'successCount', 'rejectedCount'));
    }

    public function verify(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:success,rejected',
            'note' => 'nullable|string'
        ]);

        $payment->update([
            'status' => $request->status,
            'note' => $request->note,
            'verified_by' => Auth::id(),
        ]);

        $message = $request->status === 'success' ? 'Pembayaran berhasil diverifikasi!' : 'Pembayaran telah ditolak.';
        
        return redirect()->route('staff.verification')->with('success', $message);
    }
}

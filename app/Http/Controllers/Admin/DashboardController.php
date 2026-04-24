<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\User;
use App\Models\Fee;
use App\Models\Payment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Student::count();
        $totalKelas = ClassRoom::count();
        $totalPetugas = User::where('role', 'staff')->count();
        
        $totalActiveFees = Fee::where('is_active', true)->sum('amount');
        $totalSuccessPayments = Payment::where('status', 'success')->sum('amount');
        
        $totalTunggakan = ($totalSiswa * $totalActiveFees) - $totalSuccessPayments;
        if ($totalTunggakan < 0) $totalTunggakan = 0;
        
        $totalPemasukan = Payment::where('status', 'success')
            ->whereMonth('payment_date', now()->month)
            ->sum('amount');

        return view('admin.dashboard', compact(
            'totalSiswa', 
            'totalKelas', 
            'totalPetugas', 
            'totalPemasukan', 
            'totalTunggakan'
        ));
    }
}

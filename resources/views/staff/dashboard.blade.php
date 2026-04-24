@extends('layouts.staff')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Beranda Petugas</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Pantau dan kelola pembayaran SPP siswa</p>
        </div>
        <div class="flex gap-3">
            <a href="/staff/verification" class="flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-3 rounded-xl font-bold text-sm shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fa-solid fa-hourglass-half"></i> Verifikasi Pending ({{ $pendingCount }})
            </a>
            <a href="/staff/payment" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
                <i class="fa-solid fa-plus"></i> Catat Pembayaran
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg transition-all duration-300 group flex items-center gap-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center bg-blue-50 text-blue-600 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-wallet text-3xl"></i>
            </div>
            <div>
                <p class="text-slate-500 font-semibold mb-1">Total Diterima (Hari Ini)</p>
                <h3 class="text-3xl font-extrabold text-slate-800">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h3>
            </div>
        </div>

        <a href="/staff/verification" class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-amber-100 hover:shadow-lg transition-all duration-300 group flex items-center gap-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center bg-amber-50 text-amber-600 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-hourglass-half text-3xl"></i>
            </div>
            <div>
                <p class="text-slate-500 font-semibold mb-1">Menunggu Verifikasi</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $pendingCount }}</h3>
            </div>
        </a>

        <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg transition-all duration-300 group flex items-center gap-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center bg-[#d97706] text-white group-hover:scale-110 shadow-lg shadow-amber-500/30 transition-all duration-300">
                <i class="fa-solid fa-circle-exclamation text-3xl"></i>
            </div>
            <div>
                <p class="text-slate-500 font-semibold mb-1">Total Tunggakan</p>
                <h3 class="text-3xl font-extrabold text-slate-800">Rp {{ number_format($totalArrears, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Preview -->
    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 overflow-hidden relative z-0">
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white/50 backdrop-blur-sm">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Menunggu Verifikasi</h3>
                <p class="text-slate-500 text-sm mt-1">Daftar pembayaran yang perlu Anda tinjau</p>
            </div>
            <a href="/staff/verification" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-colors">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto relative">
            <table class="w-full text-left border-collapse">
                <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                    @forelse($pendingPayments as $p)
                        <tr class="hover:bg-slate-50/80 transition cursor-pointer" onclick="window.location='/staff/verification'">
                            <td class="px-8 py-5">
                                <p class="font-bold text-slate-800 text-base">{{ $p->student->name }}</p>
                                <p class="text-xs text-slate-500">{{ $p->fee->name }}</p>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <p class="font-bold text-slate-800 text-base">Rp {{ number_format($p->amount, 0, ',', '.') }}</p>
                                <span class="text-amber-500 text-xs font-bold">{{ $p->status }}</span>
                            </td>
                            <td class="px-8 py-5 text-right w-20">
                                <a href="/staff/verification" class="bg-slate-900 hover:bg-indigo-600 text-white p-2.5 rounded-xl transition-colors shadow-md inline-flex items-center justify-center">
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-8 py-10 text-center text-slate-400 font-medium">
                                Tidak ada antrian verifikasi pembayaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

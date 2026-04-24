@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Beranda</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Selamat datang kembali, {{ $student->name ?? Auth::user()->name }}!</p>
        </div>
        <a href="/payment" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold text-sm shadow-lg shadow-indigo-500/30 transition-all transform hover:-translate-y-0.5">
            <i class="fa-solid fa-plus"></i> Bayar SPP Sekarang
        </a>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-10">

        <!-- Card 1 -->
        <a href="/history" class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 group flex items-center gap-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center bg-blue-50 text-blue-600 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300">
                <i class="fa-solid fa-wallet text-3xl"></i>
            </div>
            <div>
                <p class="text-slate-500 font-semibold mb-1">Total Dibayar</p>
                <h3 class="text-3xl font-extrabold text-slate-800">Rp {{ number_format($totalPaid, 0, ',', '.') }}</h3>
            </div>
        </a>

        <!-- Card 2 -->
        <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 group flex items-center gap-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center bg-[#059669] text-white group-hover:scale-110 shadow-lg shadow-emerald-500/30 transition-all duration-300">
                <i class="fa-solid fa-users text-3xl"></i>
            </div>
            <div>
                <p class="text-slate-500 font-semibold mb-1">Petugas Aktif</p>
                <h3 class="text-3xl font-extrabold text-slate-800">{{ $activeStaff }}</h3>
            </div>
        </div>

        <!-- Card 3 -->
        <a href="/bills" class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 group flex items-center gap-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center bg-slate-50 text-slate-600 group-hover:scale-110 group-hover:bg-slate-800 group-hover:text-white transition-all duration-300">
                <i class="fa-regular fa-calendar-check text-3xl"></i>
            </div>
            <div>
                <p class="text-slate-500 font-semibold mb-1">Tagihan Saat Ini</p>
                <h3 class="text-3xl font-extrabold text-slate-800">Rp {{ number_format($currentBill, 0, ',', '.') }}</h3>
            </div>
        </a>

        <!-- Card 4 (Outstanding Debt) -->
        <a href="/bills" class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-rose-100 hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 group flex items-center gap-6">
            <div class="w-20 h-20 rounded-2xl flex items-center justify-center bg-[#d97706] text-white group-hover:scale-110 shadow-lg shadow-amber-500/30 transition-all duration-300">
                <i class="fa-solid fa-circle-exclamation text-3xl"></i>
            </div>
            <div>
                <p class="text-slate-500 font-semibold mb-1">Total Tunggakan</p>
                <h3 class="text-3xl font-extrabold text-rose-600">Rp {{ number_format($totalArrears, 0, ',', '.') }}</h3>
            </div>
        </a>

    </div>

    <!-- Recent Transactions Preview -->
    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 overflow-hidden relative z-0">
        
        <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-white/50 backdrop-blur-sm">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Transaksi Terakhir</h3>
                <p class="text-slate-500 text-sm mt-1">Riwayat pembayaran terbaru Anda</p>
            </div>
            <a href="/history" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl transition-colors">Lihat Semua</a>
        </div>

        <div class="overflow-x-auto relative">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-[11px] uppercase tracking-wider">
                        <th class="px-8 py-4 font-bold border-y border-slate-100">ID</th>
                        <th class="px-8 py-4 font-bold border-y border-slate-100">JUMLAH</th>
                        <th class="px-8 py-4 font-bold border-y border-slate-100">TAGIHAN</th>
                        <th class="px-8 py-4 font-bold border-y border-slate-100">TANGGAL</th>
                        <th class="px-8 py-4 font-bold border-y border-slate-100">STATUS</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                    
                    @forelse($recentPayments as $p)
                        <tr class="hover:bg-slate-50/80 transition group cursor-pointer" onclick="window.location='/history'">
                            <td class="px-8 py-5 font-bold text-slate-500">#TRX-{{ str_pad($p->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-5 font-bold text-slate-800 text-base">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                            <td class="px-8 py-5 text-slate-600 font-medium">{{ $p->fee->name }}</td>
                            <td class="px-8 py-5 text-slate-500">{{ $p->payment_date }}</td>
                            <td class="px-8 py-5">
                                @if($p->status === 'pending')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-500 text-white shadow-sm">
                                        <i class="fa-regular fa-clock"></i> Menunggu
                                    </span>
                                @elseif($p->status === 'success')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-500 text-white shadow-sm">
                                        <i class="fa-solid fa-check"></i> Berhasil
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-500 text-white shadow-sm">
                                        <i class="fa-solid fa-xmark"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-slate-400 font-medium">
                                Belum ada riwayat transaksi
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
    </div>

</div>
@endsection

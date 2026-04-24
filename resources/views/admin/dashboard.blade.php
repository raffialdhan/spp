@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Beranda Administrator</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Ringkasan operasional dan keuangan sekolah</p>
        </div>
        
        <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
            <div class="text-right">
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Pemasukan Bulan Ini</p>
                <p class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                <i class="fa-solid fa-chart-line text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

        <a href="/admin/students" class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg hover:border-indigo-200 transition-all duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-1">Total Siswa Aktif</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $totalSiswa }}</h3>
        </a>

        <a href="/admin/students" class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg hover:border-purple-200 transition-all duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-chalkboard-user"></i>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-1">Total Kelas</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $totalKelas }}</h3>
        </a>

        <a href="/admin/users" class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:shadow-lg hover:border-sky-200 transition-all duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-bold uppercase tracking-wider mb-1">Total Petugas</p>
            <h3 class="text-3xl font-black text-slate-800">{{ $totalPetugas }}</h3>
        </a>

        <a href="/admin/students" class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-rose-100 hover:shadow-lg transition-all duration-300 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
            </div>
            <p class="text-rose-500 text-sm font-bold uppercase tracking-wider mb-1">Total Tunggakan</p>
            <h3 class="text-3xl font-black text-slate-800">Rp {{ number_format($totalTunggakan / 1000000, 1) }}M</h3>
        </a>

    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- Left Side: Recent Entries -->
        <div class="xl:col-span-2 space-y-6">
            
            <h2 class="text-2xl font-black text-[#059669] mb-2 tracking-tight">Recent Entry</h2>
            
            <!-- Search Bar -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 flex gap-2">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Search by ID or NISN (e.g. 1234567)" class="w-full pl-10 pr-4 py-3 bg-transparent outline-none text-slate-700 font-medium placeholder:font-normal">
                </div>
                <button class="bg-indigo-900 hover:bg-indigo-800 text-white font-bold px-8 py-3 rounded-xl transition-colors shadow-md">
                    Search
                </button>
            </div>

            <!-- Entries List -->
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-2">
                
                <!-- Item 1 -->
                <div class="p-6 hover:bg-slate-50 rounded-[1.5rem] transition-colors flex justify-between items-center group cursor-pointer border-b border-slate-50 last:border-0">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fa-solid fa-arrow-down-to-line"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">Budi Santoso</h4>
                            <p class="text-sm text-slate-500">SPP April 2026 + Denda</p>
                            <p class="text-xs text-slate-400 mt-1 font-medium"><i class="fa-regular fa-clock"></i> Hari ini, 14:30 WIB</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-black text-slate-800">Rp 550,000</p>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Recorded by <span class="font-bold text-indigo-600">Pak Joko Widodo</span></p>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="p-6 hover:bg-slate-50 rounded-[1.5rem] transition-colors flex justify-between items-center group cursor-pointer border-b border-slate-50 last:border-0">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fa-solid fa-arrow-down-to-line"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">Ahmad Fauzi</h4>
                            <p class="text-sm text-slate-500">SPP Mei 2026</p>
                            <p class="text-xs text-slate-400 mt-1 font-medium"><i class="fa-regular fa-clock"></i> Hari ini, 10:15 WIB</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-black text-slate-800">Rp 500,000</p>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Recorded by <span class="font-bold text-indigo-600">Raffi Aldhan</span></p>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="p-6 hover:bg-slate-50 rounded-[1.5rem] transition-colors flex justify-between items-center group cursor-pointer border-b border-slate-50 last:border-0">
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shadow-sm">
                            <i class="fa-solid fa-arrow-down-to-line"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 text-lg">Citra Lestari</h4>
                            <p class="text-sm text-slate-500">SPP Maret 2026</p>
                            <p class="text-xs text-slate-400 mt-1 font-medium"><i class="fa-regular fa-clock"></i> Kemarin, 09:00 WIB</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-black text-slate-800">Rp 500,000</p>
                        <p class="text-xs font-medium text-slate-500 mt-0.5">Recorded by <span class="font-bold text-indigo-600">Sistem (Auto)</span></p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Right Side: Quick Actions -->
        <div class="xl:col-span-1 space-y-6">
            
            <h2 class="text-xl font-bold text-slate-800 mb-2">Aksi Cepat</h2>

            <a href="/admin/users" class="block bg-gradient-to-r from-blue-600 to-indigo-700 rounded-[2rem] p-8 text-white relative overflow-hidden group shadow-lg shadow-indigo-600/30 hover:-translate-y-1 transition-transform">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center text-2xl mb-6 backdrop-blur-sm border border-white/20">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h3 class="text-xl font-bold mb-1">Tambah Pengguna Baru</h3>
                <p class="text-indigo-100 text-sm">Daftarkan akun Siswa atau Petugas baru ke sistem.</p>
            </a>

            <a href="/admin/fees" class="block bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 hover:shadow-lg transition-all group">
                <div class="w-12 h-12 bg-slate-50 text-slate-600 rounded-xl flex items-center justify-center text-2xl mb-6 border border-slate-200 group-hover:bg-slate-900 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-gear"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-1">Konfigurasi SPP</h3>
                <p class="text-slate-500 text-sm">Atur nominal tagihan tahun ajaran baru.</p>
            </a>

        </div>

    </div>

</div>
@endsection

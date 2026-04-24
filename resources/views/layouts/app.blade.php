<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem SPP - Beranda Siswa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased h-screen overflow-hidden flex" x-data="{ sidebarOpen: true }">
    
    <!-- Sidebar -->
    <aside class="bg-[#362773] text-white flex flex-col justify-between transition-all duration-300 z-20 shrink-0 relative"
           :class="sidebarOpen ? 'w-72' : 'w-0 overflow-hidden md:w-20'">
           
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none opacity-50">
            <div class="absolute -top-24 -left-24 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl"></div>
        </div>

        <div class="w-72 relative z-10">
            <!-- Sidebar Header / Logo -->
            <div class="h-24 flex items-center justify-between px-6 border-b border-white/10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center shadow-lg shadow-purple-500/30">
                        <i class="fa-solid fa-graduation-cap text-white text-xl"></i>
                    </div>
                    <div x-show="sidebarOpen" x-transition.opacity class="whitespace-nowrap">
                        <h1 class="text-xl font-bold leading-tight tracking-wide">Sistem SPP</h1>
                        <p class="text-[11px] font-medium text-indigo-200 mt-0.5 uppercase tracking-wider">Manajemen Pembayaran</p>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="text-white/60 hover:text-white transition rounded-full p-1" x-show="sidebarOpen">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <nav class="p-4 space-y-2 mt-4">
                <a href="/dashboard" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->is('dashboard') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white font-bold shadow-lg shadow-purple-500/30 transform hover:scale-[1.02] hover:shadow-purple-500/40 relative overflow-hidden group' : 'text-indigo-200 hover:text-white hover:bg-white/10 font-medium' }}">
                    @if(request()->is('dashboard'))
                        <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500 ease-out skew-x-12"></div>
                    @endif
                    <i class="fa-solid fa-border-all text-lg w-6 text-center relative z-10"></i>
                    <span x-show="sidebarOpen" x-transition.opacity class="relative z-10">Beranda</span>
                </a>
                <a href="/bills" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->is('bills') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white font-bold shadow-lg shadow-purple-500/30 transform hover:scale-[1.02] hover:shadow-purple-500/40 relative overflow-hidden group' : 'text-indigo-200 hover:text-white hover:bg-white/10 font-medium' }}">
                    @if(request()->is('bills'))
                        <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500 ease-out skew-x-12"></div>
                    @endif
                    <i class="fa-regular fa-file-lines text-lg w-6 text-center relative z-10"></i>
                    <span x-show="sidebarOpen" x-transition.opacity class="relative z-10">Ringkasan Tagihan</span>
                </a>
                <a href="/payment" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->is('payment') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white font-bold shadow-lg shadow-purple-500/30 transform hover:scale-[1.02] hover:shadow-purple-500/40 relative overflow-hidden group' : 'text-indigo-200 hover:text-white hover:bg-white/10 font-medium' }}">
                    @if(request()->is('payment'))
                        <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500 ease-out skew-x-12"></div>
                    @endif
                    <i class="fa-solid fa-file-invoice-dollar text-lg w-6 text-center relative z-10"></i>
                    <span x-show="sidebarOpen" x-transition.opacity class="relative z-10">Pembayaran SPP</span>
                </a>
                <a href="/history" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->is('history') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white font-bold shadow-lg shadow-purple-500/30 transform hover:scale-[1.02] hover:shadow-purple-500/40 relative overflow-hidden group' : 'text-indigo-200 hover:text-white hover:bg-white/10 font-medium' }}">
                    @if(request()->is('history'))
                        <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500 ease-out skew-x-12"></div>
                    @endif
                    <i class="fa-regular fa-clock text-lg w-6 text-center relative z-10"></i>
                    <span x-show="sidebarOpen" x-transition.opacity class="relative z-10">Riwayat Transaksi</span>
                </a>
                <a href="/profile" class="flex items-center gap-4 px-4 py-3.5 rounded-xl transition-all {{ request()->is('profile') ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white font-bold shadow-lg shadow-purple-500/30 transform hover:scale-[1.02] hover:shadow-purple-500/40 relative overflow-hidden group' : 'text-indigo-200 hover:text-white hover:bg-white/10 font-medium' }}">
                    @if(request()->is('profile'))
                        <div class="absolute inset-0 bg-white/20 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-500 ease-out skew-x-12"></div>
                    @endif
                    <i class="fa-regular fa-user text-lg w-6 text-center relative z-10"></i>
                    <span x-show="sidebarOpen" x-transition.opacity class="relative z-10">Profil Saya</span>
                </a>
            </nav>
        </div>

        <div class="p-6 whitespace-nowrap relative z-10" x-show="sidebarOpen" x-transition.opacity>
            <p class="text-sm font-medium text-indigo-300/60">© 2026 Sistem Sekolah</p>
        </div>
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col min-w-0 bg-[#eff3f8] relative">
        
        <!-- Top Header -->
        <header class="h-24 bg-white/80 backdrop-blur-xl border-b border-slate-200 flex items-center justify-between px-8 z-30 shrink-0 sticky top-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" x-show="!sidebarOpen" class="text-slate-500 hover:text-slate-800 bg-white p-2.5 rounded-xl shadow-sm border border-slate-200 focus:outline-none transition transform hover:scale-105">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
                <h2 class="text-xl md:text-2xl font-bold text-[#4a3294] tracking-tight hidden sm:block">Sistem Manajemen Pembayaran SPP</h2>
            </div>

            <div class="flex items-center gap-6">
                <!-- Notifications Dropdown -->
                <div x-data="{ notifOpen: false }" class="relative">
                    <button @click="notifOpen = !notifOpen" class="relative text-slate-400 hover:text-indigo-600 transition-colors p-2">
                        <i class="fa-regular fa-bell text-xl"></i>
                        <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full"></span>
                    </button>

                    <div x-show="notifOpen" @click.away="notifOpen = false" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 py-2 z-50">
                        <div class="px-5 py-3 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-bold text-slate-800">Notifikasi</h3>
                            <a href="#" class="text-xs text-indigo-600 font-bold hover:underline">Tandai dibaca</a>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            <a href="/history" class="block px-5 py-4 hover:bg-slate-50 transition-colors border-b border-slate-50 relative">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500"></div>
                                <p class="text-sm font-bold text-slate-800 mb-0.5">Pembayaran Diverifikasi 🎉</p>
                                <p class="text-xs text-slate-500">Pembayaran Anda untuk SPP April 2026 telah diverifikasi admin.</p>
                                <p class="text-[10px] text-slate-400 mt-2 font-medium">2 jam yang lalu</p>
                            </a>
                            <a href="/bills" class="block px-5 py-4 hover:bg-slate-50 transition-colors">
                                <p class="text-sm font-bold text-slate-800 mb-0.5">Tagihan Baru</p>
                                <p class="text-xs text-slate-500">Tagihan baru untuk SPP Mei 2026 telah diterbitkan.</p>
                                <p class="text-[10px] text-slate-400 mt-2 font-medium">1 hari yang lalu</p>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Dropdown -->
                <div x-data="{ dropdownOpen: false }" class="relative">
                    <button @click="dropdownOpen = !dropdownOpen" class="flex items-center gap-3 hover:bg-slate-100 p-1.5 pr-4 rounded-full transition-all border border-transparent hover:border-slate-200">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-800 to-[#362773] text-white flex items-center justify-center font-bold shadow-md text-sm">
                            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="text-left hidden md:block">
                            <p class="text-sm font-bold text-slate-800 leading-tight">{{ Auth::user()->name ?? 'Pengguna' }}</p>
                            <p class="text-xs font-medium text-slate-500 mt-0.5">{{ ucfirst(Auth::user()->role ?? 'student') }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-slate-400 text-xs ml-2 transition-transform duration-200" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="dropdownOpen" @click.away="dropdownOpen = false" x-cloak
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                         class="absolute right-0 mt-3 w-64 bg-white rounded-2xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 py-2 z-50">

                        <div class="px-5 py-3 border-b border-slate-100">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Masuk sebagai</p>
                            <p class="font-bold text-slate-800 mt-0.5">{{ Auth::user()->name ?? '' }}</p>
                        </div>

                        <a href="/profile" class="flex items-center px-5 py-3 text-sm text-slate-700 font-bold hover:bg-slate-50 transition-colors border-b border-slate-100">
                            <i class="fa-regular fa-user mr-3 text-slate-400"></i> Profil Saya
                        </a>

                        <div class="mt-2">
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit" class="w-full flex items-center px-5 py-2.5 text-sm text-rose-600 hover:bg-rose-50 transition-colors font-bold text-left">
                                    <i class="fa-solid fa-arrow-right-from-bracket mr-3 text-rose-500"></i> Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            @yield('content')
        </main>

        <!-- Floating Help Button -->
        <button class="fixed bottom-8 right-8 w-14 h-14 bg-white text-slate-600 shadow-[0_10px_25px_rgba(0,0,0,0.1)] border border-slate-100 rounded-full flex items-center justify-center hover:scale-110 hover:text-indigo-600 transition-all duration-300 z-50 group">
            <i class="fa-solid fa-question text-xl group-hover:rotate-12 transition-transform"></i>
        </button>
    </div>

</body>
</html>

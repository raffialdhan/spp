<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem SPP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        @keyframes shimmer { 100% { transform: translateX(200%); } }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-8 antialiased bg-gradient-to-br from-blue-600 via-indigo-700 to-blue-900 relative overflow-hidden">

    <!-- Background Decoration -->
    <div class="absolute inset-0 overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-20%] left-[-10%] w-[600px] h-[600px] rounded-full bg-cyan-400/30 blur-[120px] animate-[pulse_6s_ease-in-out_infinite]"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[700px] h-[700px] rounded-full bg-blue-400/20 blur-[150px] animate-[pulse_8s_ease-in-out_infinite_alternate]"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4xNSkiLz48L3N2Zz4=')] mix-blend-overlay opacity-40"></div>
    </div>

    <!-- Login Card -->
    <div class="w-full max-w-[28rem] bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-12 relative z-10">
        
        <div class="mb-8 text-center">
            <h2 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Selamat Datang</h2>
            <p class="text-slate-500 text-sm font-medium">Sistem Manajemen Pembayaran SPP</p>
        </div>

        {{-- Error Message --}}
        @if ($errors->has('username'))
            <div class="mb-6 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-700 text-sm font-bold px-5 py-4 rounded-2xl">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg shrink-0"></i>
                <span>{{ $errors->first('username') }}</span>
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-5">
            @csrf
            
            <!-- Username -->
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">Username</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fa-regular fa-user"></i>
                    </div>
                    <input type="text" name="username" value="{{ old('username') }}"
                           placeholder="Masukkan username Anda"
                           class="w-full bg-slate-50 border-[1.5px] {{ $errors->has('username') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-200' }} text-slate-800 rounded-xl pl-12 pr-5 py-3.5 outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400 font-medium"
                           autofocus>
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-slate-700">Kata Sandi</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-5 text-slate-400 group-focus-within:text-indigo-500 transition-colors">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </div>
                    <input type="password" name="password" placeholder="••••••••"
                           class="w-full bg-slate-50 border-[1.5px] {{ $errors->has('username') ? 'border-rose-400 bg-rose-50/30' : 'border-slate-200' }} text-slate-800 rounded-xl pl-12 pr-12 py-3.5 outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all tracking-widest placeholder:tracking-normal font-medium"
                           id="passwordInput">
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center pr-5 text-slate-400 hover:text-slate-600 cursor-pointer transition-colors">
                        <i class="fa-regular fa-eye text-sm" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <div class="relative flex items-center justify-center">
                        <input type="checkbox" name="remember" class="peer appearance-none w-5 h-5 border-2 border-slate-300 rounded-[5px] bg-white checked:bg-indigo-600 checked:border-indigo-600 focus:ring-2 focus:ring-indigo-500/30 focus:outline-none transition-all cursor-pointer">
                        <i class="fa-solid fa-check absolute text-white text-[10px] opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></i>
                    </div>
                    <span class="text-sm font-bold text-slate-600 group-hover:text-slate-900 transition-colors">Ingat saya</span>
                </label>
                <a href="#" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Lupa kata sandi?</a>
            </div>

            <!-- Submit -->
            <div class="pt-3">
                <button type="submit" class="w-full bg-[#0f172a] hover:bg-indigo-600 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 shadow-lg shadow-slate-900/20 hover:shadow-indigo-600/30 transform hover:-translate-y-1 relative overflow-hidden group">
                    <span class="relative z-10 flex items-center justify-center gap-2 text-[15px]">
                        Masuk ke Akun <i class="fa-solid fa-arrow-right-long group-hover:translate-x-1 transition-transform duration-300"></i>
                    </span>
                    <div class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] skew-x-12 z-0"></div>
                </button>
            </div>

        </form>



    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
</body>
</html>

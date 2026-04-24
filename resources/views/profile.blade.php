@extends(Auth::user()->role === 'admin' ? 'layouts.admin' : (Auth::user()->role === 'staff' ? 'layouts.staff' : 'layouts.app'))

@section('content')
<div class="max-w-4xl mx-auto" x-data="{
    newPass: '',
    confirmPass: ''
}">
    
    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Profil Saya</h1>
        <p class="text-slate-500 mt-2 font-medium">Kelola informasi pribadi dan pengaturan keamanan Anda</p>
    </div>

    <!-- Success Toasts -->
    @if(session('success_info'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-cloak
         class="fixed top-6 right-6 z-[999] flex items-center gap-3 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-check-circle text-xl"></i><span class="font-bold">{{ session('success_info') }}</span>
    </div>
    @endif

    @if(session('success_password'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-cloak
         class="fixed top-6 right-6 z-[999] flex items-center gap-3 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-lock text-xl"></i><span class="font-bold">{{ session('success_password') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <!-- Left Column: Profile Card -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-blue-500 to-purple-500"></div>
                
                <div class="relative mt-8 mb-4">
                    <div class="w-24 h-24 mx-auto rounded-full bg-white p-1 shadow-lg flex items-center justify-center">
                        <div class="w-full h-full rounded-full bg-indigo-900 text-white flex items-center justify-center text-3xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
                
                <h3 class="text-xl font-bold text-slate-800">{{ $user->name }}</h3>
                <p class="text-indigo-600 font-bold text-sm mb-4">{{ ucfirst($user->role) }}</p>
                
                <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-bold mb-6">
                    <i class="fa-solid fa-check-circle"></i> Status Aktif
                </div>

                @if($student)
                <div class="border-t border-slate-100 pt-6 space-y-4 text-left">
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">NISN</p>
                        <p class="text-sm font-medium text-slate-800">{{ $student->nisn }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">Kelas</p>
                        <p class="text-sm font-medium text-slate-800">{{ $student->classRoom->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">Angkatan</p>
                        <p class="text-sm font-medium text-slate-800">{{ $student->academic_year }}</p>
                    </div>
                </div>

                <a href="/bills" class="mt-6 w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg text-sm">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Lihat Tagihan Saya
                </a>
                @endif
            </div>
        </div>

        <!-- Right Column -->
        <div class="md:col-span-2 space-y-6">
            
            <!-- Personal Information -->
            <form action="{{ route('profile.update.info') }}" method="POST" class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-10">
                @csrf
                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fa-regular fa-id-card text-indigo-500"></i> Informasi Pribadi
                </h3>
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-5 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium">
                            @error('name') <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $student->phone ?? '') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-5 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium">
                            @error('phone') <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-5 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium">
                        @error('email') <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Alamat Rumah</label>
                        <textarea name="address" rows="3" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-5 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium">{{ old('address', $student->address ?? '') }}</textarea>
                        @error('address') <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-1">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Security Section -->
            <form action="{{ route('profile.update.password') }}" method="POST" class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-10">
                @csrf
                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-emerald-500"></i> Keamanan
                </h3>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-5 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all">
                        @error('current_password') <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Kata Sandi Baru</label>
                            <input type="password" name="new_password" required x-model="newPass" placeholder="Min. 6 karakter"
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-5 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all">
                            @error('new_password') <p class="text-rose-500 text-xs font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="new_password_confirmation" required x-model="confirmPass" placeholder="••••••••"
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-5 py-3 outline-none focus:bg-white transition-all">
                        </div>
                    </div>
                    <div class="flex justify-end pt-2">
                        <button type="submit" class="flex items-center gap-2 bg-slate-900 hover:bg-slate-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition transform hover:-translate-y-1">
                            <i class="fa-solid fa-lock"></i> Perbarui Kata Sandi
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection

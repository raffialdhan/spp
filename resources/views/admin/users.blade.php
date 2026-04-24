@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">

    {{-- ============================================================ --}}
    {{-- FLASH MESSAGES --}}
    {{-- ============================================================ --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed top-6 right-6 z-[999] flex items-center gap-3 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-check-circle text-xl"></i>
        <span class="font-bold">{{ session('success') }}</span>
        <button @click="show = false" class="ml-2 text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-6 right-6 z-[999] flex items-center gap-3 bg-rose-600 text-white px-6 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-circle-exclamation text-xl"></i>
        <span class="font-bold">{{ session('error') }}</span>
        <button @click="show = false" class="ml-2 text-white/70 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
    </div>
    @endif

    {{-- ============================================================ --}}
    {{-- HEADER --}}
    {{-- ============================================================ --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Manajemen Pengguna</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Kelola akses untuk Administrator dan Petugas Sistem</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')"
                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
            <i class="fa-solid fa-plus"></i> Tambah Pengguna
        </button>
    </div>

    {{-- ============================================================ --}}
    {{-- SEARCH & FILTER BAR --}}
    {{-- ============================================================ --}}
    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">

        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <form method="GET" action="{{ route('admin.users') }}" class="flex flex-col sm:flex-row gap-3 items-center">
                <div class="relative flex-1 w-full sm:max-w-xs">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama, username, atau email..."
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all shadow-sm">
                </div>
                <select name="role" onchange="this.form.submit()"
                        class="bg-white border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 focus:outline-none shadow-sm cursor-pointer">
                    <option value="all"   {{ request('role', 'all') === 'all'  ? 'selected' : '' }}>Semua Peran</option>
                    <option value="admin" {{ request('role') === 'admin'       ? 'selected' : '' }}>Administrator</option>
                    <option value="staff" {{ request('role') === 'staff'       ? 'selected' : '' }}>Petugas</option>
                </select>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition shadow-md shadow-indigo-500/20">
                    <i class="fa-solid fa-search mr-1"></i> Cari
                </button>
                @if(request('search') || (request('role') && request('role') !== 'all'))
                <a href="{{ route('admin.users') }}" class="text-sm text-slate-500 hover:text-slate-800 font-medium underline">Reset</a>
                @endif
                <p class="text-sm text-slate-400 font-medium ml-auto">{{ $users->count() }} pengguna ditemukan</p>
            </form>
        </div>

        {{-- ============================================================ --}}
        {{-- USERS TABLE --}}
        {{-- ============================================================ --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-white text-slate-400 text-xs uppercase tracking-widest border-b border-slate-100">
                        <th class="px-8 py-5 font-bold">Profil Pengguna</th>
                        <th class="px-8 py-5 font-bold">Username</th>
                        <th class="px-8 py-5 font-bold">Peran</th>
                        <th class="px-8 py-5 font-bold">Terdaftar</th>
                        <th class="px-8 py-5 font-bold">Status</th>
                        <th class="px-8 py-5 font-bold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition {{ !$user->email_verified_at ? 'opacity-60' : '' }}">
                        {{-- Avatar + Name --}}
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shadow-md
                                    {{ $user->role === 'admin' ? 'bg-indigo-900 text-white' : 'bg-blue-100 text-blue-600' }}">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-base">{{ $user->name }}</h4>
                                    <p class="text-xs text-slate-500">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Username --}}
                        <td class="px-8 py-5">
                            <span class="font-mono text-sm font-medium text-slate-600 bg-slate-100 px-3 py-1 rounded-lg">{{ $user->username }}</span>
                        </td>

                        {{-- Role Badge --}}
                        <td class="px-8 py-5">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold border
                                {{ $user->role === 'admin' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                <i class="{{ $user->role === 'admin' ? 'fa-solid fa-shield-halved' : 'fa-solid fa-user-tie' }}"></i>
                                {{ $user->role === 'admin' ? 'Administrator' : 'Petugas' }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td class="px-8 py-5 text-slate-500 font-medium">
                            {{ $user->created_at->format('d M Y') }}
                        </td>

                        {{-- Status Toggle --}}
                        <td class="px-8 py-5">
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="flex items-center gap-2 hover:opacity-80 transition" title="Klik untuk ubah status">
                                    <span class="w-2.5 h-2.5 rounded-full {{ $user->email_verified_at ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]' : 'bg-slate-300' }}"></span>
                                    <span class="font-bold {{ $user->email_verified_at ? 'text-slate-600' : 'text-slate-400' }}">
                                        {{ $user->email_verified_at ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </button>
                            </form>
                        </td>

                        {{-- Actions --}}
                        <td class="px-8 py-5 text-center">
                            {{-- Edit Button --}}
                            <button onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->username }}', '{{ $user->email }}', '{{ $user->role }}')"
                                    class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-colors mx-1">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            {{-- Delete Button (cannot delete self) --}}
                            @if($user->id !== auth()->id())
                            <button onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                    class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors mx-1">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <i class="fa-solid fa-users-slash text-4xl text-slate-300"></i>
                                <p class="font-bold text-slate-500">Tidak ada pengguna ditemukan</p>
                                <p class="text-sm">Coba ubah kata kunci pencarian atau tambah pengguna baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- ADD MODAL --}}
    {{-- ============================================================ --}}
    <div id="addModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('addModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8 z-10 animate-[fadeIn_.2s_ease]">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800">Tambah Pengguna Baru</h2>
                    <p class="text-sm text-slate-400 mt-1">Data akan langsung disimpan ke database</p>
                </div>
                <button onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="w-9 h-9 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-full flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" id="addForm">
                @csrf
                <div class="space-y-4">

                    {{-- Validation Errors (for Add) --}}
                    @if($errors->any() && old('_form') === 'add')
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <input type="hidden" name="_form" value="add">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   placeholder="Contoh: Budi Santoso"
                                   class="w-full bg-slate-50 border {{ $errors->has('name') && old('_form') === 'add' ? 'border-rose-400' : 'border-slate-200' }} text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Username <span class="text-rose-500">*</span></label>
                            <input type="text" name="username" value="{{ old('username') }}" required
                                   placeholder="username_unik"
                                   class="w-full bg-slate-50 border {{ $errors->has('username') && old('_form') === 'add' ? 'border-rose-400' : 'border-slate-200' }} text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Peran <span class="text-rose-500">*</span></label>
                            <select name="role" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-bold">
                                <option value="staff"  {{ old('role') === 'staff'  ? 'selected' : 'selected' }}>Petugas</option>
                                <option value="admin"  {{ old('role') === 'admin'  ? 'selected' : '' }}>Administrator</option>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="email@sekolah.com"
                                   class="w-full bg-slate-50 border {{ $errors->has('email') && old('_form') === 'add' ? 'border-rose-400' : 'border-slate-200' }} text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Kata Sandi <span class="text-rose-500">*</span></label>
                            <input type="password" name="password" required
                                   placeholder="Min. 6 karakter"
                                   class="w-full bg-slate-50 border {{ $errors->has('password') && old('_form') === 'add' ? 'border-rose-400' : 'border-slate-200' }} text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium">
                        </div>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                                class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-user-plus"></i> Simpan Pengguna
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- EDIT MODAL --}}
    {{-- ============================================================ --}}
    <div id="editModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('editModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8 z-10">

            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-extrabold text-slate-800">Edit Pengguna</h2>
                    <p class="text-sm text-slate-400 mt-1">Kosongkan kata sandi jika tidak ingin mengubahnya</p>
                </div>
                <button onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="w-9 h-9 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-full flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" id="editForm" action="">
                @csrf @method('PUT')
                <div class="space-y-4">

                    {{-- Validation Errors (for Edit) --}}
                    @if($errors->any() && old('_form') === 'edit')
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3 rounded-xl">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <input type="hidden" name="_form" value="edit">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="editName" required
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Username <span class="text-rose-500">*</span></label>
                            <input type="text" name="username" id="editUsername" required
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium font-mono">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Peran <span class="text-rose-500">*</span></label>
                            <select name="role" id="editRole" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-bold">
                                <option value="staff">Petugas</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" id="editEmail" required
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium">
                        </div>

                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Kata Sandi Baru <span class="text-slate-400 font-normal text-xs">(Kosongkan jika tidak diubah)</span></label>
                            <input type="password" name="password"
                                   placeholder="Isi jika ingin mengubah kata sandi"
                                   class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition-all font-medium">
                        </div>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                                class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- DELETE MODAL --}}
    {{-- ============================================================ --}}
    <div id="deleteModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="document.getElementById('deleteModal').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-sm p-8 text-center z-10">
            <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="fa-solid fa-trash-can text-2xl"></i>
            </div>
            <h2 class="text-xl font-extrabold text-slate-800 mb-2">Hapus Pengguna?</h2>
            <p class="text-slate-500 mb-2">Akun <span id="deleteUserName" class="font-bold text-slate-800"></span> akan dihapus secara permanen dari sistem.</p>
            <p class="text-xs text-rose-500 font-medium mb-8">Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button onclick="document.getElementById('deleteModal').classList.add('hidden')"
                        class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">
                    Batal
                </button>
                <form id="deleteForm" method="POST" action="" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-rose-500/30">
                        <i class="fa-solid fa-trash-can mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

{{-- Reopen add modal if validation failed for add form --}}
@if($errors->any() && old('_form') === 'add')
<script>document.getElementById('addModal').classList.remove('hidden');</script>
@endif

{{-- Reopen edit modal if validation failed for edit form --}}
@if($errors->any() && old('_form') === 'edit')
<script>
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editName').value = '{{ old("name") }}';
    document.getElementById('editUsername').value = '{{ old("username") }}';
    document.getElementById('editEmail').value = '{{ old("email") }}';
    document.getElementById('editRole').value = '{{ old("role") }}';
</script>
@endif

<script>
function openEditModal(id, name, username, email, role) {
    document.getElementById('editForm').action = '/admin/users/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editUsername').value = username;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value = role;
    document.getElementById('editModal').classList.remove('hidden');
}

function openDeleteModal(id, name) {
    document.getElementById('deleteForm').action = '/admin/users/' + id;
    document.getElementById('deleteUserName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('addModal').classList.add('hidden');
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.add('hidden');
    }
});
</script>
@endsection

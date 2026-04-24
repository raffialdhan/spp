@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    
    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-6 right-6 z-[999] flex items-center gap-3 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-check-circle text-xl"></i><span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Data Master Siswa</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Kelola database siswa, penempatan kelas, dan kenaikan kelas</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5 text-sm">
                <i class="fa-solid fa-user-plus"></i> Tambah Siswa
            </button>
        </div>
    </div>

    <!-- Filters & Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
        <div class="lg:col-span-3 bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col md:flex-row gap-4 items-center">
            <form method="GET" action="{{ route('admin.students') }}" class="flex flex-col md:flex-row gap-4 items-center w-full">
                <div class="relative flex-1 w-full">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NISN atau Nama Siswa..." class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-indigo-500 focus:bg-white transition-all">
                </div>
                <div class="flex gap-4 w-full md:w-auto">
                    <select name="class_id" onchange="this.form.submit()" class="flex-1 md:w-40 bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 focus:outline-none shadow-sm cursor-pointer">
                        <option value="all">Semua Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    <select name="year" onchange="this.form.submit()" class="flex-1 md:w-40 bg-white border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-600 focus:outline-none shadow-sm cursor-pointer">
                        <option value="all">Semua Angkatan</option>
                        @foreach($years as $y)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="hidden">Cari</button>
            </form>
        </div>
        <div class="bg-indigo-900 rounded-[2rem] p-6 shadow-lg shadow-indigo-900/20 text-white flex flex-col justify-center relative overflow-hidden">
            <div class="absolute -right-6 -top-6 text-white/5 text-8xl"><i class="fa-solid fa-users"></i></div>
            <p class="text-indigo-200 text-sm font-bold uppercase tracking-wider mb-1 relative z-10">Total Siswa Aktif</p>
            <h3 class="text-4xl font-black relative z-10">{{ $students->count() }}</h3>
        </div>
    </div>

    <!-- Student Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($students as $s)
            <div class="bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.03)] border border-slate-100 hover:border-indigo-200 transition-all relative">
                <!-- Actions dropdown -->
                <div class="absolute top-4 right-4 flex gap-1">
                    <button onclick="openEditModal({{ $s->id }}, '{{ addslashes($s->name) }}', '{{ $s->nisn }}', '{{ $s->nis }}', '{{ $s->class_room_id }}', '{{ $s->academic_year }}', '{{ $s->phone }}', '{{ addslashes($s->address) }}')" 
                            class="w-8 h-8 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <button onclick="openDeleteModal({{ $s->id }}, '{{ addslashes($s->name) }}')"
                            class="w-8 h-8 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-trash text-xs"></i>
                    </button>
                </div>

                <div class="flex items-center gap-4 mb-5">
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-400 to-indigo-600 text-white flex items-center justify-center font-bold text-xl shadow-md">
                        {{ substr($s->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $s->name }}</h3>
                        <p class="text-xs font-bold text-indigo-500">NISN: {{ $s->nisn }}</p>
                    </div>
                </div>
                
                <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-100 space-y-2">
                    <div class="flex justify-between text-sm"><span class="text-slate-500">Kelas</span><span class="font-bold text-slate-800">{{ $s->classRoom->name }}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-slate-500">Angkatan</span><span class="font-bold text-slate-800">{{ $s->academic_year }}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-slate-500">Telepon</span><span class="font-bold text-slate-800">{{ $s->phone ?? '-' }}</span></div>
                </div>

                <button onclick="openDetailModal('{{ addslashes($s->name) }}', '{{ $s->nisn }}', '{{ $s->nis }}', '{{ $s->classRoom->name }}', '{{ $s->academic_year }}', '{{ $s->phone }}', '{{ addslashes($s->address) }}')" 
                        class="w-full bg-white border-2 border-slate-100 hover:border-indigo-600 hover:text-indigo-600 text-slate-600 font-bold py-2.5 rounded-xl transition-colors text-sm">
                    Lihat Detail
                </button>
            </div>
        @empty
            <div class="col-span-3 py-16 text-center text-slate-400 font-medium">
                <i class="fa-solid fa-users-slash text-4xl mb-3 block text-slate-300"></i>
                Tidak ada data siswa ditemukan
            </div>
        @endforelse
    </div>

    <!-- DETAIL MODAL -->
    <div id="detailModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('detailModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-sm p-8 z-10">
            <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="absolute top-5 right-5 w-9 h-9 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center transition"><i class="fa-solid fa-xmark text-slate-500"></i></button>
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 text-white flex items-center justify-center font-bold text-2xl shadow-lg mx-auto mb-4" id="detailInitial">A</div>
            <h2 class="text-xl font-extrabold text-slate-800 text-center mb-1" id="detailName"></h2>
            <p class="text-indigo-500 text-xs font-bold text-center mb-6" id="detailNisn"></p>
            <div class="space-y-3 divide-y divide-slate-100 text-sm">
                <div class="flex justify-between pt-3"><span class="text-slate-500">NIS</span><span class="font-bold" id="detailNis"></span></div>
                <div class="flex justify-between pt-3"><span class="text-slate-500">Kelas</span><span class="font-bold" id="detailClass"></span></div>
                <div class="flex justify-between pt-3"><span class="text-slate-500">Angkatan</span><span class="font-bold" id="detailYear"></span></div>
                <div class="flex justify-between pt-3"><span class="text-slate-500">Telepon</span><span class="font-bold" id="detailPhone"></span></div>
                <div class="pt-3"><span class="text-slate-500 block mb-1">Alamat</span><span class="font-medium text-slate-700" id="detailAddress"></span></div>
            </div>
            <div class="mt-8">
                <button onclick="document.getElementById('detailModal').classList.add('hidden')" class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition text-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ADD MODAL -->
    <div id="addModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('addModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">Tambah Siswa Baru</h2>
                <button onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark text-slate-500"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.students.store') }}">
                @csrf
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="name" required placeholder="Masukkan nama..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">NISN</label>
                            <input type="text" name="nisn" required maxlength="10" placeholder="10 digit NISN" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">NIS</label>
                            <input type="text" name="nis" required maxlength="8" placeholder="8 digit NIS" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Kelas</label>
                            <select name="class_room_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Angkatan</label>
                            <input type="text" name="academic_year" required placeholder="2026" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Telepon</label>
                        <input type="text" name="phone" placeholder="08xxxxxxxxxx" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat</label>
                        <textarea name="address" rows="2" placeholder="Masukkan alamat lengkap..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></textarea>
                    </div>
                    <div class="flex items-center gap-2 py-2">
                        <input type="checkbox" name="create_user" value="1" id="create_user" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="create_user" class="text-sm font-bold text-slate-700">Buat akun login siswa (Password: NISN)</label>
                        <input type="hidden" name="password" id="default_pass">
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30">
                            <i class="fa-solid fa-user-plus mr-2"></i>Tambah Siswa
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('editModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-lg p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">Edit Data Siswa</h2>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark text-slate-500"></i></button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf @method('PUT')
                <div class="space-y-4">
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" id="editName" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-sm font-bold text-slate-700 mb-1.5">NISN</label>
                            <input type="text" name="nisn" id="editNisn" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                        <div><label class="block text-sm font-bold text-slate-700 mb-1.5">NIS</label>
                            <input type="text" name="nis" id="editNis" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                        <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Kelas</label>
                            <select name="class_room_id" id="editClassId" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold">
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Angkatan</label>
                            <input type="text" name="academic_year" id="editYear" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                    </div>
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Nomor Telepon</label>
                        <input type="text" name="phone" id="editPhone" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat</label>
                        <textarea name="address" id="editAddress" rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></textarea></div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('deleteModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-sm p-8 text-center z-10">
            <div class="w-16 h-16 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center mx-auto mb-5"><i class="fa-solid fa-trash-can text-2xl"></i></div>
            <h2 class="text-xl font-extrabold text-slate-800 mb-2">Hapus Data Siswa?</h2>
            <p class="text-slate-500 mb-8">Data <span class="font-bold text-slate-800" id="deleteName"></span> akan dihapus permanen.</p>
            <form id="deleteForm" method="POST" action="">
                @csrf @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                    <button type="submit" class="flex-1 bg-rose-500 hover:bg-rose-600 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-rose-500/30">Hapus</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function openDetailModal(name, nisn, nis, className, year, phone, address) {
    document.getElementById('detailName').textContent = name;
    document.getElementById('detailInitial').textContent = name.charAt(0).toUpperCase();
    document.getElementById('detailNisn').textContent = 'NISN: ' + nisn;
    document.getElementById('detailNis').textContent = nis;
    document.getElementById('detailClass').textContent = className;
    document.getElementById('detailYear').textContent = year;
    document.getElementById('detailPhone').textContent = phone || '-';
    document.getElementById('detailAddress').textContent = address || '-';
    document.getElementById('detailModal').classList.remove('hidden');
}

function openEditModal(id, name, nisn, nis, classId, year, phone, address) {
    document.getElementById('editForm').action = '/admin/students/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editNisn').value = nisn;
    document.getElementById('editNis').value = nis;
    document.getElementById('editClassId').value = classId;
    document.getElementById('editYear').value = year;
    document.getElementById('editPhone').value = phone;
    document.getElementById('editAddress').value = address;
    document.getElementById('editModal').classList.remove('hidden');
}

function openDeleteModal(id, name) {
    document.getElementById('deleteForm').action = '/admin/students/' + id;
    document.getElementById('deleteName').textContent = name;
    document.getElementById('deleteModal').classList.remove('hidden');
}

// Set password to NISN when checkbox is checked
document.getElementById('create_user').addEventListener('change', function() {
    if(this.checked) {
        const nisn = document.querySelector('input[name="nisn"]').value;
        document.getElementById('default_pass').value = nisn;
    }
});
</script>
@endsection

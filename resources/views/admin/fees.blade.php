@extends('layouts.admin')

@section('content')
<div class="max-w-6xl mx-auto">
    
    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-6 right-6 z-[999] flex items-center gap-3 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-check-circle text-xl"></i><span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Konfigurasi SPP</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Kelola nominal dan kategori tagihan untuk seluruh siswa</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-indigo-500/30 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
            <i class="fa-solid fa-file-invoice-dollar"></i> Buat Kategori Baru
        </button>
    </div>

    <!-- Active Fees -->
    <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-circle-check text-emerald-500"></i> Kategori Tagihan Aktif
        <span class="text-sm font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg ml-2">{{ $fees->count() }} aktif</span>
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        @foreach($fees as $f)
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative group transition-all">
                <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                <div class="p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <span class="inline-block px-3 py-1 rounded-lg text-xs font-bold mb-3 border 
                                {{ $f->type === 'Bulanan' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                                {{ $f->type }}
                            </span>
                            <h3 class="text-2xl font-bold text-slate-800 leading-tight">{{ $f->name }}</h3>
                        </div>
                        <form method="POST" action="{{ route('admin.fees.toggle', $f) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="relative w-11 h-6 rounded-full transition-colors flex-shrink-0 bg-emerald-500">
                                <span class="absolute top-0.5 w-5 h-5 bg-white rounded-full shadow transition-all left-5"></span>
                            </button>
                        </form>
                    </div>
                    
                    <p class="text-sm text-slate-500 mb-6 font-medium">{{ $f->description }}</p>
                    
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100 flex justify-between items-center mb-6">
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-wider">Nominal Dasar</p>
                        <p class="text-3xl font-extrabold text-slate-800">Rp {{ number_format($f->amount, 0, ',', '.') }}</p>
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="openEditModal({{ $f->id }}, '{{ addslashes($f->name) }}', '{{ addslashes($f->description) }}', {{ $f->amount }}, '{{ $f->type }}')" 
                                class="flex-1 bg-white border-2 border-slate-100 hover:border-indigo-600 hover:text-indigo-600 text-slate-600 font-bold py-2.5 rounded-xl transition-colors text-sm">
                            <i class="fa-solid fa-pen mr-2"></i> Edit Nominal
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Archived -->
    @if($archived->count() > 0)
    <h2 class="text-lg font-bold text-slate-500 mb-4 flex items-center gap-2">
        <i class="fa-solid fa-box-archive"></i> Arsip Tagihan (Nonaktif)
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($archived as $f)
            <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-200 opacity-70 hover:opacity-100 transition-opacity">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-600 leading-tight">{{ $f->name }}</h3>
                        <p class="text-xs font-bold text-slate-400 mt-1">{{ $f->description }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.fees.toggle', $f) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="text-xs bg-white border border-slate-200 text-slate-500 hover:text-emerald-600 hover:border-emerald-300 font-bold px-3 py-1.5 rounded-lg transition-colors">
                            Aktifkan
                        </button>
                    </form>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <p class="text-xl font-bold text-slate-500">Rp {{ number_format($f->amount, 0, ',', '.') }}</p>
                </div>
            </div>
        @endforeach
    </div>
    @endif

    <!-- ADD MODAL -->
    <div id="addModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('addModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">Kategori Tagihan Baru</h2>
                <button onclick="document.getElementById('addModal').classList.add('hidden')" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark text-slate-500"></i></button>
            </div>
            <form method="POST" action="{{ route('admin.fees.store') }}">
                @csrf
                <div class="space-y-5">
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Kategori</label>
                        <input type="text" name="name" required placeholder="Contoh: Dana Kegiatan" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></div>
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" placeholder="Deskripsi singkat..." rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></textarea></div>
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Jenis</label>
                        <select name="type" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold">
                            <option value="Bulanan">Bulanan</option><option value="Sekali Bayar">Sekali Bayar</option><option value="Tahunan">Tahunan</option>
                        </select></div>
                    <div><label class="block text-sm font-bold text-slate-700 mb-1.5">Nominal (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                            <input type="number" name="amount" required placeholder="500000" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-12 pr-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold text-xl">
                        </div></div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30">
                            <i class="fa-solid fa-plus mr-2"></i>Buat Kategori
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div onclick="document.getElementById('editModal').classList.add('hidden')" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 z-10">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-extrabold text-slate-800">Edit Konfigurasi SPP</h2>
                <button onclick="document.getElementById('editModal').classList.add('hidden')" class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center"><i class="fa-solid fa-xmark text-slate-500"></i></button>
            </div>
            <form id="editForm" method="POST" action="">
                @csrf @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Kategori</label>
                        <input type="text" name="name" id="editName" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Deskripsi</label>
                        <textarea name="description" id="editDesc" rows="2" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-medium"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Nominal (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                            <input type="number" name="amount" id="editAmount" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl pl-12 pr-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold text-xl">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Jenis</label>
                        <select name="type" id="editType" class="w-full bg-slate-50 border border-slate-200 text-slate-800 rounded-xl px-4 py-3 outline-none focus:bg-white focus:border-indigo-500 transition font-bold">
                            <option value="Bulanan">Bulanan</option>
                            <option value="Sekali Bayar">Sekali Bayar</option>
                            <option value="Tahunan">Tahunan</option>
                        </select>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition">Batal</button>
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-indigo-600/30"><i class="fa-solid fa-floppy-disk mr-2"></i>Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function openEditModal(id, name, desc, amount, type) {
    document.getElementById('editForm').action = '/admin/fees/' + id;
    document.getElementById('editName').value = name;
    document.getElementById('editDesc').value = desc;
    document.getElementById('editAmount').value = amount;
    document.getElementById('editType').value = type;
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endsection

@extends('layouts.staff')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{ 
    modalOpen: false, 
    activeId: null,
    activeStudent: '', 
    activeAmount: '', 
    activeBill: '', 
    activeImg: '',
    activeNote: ''
}">
    
    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-6 right-6 z-[999] flex items-center gap-3 bg-emerald-600 text-white px-6 py-4 rounded-2xl shadow-2xl">
        <i class="fa-solid fa-check-circle text-xl"></i><span class="font-bold">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Verifikasi Pembayaran</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Tinjau dan verifikasi bukti transfer yang diunggah siswa</p>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-amber-50 rounded-[1.5rem] p-5 border border-amber-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-amber-600 uppercase tracking-wider mb-0.5">Menunggu</p>
                <p class="text-2xl font-extrabold text-amber-700">{{ $pendingCount }}</p>
            </div>
        </div>
        <div class="bg-emerald-50 rounded-[1.5rem] p-5 border border-emerald-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-0.5">Selesai Hari Ini</p>
                <p class="text-2xl font-extrabold text-emerald-700">{{ $successCount }}</p>
            </div>
        </div>
        <div class="bg-rose-50 rounded-[1.5rem] p-5 border border-rose-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-rose-600 uppercase tracking-wider mb-0.5">Ditolak</p>
                <p class="text-2xl font-extrabold text-rose-700">{{ $rejectedCount }}</p>
            </div>
        </div>
    </div>

    <!-- Verification Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        @forelse($pending as $p)
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg">
                                {{ substr($p->student->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $p->student->name }}</h3>
                                <p class="text-xs font-medium text-slate-500">NISN: {{ $p->student->nisn }}</p>
                            </div>
                        </div>
                        <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-lg border border-amber-200">Pending</span>
                    </div>
                    
                    <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-100">
                        <div class="flex justify-between items-center mb-2">
                            <p class="text-xs text-slate-500 font-bold uppercase">Tagihan</p>
                            <p class="text-sm font-bold text-slate-800">{{ $p->fee->name }}</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-xs text-slate-500 font-bold uppercase">Jumlah</p>
                            <p class="text-lg font-extrabold text-indigo-600">Rp {{ number_format($p->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <button @click="modalOpen = true; 
                                   activeId = {{ $p->id }};
                                   activeStudent = '{{ addslashes($p->student->name) }} ({{ $p->student->classRoom->name }})'; 
                                   activeAmount = 'Rp {{ number_format($p->amount, 0, ',', '.') }}'; 
                                   activeBill = '{{ addslashes($p->fee->name) }}'; 
                                   activeImg = '{{ $p->proof_img }}';
                                   activeNote = '{{ addslashes($p->note) }}';" 
                            class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-2.5 rounded-xl transition-colors flex items-center justify-center gap-2 text-sm mb-4">
                        <i class="fa-solid fa-image text-indigo-500"></i> Lihat Bukti Transfer
                    </button>

                    <div class="flex gap-3">
                        <form action="{{ route('staff.verification.verify', $p) }}" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="success">
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl transition-colors shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-check"></i> Terima
                            </button>
                        </form>
                        <form action="{{ route('staff.verification.verify', $p) }}" method="POST" class="flex-1">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="w-full bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold py-3 rounded-xl transition-colors flex items-center justify-center gap-2 border border-rose-200">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-16 text-center text-slate-400 font-medium bg-white rounded-[2rem] border border-slate-100">
                <i class="fa-solid fa-check-double text-4xl mb-3 block text-emerald-300"></i>
                Semua pembayaran telah diverifikasi
            </div>
        @endforelse

    </div>

    <!-- Alpine JS Modal for Receipt Preview -->
    <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
        <!-- Backdrop -->
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false"></div>
        
        <!-- Modal Content -->
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col md:flex-row overflow-hidden">
            
            <!-- Close Button -->
            <button @click="modalOpen = false" class="absolute top-4 right-4 z-10 w-10 h-10 bg-black/10 hover:bg-black/20 text-white rounded-full flex items-center justify-center backdrop-blur transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>

            <!-- Image Side -->
            <div class="md:w-1/2 bg-slate-100 min-h-[300px] flex items-center justify-center relative group">
                <img :src="activeImg || 'https://via.placeholder.com/600x800?text=Bukti+Transfer'" class="w-full h-full object-contain max-h-[90vh]">
            </div>

            <!-- Details Side -->
            <div class="md:w-1/2 p-8 md:p-10 flex flex-col bg-white">
                <h2 class="text-2xl font-extrabold text-slate-800 mb-2">Detail Verifikasi</h2>
                <p class="text-slate-500 font-medium mb-8">Pastikan nominal pada struk sesuai dengan tagihan sistem.</p>
                
                <div class="space-y-6 flex-1">
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Siswa</p>
                        <p class="text-lg font-bold text-slate-800" x-text="activeStudent"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Tagihan Dibayar</p>
                        <p class="text-lg font-bold text-slate-800" x-text="activeBill"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Nominal Sistem</p>
                        <p class="text-3xl font-extrabold text-indigo-600" x-text="activeAmount"></p>
                    </div>
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mt-4" x-show="activeNote">
                        <p class="text-sm font-bold text-amber-700 flex items-center gap-2">
                            <i class="fa-solid fa-triangle-exclamation"></i> Catatan Siswa:
                        </p>
                        <p class="text-sm text-amber-600 mt-1 italic" x-text="activeNote"></p>
                    </div>
                </div>

                <div class="flex gap-4 mt-8 pt-6 border-t border-slate-100">
                    <form :action="'/staff/verification/' + activeId" method="POST" class="flex-1">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="success">
                        <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-4 rounded-xl transition-colors shadow-lg shadow-emerald-500/30 flex items-center justify-center gap-2 text-lg">
                            <i class="fa-solid fa-check"></i> Verifikasi Sah
                        </button>
                    </form>
                    <form :action="'/staff/verification/' + activeId" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="w-16 h-full bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-xl transition-colors flex items-center justify-center border border-rose-200">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

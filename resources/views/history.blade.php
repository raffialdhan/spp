@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto" x-data="{
    detailOpen: false,
    detail: {},
    openDetail(data) { this.detail = data; this.detailOpen = true; }
}">
    
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Riwayat Transaksi</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Lihat semua pembayaran lalu dan status verifikasinya</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('student.history') }}" method="GET" class="relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari transaksi..." class="pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-indigo-500 transition-all w-64 shadow-sm">
            </form>
            <a href="/payment" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-colors flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> Bayar Baru
            </a>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative">
        <div class="overflow-x-auto relative">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-widest">
                        <th class="px-8 py-5 font-bold border-b border-slate-100">ID Transaksi</th>
                        <th class="px-8 py-5 font-bold border-b border-slate-100">Deskripsi</th>
                        <th class="px-8 py-5 font-bold border-b border-slate-100">Tanggal</th>
                        <th class="px-8 py-5 font-bold border-b border-slate-100">Jumlah</th>
                        <th class="px-8 py-5 font-bold border-b border-slate-100">Status</th>
                        <th class="px-8 py-5 font-bold border-b border-slate-100 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-50">
                    
                    @forelse($payments as $p)
                        <tr class="hover:bg-slate-50/80 transition group">
                            <td class="px-8 py-6 font-bold text-slate-500">#TRX-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-slate-800">{{ $p->fee->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">Metode: {{ $p->proof_img ? 'Transfer' : 'Tunai' }}</p>
                            </td>
                            <td class="px-8 py-6 text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y') }}<br>
                                <span class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($p->payment_date)->format('H:i') }} WIB</span>
                            </td>
                            <td class="px-8 py-6 font-extrabold text-slate-800 text-base">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                            <td class="px-8 py-6">
                                @if($p->status === 'pending')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200"><i class="fa-solid fa-hourglass-half"></i> Verifikasi</span>
                                @elseif($p->status === 'success')
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200"><i class="fa-solid fa-check"></i> Berhasil</span>
                                @else
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200"><i class="fa-solid fa-xmark"></i> Ditolak</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-center">
                                <button @click="openDetail({
                                    id: '#TRX-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}',
                                    desc: '{{ addslashes($p->fee->name) }}',
                                    bank: '{{ $p->proof_img ? 'Transfer Proof Uploaded' : 'Tunai' }}',
                                    date: '{{ \Carbon\Carbon::parse($p->payment_date)->format('d M Y, H:i') }} WIB',
                                    amount: 'Rp {{ number_format($p->amount, 0, ',', '.') }}',
                                    status: '{{ ucfirst($p->status) }}',
                                    statusColor: '{{ $p->status === 'pending' ? 'amber' : ($p->status === 'success' ? 'emerald' : 'rose') }}',
                                    note: '{{ addslashes($p->note) }}'
                                })" class="w-10 h-10 rounded-full bg-slate-50 text-slate-600 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <i class="fa-solid fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-10 text-center text-slate-400 font-medium">
                                Belum ada riwayat transaksi
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="p-6 border-t border-slate-100">
            {{ $payments->links() }}
        </div>
    </div>

    <!-- Detail Modal -->
    <div x-show="detailOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div @click="detailOpen = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 z-10"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            
            <button @click="detailOpen = false" class="absolute top-5 right-5 w-9 h-9 bg-slate-100 hover:bg-slate-200 rounded-full flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-slate-500"></i>
            </button>

            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5"
                 :class="{'bg-amber-100 text-amber-600': detail.statusColor==='amber', 'bg-emerald-100 text-emerald-600': detail.statusColor==='emerald', 'bg-rose-100 text-rose-600': detail.statusColor==='rose'}">
                <i class="fa-solid text-2xl"
                   :class="{'fa-hourglass-half': detail.statusColor==='amber', 'fa-check': detail.statusColor==='emerald', 'fa-xmark': detail.statusColor==='rose'}"></i>
            </div>

            <h2 class="text-xl font-extrabold text-slate-800 mb-6">Detail Transaksi</h2>
            
            <div class="space-y-0 mb-6 divide-y divide-slate-100">
                <div class="flex justify-between items-center py-3"><span class="text-sm text-slate-500 font-medium">ID</span><span class="font-bold text-slate-800" x-text="detail.id"></span></div>
                <div class="flex justify-between items-center py-3"><span class="text-sm text-slate-500 font-medium">Deskripsi</span><span class="font-bold text-slate-800" x-text="detail.desc"></span></div>
                <div class="flex justify-between items-center py-3"><span class="text-sm text-slate-500 font-medium">Metode</span><span class="font-bold text-slate-800" x-text="detail.bank"></span></div>
                <div class="flex justify-between items-center py-3"><span class="text-sm text-slate-500 font-medium">Tanggal</span><span class="font-bold text-slate-800 text-right" x-text="detail.date"></span></div>
                <div class="flex justify-between items-center py-3"><span class="text-sm text-slate-500 font-medium">Jumlah</span><span class="text-xl font-extrabold text-indigo-600" x-text="detail.amount"></span></div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm text-slate-500 font-medium">Status</span>
                    <span class="font-bold" x-text="detail.status"
                          :class="{'text-amber-600':detail.statusColor==='amber','text-emerald-600':detail.statusColor==='emerald','text-rose-600':detail.statusColor==='rose'}"></span>
                </div>
            </div>

            <div class="bg-slate-50 rounded-xl p-4 mb-6 border border-slate-100" x-show="detail.note">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan</p>
                <p class="text-sm text-slate-600 font-medium italic" x-text="detail.note"></p>
            </div>

            <div class="flex gap-3">
                <button @click="detailOpen = false" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 rounded-xl transition-colors">Tutup</button>
            </div>
        </div>
    </div>

</div>
@endsection

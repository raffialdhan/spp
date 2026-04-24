@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto" x-data="{ 
    sent: {{ session('success_sent') ? 'true' : 'false' }},
    fileName: '' 
}">
    
    <!-- Header -->
    <div class="mb-10 text-center">
        <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Pembayaran SPP</h1>
        <p class="text-slate-500 mt-2 font-medium">Unggah bukti pembayaran Anda untuk diverifikasi</p>
    </div>

    <!-- Main Form Container -->
    <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden relative">
        
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-purple-500"></div>

        <form action="{{ route('student.payment.store') }}" method="POST" enctype="multipart/form-data" class="p-8 sm:p-12">
            @csrf
            
            <div class="space-y-8">
                <!-- Section 1: Select Bill -->
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">1</span> 
                        Pilih Tagihan
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($fees as $f)
                            <label class="cursor-pointer relative">
                                <input type="radio" name="fee_id" value="{{ $f->id }}" class="peer sr-only" 
                                    {{ ($selectedFeeId == $f->id || ($loop->first && !$selectedFeeId)) ? 'checked' : '' }}>
                                <div class="p-5 rounded-2xl border-2 border-slate-100 peer-checked:border-indigo-600 peer-checked:bg-indigo-50/50 hover:bg-slate-50 transition-all">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-slate-800">{{ $f->name }}</h4>
                                        <i class="fa-solid fa-circle-check text-indigo-600 opacity-0 peer-checked:opacity-100 transition-opacity text-xl"></i>
                                    </div>
                                    <p class="text-2xl font-extrabold text-indigo-600">Rp {{ number_format($f->amount, 0, ',', '.') }}</p>
                                </div>
                            </label>
                        @empty
                            <div class="col-span-2 py-4 text-center text-slate-400 font-medium">
                                Tidak ada kategori tagihan tersedia
                            </div>
                        @endforelse
                    </div>
                    @error('fee_id') <p class="text-rose-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>

                <hr class="border-slate-100">

                <!-- Section 2: Payment Method Details -->
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">2</span>
                        Tujuan Transfer
                    </h3>
                    <p class="text-sm text-slate-400 font-medium mb-5 ml-10">Pilih salah satu rekening sekolah di bawah ini</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- BCA -->
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 hover:border-blue-200 hover:shadow-md transition-all group">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-10 bg-white rounded-lg shadow-sm border border-slate-200 flex items-center justify-center font-black text-blue-700 italic text-lg shrink-0">BCA</div>
                                    <div>
                                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Bank BCA</p>
                                        <p class="text-base font-bold text-slate-800 tracking-wider">1234 5678 90</p>
                                    </div>
                                </div>
                                <button type="button" onclick="copyToClipboard('1234567890', this)" class="shrink-0 bg-white text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-200 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-all"><i class="fa-regular fa-copy mr-1"></i>Salin</button>
                            </div>
                        </div>

                        <!-- Mandiri -->
                        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 hover:border-yellow-200 hover:shadow-md transition-all group">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-10 bg-yellow-400 rounded-lg shadow-sm flex items-center justify-center font-black text-white text-xs shrink-0">MANDIRI</div>
                                    <div>
                                        <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wider">Bank Mandiri</p>
                                        <p class="text-base font-bold text-slate-800 tracking-wider">1380 0087 6543</p>
                                    </div>
                                </div>
                                <button type="button" onclick="copyToClipboard('138000876543', this)" class="shrink-0 bg-white text-yellow-600 hover:bg-yellow-500 hover:text-white border border-yellow-200 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm transition-all"><i class="fa-regular fa-copy mr-1"></i>Salin</button>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                <!-- Section 3: Upload Proof -->
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">3</span> 
                        Unggah Bukti Transfer
                    </h3>
                    
                    <div class="w-full relative group cursor-pointer">
                        <input type="file" name="proof_img" required @change="fileName = $event.target.files[0].name" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer" accept="image/*">
                        <div class="border-2 border-dashed border-indigo-200 rounded-2xl p-10 text-center bg-indigo-50/30 group-hover:bg-indigo-50 transition-colors">
                            <div class="w-20 h-20 mx-auto bg-white rounded-full flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl text-indigo-500"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 mb-1" x-text="fileName || 'Klik untuk mengunggah atau seret file'"></h4>
                            <p class="text-sm text-slate-500 font-medium">PNG, JPG atau JPEG (maks. 5MB)</p>
                        </div>
                    </div>
                    @error('proof_img') <p class="text-rose-500 text-xs font-bold mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Catatan Tambahan (Opsional)</label>
                    <textarea name="note" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all placeholder:text-slate-400 font-medium" placeholder="Cth: Titip bayar lewat teller oleh ayah..."></textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4" x-show="!sent">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:opacity-90 text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 shadow-lg shadow-purple-500/30 transform hover:-translate-y-1 relative overflow-hidden group text-lg">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Pembayaran untuk Verifikasi
                        </span>
                    </button>
                </div>

                <!-- Success Message -->
                <div x-show="sent" x-cloak
                     x-transition:enter="transition ease-out duration-400"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="pt-4 bg-emerald-50 border border-emerald-200 rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg shadow-emerald-500/30">
                        <i class="fa-solid fa-check text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-emerald-800 mb-2">Pembayaran Terkirim!</h3>
                    <p class="text-emerald-600 font-medium mb-6">Bukti transfer Anda sedang menunggu verifikasi dari petugas. Kami akan memberikan konfirmasi segera.</p>
                    <div class="flex gap-3 justify-center">
                        <a href="/history" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-6 py-3 rounded-xl transition-colors flex items-center gap-2 shadow-md">
                            <i class="fa-solid fa-clock-rotate-left"></i> Lihat Riwayat
                        </a>
                        <a href="/dashboard" class="bg-white border border-emerald-200 text-emerald-700 font-bold px-6 py-3 rounded-xl transition-colors hover:bg-emerald-50">Beranda</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function copyToClipboard(number, btn) {
        navigator.clipboard.writeText(number).then(() => {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-check mr-1"></i>Tersalin!';
            btn.classList.add('bg-emerald-500', 'text-white', 'border-emerald-500');
            setTimeout(() => {
                btn.innerHTML = original;
                btn.classList.remove('bg-emerald-500', 'text-white', 'border-emerald-500');
            }, 2000);
        });
    }
</script>
@endsection

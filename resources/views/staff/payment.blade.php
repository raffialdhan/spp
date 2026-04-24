@extends('layouts.staff')

@section('content')
<div class="max-w-7xl mx-auto" x-data="{
    /* --- State --- */
    searchQuery: '',
    selectedStudent: null,
    processed: false,

    bills: [
        { id: 1, name: 'SPP Mei 2026', due: '10 Mei 2026', amount: 500000, late: false, checked: true },
        { id: 2, name: 'Dana Bangunan Tahap 1', due: 'Terlambat (20 Apr)', amount: 1000000, late: true, checked: false },
        { id: 3, name: 'SPP April 2026', due: '10 Apr 2026', amount: 500000, late: true, checked: false },
    ],

    students: [
        { id: 1, name: 'Ahmad Fauzi',   nisn: '9988776655', kelas: 'XII - MIPA 1', initial: 'A', color: 'indigo' },
        { id: 2, name: 'Budi Santoso',  nisn: '1122334455', kelas: 'X - IPS 2',    initial: 'B', color: 'rose' },
        { id: 3, name: 'Citra Lestari', nisn: '5566778899', kelas: 'XI - IPA 3',   initial: 'C', color: 'emerald' },
        { id: 4, name: 'Dewi Puspita',  nisn: '3344556677', kelas: 'X - MIPA 2',   initial: 'D', color: 'amber' },
    ],

    cashInput: '',

    get filteredStudents() {
        if (!this.searchQuery) return this.students;
        return this.students.filter(s =>
            s.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            s.nisn.includes(this.searchQuery)
        );
    },

    get checkedBills() {
        return this.bills.filter(b => b.checked);
    },

    get total() {
        return this.checkedBills.reduce((sum, b) => sum + b.amount, 0);
    },

    get cashValue() {
        return parseInt(this.cashInput.replace(/\./g, '')) || 0;
    },

    get kembalian() {
        return Math.max(0, this.cashValue - this.total);
    },

    get kurang() {
        return this.cashValue < this.total && this.cashValue > 0 ? this.total - this.cashValue : 0;
    },

    formatRp(val) {
        return 'Rp ' + val.toLocaleString('id-ID');
    },

    selectStudent(s) {
        this.selectedStudent = s;
        this.searchQuery = '';
        this.processed = false;
        /* Reset cash when switching student */
        this.cashInput = this.formatRp(this.total).replace('Rp ', '');
    },

    proses() {
        if (!this.selectedStudent || this.checkedBills.length === 0) return;
        this.processed = true;
    }
}">

    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#362773] tracking-tight">Pembayaran Langsung</h1>
            <p class="text-slate-500 mt-1.5 font-medium">Proses pembayaran tunai langsung dari siswa atau wali murid di tempat</p>
        </div>
        <a href="/staff/verification" class="flex items-center gap-2 bg-amber-50 border border-amber-200 hover:bg-amber-100 text-amber-700 px-5 py-3 rounded-xl font-bold text-sm transition-colors">
            <i class="fa-solid fa-clock-rotate-left"></i> Lihat Antrian Verifikasi
        </a>
    </div>

    <!-- SUCCESS RECEIPT -->
    <div x-show="processed" x-cloak
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="bg-white rounded-[2rem] border-2 border-emerald-200 shadow-[0_8px_30px_rgba(16,185,129,0.1)] p-10 mb-8 text-center">
        <div class="w-20 h-20 bg-emerald-500 text-white rounded-full flex items-center justify-center mx-auto mb-5 shadow-xl shadow-emerald-500/30">
            <i class="fa-solid fa-check text-3xl"></i>
        </div>
        <h2 class="text-2xl font-extrabold text-emerald-800 mb-2">Pembayaran Berhasil Diproses!</h2>
        <p class="text-emerald-600 font-medium mb-2" x-text="selectedStudent ? selectedStudent.name + ' — ' + selectedStudent.kelas : ''"></p>
        <p class="text-slate-500 mb-8">Total dibayar: <span class="font-extrabold text-slate-800 text-lg" x-text="formatRp(total)"></span>
            &nbsp;|&nbsp; Kembalian: <span class="font-extrabold text-emerald-600" x-text="formatRp(kembalian)"></span>
        </p>
        <div class="flex gap-4 justify-center">
            <button class="flex items-center gap-2 bg-slate-900 hover:bg-indigo-700 text-white font-bold px-8 py-3.5 rounded-xl transition-colors shadow-lg">
                <i class="fa-solid fa-print"></i> Cetak Struk
            </button>
            <button @click="processed = false; selectedStudent = null; searchQuery = ''; bills.forEach(b => { b.checked = false }); bills[0].checked = true; cashInput = ''"
                    class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold px-8 py-3.5 rounded-xl transition-colors">
                <i class="fa-solid fa-plus"></i> Transaksi Baru
            </button>
        </div>
    </div>

    <!-- MAIN FORM -->
    <div x-show="!processed" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Step 1: Cari Siswa -->
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-10">
                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm"
                          :class="selectedStudent ? 'bg-emerald-100 text-emerald-600' : 'bg-indigo-50 text-indigo-600'">
                        <template x-if="selectedStudent"><i class="fa-solid fa-check text-xs"></i></template>
                        <template x-if="!selectedStudent"><span>1</span></template>
                    </span>
                    Cari &amp; Pilih Siswa
                </h3>

                <!-- Selected Student Badge -->
                <div x-show="selectedStudent" x-cloak class="mb-4 p-4 bg-indigo-50 border border-indigo-200 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-lg" x-text="selectedStudent ? selectedStudent.initial : ''"></div>
                        <div>
                            <p class="font-bold text-slate-800" x-text="selectedStudent ? selectedStudent.name : ''"></p>
                            <p class="text-xs text-slate-500" x-text="selectedStudent ? 'NISN: ' + selectedStudent.nisn + ' • ' + selectedStudent.kelas : ''"></p>
                        </div>
                    </div>
                    <button @click="selectedStudent = null; searchQuery = ''" class="text-slate-400 hover:text-rose-500 transition-colors text-sm font-bold">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Search Input -->
                <div class="relative mb-4">
                    <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-indigo-400 text-lg"></i>
                    <input type="text" x-model="searchQuery"
                           :placeholder="selectedStudent ? 'Ganti siswa...' : 'Masukkan NISN atau Nama Siswa...'"
                           class="w-full pl-14 pr-5 py-4 bg-slate-50 border border-slate-200 text-slate-800 rounded-2xl outline-none focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition-all font-medium text-lg">
                </div>

                <!-- Search Results -->
                <div x-show="searchQuery.length > 0" x-cloak class="border border-slate-100 rounded-2xl overflow-hidden divide-y divide-slate-50">
                    <template x-for="s in filteredStudents" :key="s.id">
                        <div @click="selectStudent(s)"
                             class="p-4 flex items-center justify-between cursor-pointer hover:bg-indigo-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold" x-text="s.initial"></div>
                                <div>
                                    <h4 class="font-bold text-slate-800" x-text="s.name"></h4>
                                    <p class="text-xs text-slate-500" x-text="'NISN: ' + s.nisn + ' • ' + s.kelas"></p>
                                </div>
                            </div>
                            <i class="fa-solid fa-arrow-right text-indigo-400"></i>
                        </div>
                    </template>
                    <div x-show="filteredStudents.length === 0" class="p-6 text-center text-slate-400 font-medium">
                        <i class="fa-solid fa-search mb-2 block text-2xl"></i>
                        Siswa tidak ditemukan
                    </div>
                </div>

                <!-- Empty prompt -->
                <div x-show="!selectedStudent && searchQuery.length === 0" class="text-center py-4 text-slate-400 text-sm font-medium">
                    <i class="fa-solid fa-user-graduate text-3xl mb-2 block text-slate-300"></i>
                    Ketik nama atau NISN untuk mencari siswa
                </div>
            </div>

            <!-- Step 2: Pilih Tagihan -->
            <div class="bg-white rounded-[2rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 p-8 sm:p-10"
                 :class="!selectedStudent ? 'opacity-50 pointer-events-none' : ''">
                <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm"
                          :class="checkedBills.length > 0 ? 'bg-emerald-100 text-emerald-600' : 'bg-indigo-50 text-indigo-600'">
                        <template x-if="checkedBills.length > 0"><i class="fa-solid fa-check text-xs"></i></template>
                        <template x-if="checkedBills.length === 0"><span>2</span></template>
                    </span>
                    Pilih Tagihan yang Dibayar
                </h3>
                
                <div class="space-y-3">
                    <template x-for="bill in bills" :key="bill.id">
                        <label class="flex items-center justify-between p-5 rounded-2xl border-2 cursor-pointer transition-all"
                               :class="bill.checked ? 'border-indigo-600 bg-indigo-50/30' : 'border-slate-200 hover:bg-slate-50'">
                            <div class="flex items-center gap-4">
                                <input type="checkbox" x-model="bill.checked" class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                                <div>
                                    <h4 class="font-bold text-slate-800 text-lg" x-text="bill.name"></h4>
                                    <p class="text-sm font-medium" :class="bill.late ? 'text-rose-500' : 'text-slate-500'" x-text="'Jatuh tempo: ' + bill.due"></p>
                                </div>
                            </div>
                            <p class="text-xl font-extrabold text-slate-800" x-text="formatRp(bill.amount)"></p>
                        </label>
                    </template>
                </div>
            </div>

        </div>

        <!-- Right Column: Summary -->
        <div class="lg:col-span-1">
            <div class="bg-slate-900 rounded-[2.5rem] shadow-2xl p-8 sticky top-32 text-white">
                <h3 class="text-xl font-bold mb-6 flex items-center gap-2 text-indigo-300">
                    <i class="fa-solid fa-receipt"></i> Ringkasan Pembayaran
                </h3>
                
                <!-- Student Info -->
                <div class="mb-6">
                    <p class="text-sm text-slate-400 font-medium mb-1">Siswa</p>
                    <template x-if="selectedStudent">
                        <div>
                            <p class="text-lg font-bold" x-text="selectedStudent.name"></p>
                            <p class="text-sm text-slate-400" x-text="selectedStudent.kelas"></p>
                        </div>
                    </template>
                    <template x-if="!selectedStudent">
                        <p class="text-slate-500 italic text-sm">Belum dipilih</p>
                    </template>
                </div>

                <!-- Bill Items -->
                <div class="space-y-3 mb-6 min-h-[60px]">
                    <template x-for="b in checkedBills" :key="b.id">
                        <div class="flex justify-between items-center pb-3 border-b border-slate-700/50">
                            <p class="text-slate-300 font-medium text-sm" x-text="b.name"></p>
                            <p class="font-bold text-sm" x-text="formatRp(b.amount)"></p>
                        </div>
                    </template>
                    <div x-show="checkedBills.length === 0" class="text-slate-500 text-sm italic text-center py-2">
                        Pilih tagihan di kiri
                    </div>
                </div>

                <!-- Total -->
                <div class="mb-6 p-4 bg-slate-800/60 rounded-2xl">
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider mb-1">Total Tagihan</p>
                    <p class="text-4xl font-extrabold text-white" x-text="formatRp(total)"></p>
                </div>

                <!-- Cash Input -->
                <div class="mb-6">
                    <label class="block text-sm text-slate-400 font-medium mb-2">Uang Diterima (Tunai)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">Rp</span>
                        <input type="text" x-model="cashInput"
                               @input="cashInput = cashInput.replace(/[^0-9]/g,'')"
                               placeholder="0"
                               class="w-full bg-slate-800 border border-slate-700 text-white rounded-xl pl-12 pr-4 py-3 outline-none focus:border-indigo-500 transition-colors font-bold text-xl">
                    </div>
                    <!-- Quick amount buttons -->
                    <div class="grid grid-cols-3 gap-2 mt-3">
                        <template x-for="amt in [500000, 1000000, 2000000]">
                            <button type="button" @click="cashInput = String(amt)"
                                    class="bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs font-bold py-2 rounded-lg transition-colors"
                                    x-text="formatRp(amt)"></button>
                        </template>
                    </div>
                    <!-- Kembalian / Kurang -->
                    <div class="flex justify-between items-center mt-3 text-sm">
                        <span class="text-slate-400">Kembalian:</span>
                        <span class="font-extrabold"
                              :class="cashValue >= total ? 'text-emerald-400' : 'text-rose-400'"
                              x-text="cashValue >= total ? formatRp(kembalian) : '- ' + formatRp(kurang)"></span>
                    </div>
                </div>

                <!-- Process Button -->
                <button @click="proses()"
                        :disabled="!selectedStudent || checkedBills.length === 0 || (cashInput !== '' && cashValue < total)"
                        class="w-full bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-purple-500/30 flex items-center justify-center gap-2 text-lg transform hover:-translate-y-1">
                    <i class="fa-solid fa-print"></i> Proses &amp; Cetak Struk
                </button>

                <p x-show="!selectedStudent" class="text-xs text-slate-500 text-center mt-3">
                    Pilih siswa terlebih dahulu
                </p>
            </div>
        </div>

    </div>
</div>
@endsection

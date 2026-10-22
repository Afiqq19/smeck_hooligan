@extends('layouts.app')

@section('title', 'Cetak & Aktivasi NFC')
@section('header_title', 'Cetak & Aktivasi Kartu NFC Fisik')
@section('header_subtitle', 'Tautkan nomor UID chip fisik baru ke akun anggota untuk hak akses stadion & merchant partner')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Form Tautkan UID NFC ke Anggota --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <h3 class="font-display font-bold text-lg text-white mb-2">Form Aktivasi Chip KTA NFC Fisik</h3>
        <p class="text-xs text-white/50 mb-6">Pilih anggota yang telah diverifikasi dan scan/masukkan nomor UID kartu fisik yang diterbitkan.</p>

        <form action="{{ route('superadmin.nfc.link') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Pilih Anggota <span class="text-red-400">*</span></label>
                    <select name="user_id" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        <option value="" disabled selected>-- Pilih Anggota Tanpa Kartu --</option>
                        @forelse($membersWithoutCard as $mwc)
                            <option value="{{ $mwc->id }}" class="bg-smeck-dark-900">{{ $mwc->name }} ({{ $mwc->profile?->nia ?? 'Tanpa NIA' }}) - {{ $mwc->chapter?->name }}</option>
                        @empty
                            <option value="" disabled class="bg-smeck-dark-900">Semua anggota sudah memiliki kartu NFC</option>
                        @endforelse
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nomor UID Chip NFC <span class="text-red-400">*</span></label>
                    <input type="text" name="uid_number" required placeholder="Contoh: 04:A2:8B:1A:F3:60:80" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono uppercase text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nomor Kartu KTA (Opsional)</label>
                    <input type="text" name="card_number" placeholder="Otomatis: 6032-XXXX-XXXX-XXXX" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-red-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Vendor / Jenis Kartu <span class="text-red-400">*</span></label>
                    <select name="card_vendor" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                        <option value="emoney" class="bg-smeck-dark-900">Mandiri E-Money Custom SMeCK</option>
                        <option value="flazz" class="bg-smeck-dark-900">BCA Flazz Gen 2 SMeCK</option>
                        <option value="brizzi" class="bg-smeck-dark-900">BRIZZI BRI</option>
                        <option value="tapcash" class="bg-smeck-dark-900">BNI TapCash</option>
                        <option value="custom" class="bg-smeck-dark-900">RFID PVC Chip 13.56 MHz</option>
                    </select>
                </div>
            </div>

            <div class="pt-2 flex flex-wrap items-center justify-between gap-3">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-link"></i>
                    <span>Tautkan & Terbitkan Kartu NFC</span>
                </button>
                <button type="button" onclick="openPrintModal()" class="px-5 py-2.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 flex items-center gap-2 transition-all">
                    <i class="fa-solid fa-print"></i>
                    <span>Preview Cetak KTA Massal (PVC Layout)</span>
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Kartu Aktif --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-display font-bold text-lg text-white">Kartu Fisik NFC yang Sedang Beredar</h3>
                <p class="text-xs text-white/40">Daftar kartu KTA fisik beredar yang aktif di sistem tap gate dan merchant partner</p>
            </div>
            <span class="text-xs text-white/50 font-mono">{{ $activeCards->total() }} Kartu Terdaftar</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase">
                        <th class="py-3 px-4">Nomor Kartu KTA</th>
                        <th class="py-3 px-4">Nama Pemegang</th>
                        <th class="py-3 px-4">NIA Anggota</th>
                        <th class="py-3 px-4">Chapter</th>
                        <th class="py-3 px-4">Vendor</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($activeCards as $card)
                        <tr class="hover:bg-white/[0.02]">
                            <td class="py-3.5 px-4">
                                <div class="font-mono font-bold text-smeck-gold text-xs">
                                    {{ $card->card_number ?? '6032-9800-2024-1950' }}
                                </div>
                                <div class="font-mono text-[10px] text-white/40 mt-0.5">
                                    UID: {{ $card->uid_number }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-white">{{ $card->user->name }}</td>
                            <td class="py-3.5 px-4 font-mono text-smeck-green-400">{{ $card->user->profile?->nia ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-white/60">{{ $card->user->chapter?->name ?? 'Pusat' }}</td>
                            <td class="py-3.5 px-4 font-semibold uppercase text-white/80">{{ $card->card_vendor }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $card->status === 'active' ? 'bg-smeck-green-500/20 text-smeck-green-400' : 'bg-red-500/20 text-red-400' }}">
                                    {{ $card->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('superadmin.nfc.toggle', $card->id) }}" method="POST" onsubmit="return confirm('Ubah status kartu UID {{ $card->uid_number }}?')">
                                        @csrf
                                        <button type="submit" class="px-2.5 py-1 rounded-lg {{ $card->status === 'active' ? 'bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white' : 'bg-smeck-green-500/10 hover:bg-smeck-green-500 text-smeck-green-400 hover:text-white' }} font-semibold text-[11px] transition-colors">
                                            {{ $card->status === 'active' ? 'Blokir' : 'Buka Blokir' }}
                                        </button>
                                    </form>
                                    <button onclick="openReplaceModal({{ $card->user_id }}, '{{ addslashes($card->user->name) }}')" class="px-2.5 py-1 rounded-lg bg-amber-500/10 hover:bg-amber-600 text-amber-400 hover:text-white font-semibold text-[11px] transition-colors">
                                        Ganti Kartu
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-6 text-center text-white/40">Belum ada kartu fisik yang ditautkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Ganti Kartu Super Admin --}}
<div id="modalReplaceCard" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-md hidden flex items-center justify-center p-4">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative">
        <button onclick="closeReplaceModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-5">
            <h3 class="font-display font-black text-lg text-white">Ganti Kartu KTA NFC Baru</h3>
            <p class="text-xs text-white/50 mt-1">Pemegang: <strong id="replaceMemberName" class="text-red-400"></strong></p>
        </div>

        <form id="formReplaceCard" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nomor UID Chip Baru <span class="text-red-400">*</span></label>
                <input type="text" name="uid_number" required placeholder="Contoh: 04:A2:8B:1A:F3:60:80" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono uppercase text-sm focus:border-red-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Nomor Kartu KTA Baru (Opsional)</label>
                <input type="text" name="card_number" placeholder="Otomatis: 6032-XXXX-XXXX-XXXX" class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-mono text-sm focus:border-red-400 focus:outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-white/60 mb-1.5">Vendor Kartu <span class="text-red-400">*</span></label>
                <select name="card_vendor" required class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white text-sm focus:border-red-400 focus:outline-none">
                    <option value="emoney" class="bg-smeck-dark-900">Mandiri E-Money Custom SMeCK</option>
                    <option value="flazz" class="bg-smeck-dark-900">BCA Flazz Gen 2 SMeCK</option>
                    <option value="brizzi" class="bg-smeck-dark-900">BRIZZI BRI</option>
                    <option value="tapcash" class="bg-smeck-dark-900">BNI TapCash</option>
                    <option value="custom" class="bg-smeck-dark-900">RFID PVC Chip 13.56 MHz</option>
                </select>
            </div>
            <div class="pt-3 flex items-center justify-end gap-3">
                <button type="button" onclick="closeReplaceModal()" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-semibold">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold text-xs shadow-lg">Terbitkan Pengganti</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Preview Cetak KTA Massal (PVC Card Layout) --}}
<div id="modalPrintPreview" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-4xl w-full p-6 sm:p-8 shadow-2xl relative my-8">
        <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-6">
            <div>
                <h3 class="font-display font-black text-xl text-white flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-smeck-green-400"></i>
                    <span>Layout Cetak Kartu KTA PVC Massal</span>
                </h3>
                <p class="text-xs text-white/50 mt-1">Format standar CR80 (85.60 × 53.98 mm) dengan watermark Kinantan 1950, EMV Chip emas, dan nomor kartu KTA</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="window.print()" class="px-4 py-2 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg flex items-center gap-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Cetak Lembar PVC</span>
                </button>
                <button onclick="closePrintModal()" class="p-2 text-white/40 hover:text-white rounded-lg">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        {{-- Grid PVC Cards Preview --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[60vh] overflow-y-auto p-2">
            @foreach($activeCards->take(6) as $c)
                {{-- KTA PVC Card Visual --}}
                <div class="relative w-full aspect-[85.6/53.98] rounded-2xl overflow-hidden shadow-2xl border border-smeck-gold/40 p-5 flex flex-col justify-between bg-gradient-to-br from-smeck-dark-950 via-smeck-green-950 to-smeck-dark-900">
                    <div class="absolute -right-6 -bottom-6 w-36 h-36 rounded-full overflow-hidden opacity-15 pointer-events-none">
                        <img src="{{ asset('images/logo-crest.jpg') }}" alt="Watermark" class="w-full h-full object-cover">
                    </div>

                    {{-- Header KTA --}}
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl overflow-hidden border border-smeck-gold/60 p-0.5 bg-black">
                                <img src="{{ asset('images/logo-crest.jpg') }}" alt="Logo" class="w-full h-full object-cover rounded-lg">
                            </div>
                            <div>
                                <div class="font-display font-black text-white text-xs tracking-wider uppercase">SMeCK HOOLIGAN</div>
                                <div class="text-[8px] text-smeck-gold font-bold tracking-widest uppercase">KARTU TANDA ANGGOTA</div>
                            </div>
                        </div>
                        <i class="fa-solid fa-wifi text-white/50 text-base rotate-90"></i>
                    </div>

                    {{-- Chip EMV & Chapter --}}
                    <div class="relative z-10 flex items-center justify-between my-auto py-1">
                        <div class="w-10 h-7 rounded-md bg-gradient-to-br from-amber-200 via-amber-400 to-amber-600 border border-amber-300 shadow-inner p-0.5 flex flex-col justify-between">
                            <div class="h-[1px] bg-amber-800/40 w-full mt-0.5"></div>
                            <div class="h-[1px] bg-amber-800/40 w-2/3 mx-auto"></div>
                            <div class="h-[1px] bg-amber-800/40 w-full mb-0.5"></div>
                        </div>
                        <div class="text-right">
                            <div class="text-[8px] text-white/40 uppercase font-bold">BASIS WILAYAH</div>
                            <div class="text-[11px] font-bold text-smeck-green-300">{{ $c->user->chapter?->name ?? 'Chapter Pusat' }}</div>
                        </div>
                    </div>

                    {{-- Member Info & Card Number --}}
                    <div class="relative z-10 flex items-end justify-between pt-2 border-t border-white/10">
                        <div>
                            <div class="font-display font-black text-xs text-white uppercase">{{ $c->user->name }}</div>
                            <div class="font-mono text-[10px] text-smeck-gold font-bold">{{ $c->user->profile?->nia ?? 'SMCK-2024-1950' }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-mono text-[8px] text-white/40">NO. KARTU:</div>
                            <div class="font-mono text-[11px] text-smeck-gold font-bold tracking-wider">{{ $c->card_number ?? '6032-9800-2024-1950' }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function openReplaceModal(userId, memberName) {
        document.getElementById('replaceMemberName').innerText = memberName;
        document.getElementById('formReplaceCard').action = `/superadmin/nfc/replace/${userId}`;
        document.getElementById('modalReplaceCard').classList.remove('hidden');
    }

    function closeReplaceModal() {
        document.getElementById('modalReplaceCard').classList.add('hidden');
    }

    function openPrintModal() {
        document.getElementById('modalPrintPreview').classList.remove('hidden');
    }

    function closePrintModal() {
        document.getElementById('modalPrintPreview').classList.add('hidden');
    }
</script>
@endsection


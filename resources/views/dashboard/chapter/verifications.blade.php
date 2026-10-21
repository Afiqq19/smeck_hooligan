@extends('layouts.app')

@section('title', 'Verifikasi Pendaftar Baru')
@section('header_title', 'Verifikasi Pendaftaran Anggota Baru')
@section('header_subtitle', 'Validasi data e-KTP, kesesuaian basis wilayah, dan persetujuan penerbitan NIA suporter')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    
    {{-- Header & Penjelasan --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-display font-black text-xl sm:text-2xl text-white">Antrean Verifikasi Pendaftar Baru</h2>
            <p class="text-xs text-white/50">Basis Wilayah: <span class="text-smeck-green-400 font-semibold">{{ auth()->user()->chapter->name ?? 'Chapter' }}</span></p>
        </div>
        <div>
            <span class="px-4 py-2 rounded-xl bg-amber-500/10 text-amber-300 border border-amber-500/20 text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span>{{ $pendingUsers->count() }} Pendaftar Menunggu Validasi</span>
            </span>
        </div>
    </div>

    {{-- Alert Panduan Validasi --}}
    <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 flex items-start sm:items-center gap-3">
        <div class="w-8 h-8 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm shrink-0">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div class="text-xs text-white/80">
            <strong>Prosedur Verifikasi:</strong> Pastikan NIK KTP valid 16 digit dan berdomisili atau aktif di wilayah basis. Klik <strong>Review Data</strong> untuk memeriksa data medis & kontak darurat sebelum menyetujui. Setelah di-approve, NIA resmi akan diterbitkan otomatis.
        </div>
    </div>

    {{-- Tabel Pendaftar --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase tracking-wider text-[11px]">
                        <th class="py-3.5 px-4">Calon Anggota</th>
                        <th class="py-3.5 px-4">NIK KTP</th>
                        <th class="py-3.5 px-4">Kontak / WhatsApp</th>
                        <th class="py-3.5 px-4 text-center">Medis & Kaos</th>
                        <th class="py-3.5 px-4">Tanggal Daftar</th>
                        <th class="py-3.5 px-4 text-right">Aksi Verifikasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($pendingUsers as $u)
                        @php
                            $applicantJson = json_encode([
                                'id' => $u->id,
                                'name' => $u->name,
                                'email' => $u->email,
                                'nik' => $u->profile?->nik_ktp ?? '-',
                                'phone' => $u->profile?->phone_number ?? '-',
                                'blood_type' => $u->profile?->blood_type ?? '-',
                                'shirt_size' => $u->profile?->shirt_size ?? '-',
                                'gender' => $u->profile?->gender == 'L' ? 'Laki-laki' : ($u->profile?->gender == 'P' ? 'Perempuan' : '-'),
                                'birth' => ($u->profile?->place_of_birth ?? '-') . ', ' . ($u->profile?->date_of_birth ? $u->profile->date_of_birth->format('d M Y') : '-'),
                                'address' => $u->profile?->address ?? '-',
                                'emergency_name' => $u->profile?->emergency_contact_name ?? '-',
                                'emergency_phone' => $u->profile?->emergency_contact_number ?? '-',
                                'emergency_rel' => $u->profile?->emergency_contact_relation ?? '-',
                                'ktp_file' => $u->profile?->ktp_file ? asset('storage/' . $u->profile->ktp_file) : null,
                                'created_at' => $u->created_at ? $u->created_at->format('d M Y, H:i') : '-'
                            ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                        @endphp
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-white text-sm truncate">{{ $u->name }}</div>
                                        <div class="text-[11px] text-white/40 truncate">{{ $u->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-4 font-mono text-white/80">
                                {{ $u->profile?->nik_ktp ?? '-' }}
                            </td>

                            <td class="py-4 px-4">
                                <div class="font-mono text-white/90">{{ $u->profile?->phone_number ?? '-' }}</div>
                            </td>

                            <td class="py-4 px-4 text-center">
                                <span class="px-2 py-0.5 rounded bg-amber-400/10 text-amber-300 font-bold text-[10px]">
                                    Gol: {{ $u->profile?->blood_type ?? '-' }}
                                </span>
                                <span class="px-2 py-0.5 rounded bg-white/10 text-white/80 font-bold text-[10px] ml-1">
                                    Size: {{ $u->profile?->shirt_size ?? '-' }}
                                </span>
                            </td>

                            <td class="py-4 px-4 text-white/50 text-[11px] font-mono">
                                {{ $u->created_at ? $u->created_at->format('d/m/Y') : '-' }}
                            </td>

                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button type="button" onclick='openReviewModal({!! $applicantJson !!})' class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-semibold text-[11px] transition-colors flex items-center gap-1.5">
                                        <i class="fa-regular fa-eye text-amber-400"></i>
                                        <span>Review Data</span>
                                    </button>

                                    <form action="{{ route('chapter.verifications.approve', $u->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Setujui pendaftaran suporter {{ $u->name }}? Sistem akan otomatis menerbitkan NIA resmi.')">
                                        @csrf
                                        <button type="submit" class="px-3.5 py-1.5 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-[11px] shadow transition-colors flex items-center gap-1">
                                            <i class="fa-solid fa-check"></i>
                                            <span>Approve</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-white/40">
                                <div class="w-16 h-16 rounded-full bg-smeck-green-500/10 text-smeck-green-400 flex items-center justify-center text-2xl mx-auto mb-3">
                                    <i class="fa-solid fa-circle-check"></i>
                                </div>
                                <div class="font-bold text-white text-sm">Semua Pendaftaran Tuntas Diverifikasi!</div>
                                <p class="text-xs text-white/40 mt-1">Tidak ada calon anggota baru yang menunggu persetujuan saat ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ========================================================================= --}}
{{-- MODAL REVIEW LENGKAP CALON ANGGOTA --}}
{{-- ========================================================================= --}}
<div id="modalReviewApplicant" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-smeck-dark-900 border border-white/10 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl relative my-8">
        <button onclick="closeReviewModal()" class="absolute top-6 right-6 text-white/40 hover:text-white text-lg">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <div class="mb-5 flex items-center gap-3">
            <div id="reviewAvatar" class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl font-bold">
                A
            </div>
            <div>
                <h3 id="reviewName" class="font-display font-black text-xl text-white">Nama Calon Suporter</h3>
                <p id="reviewEmail" class="text-xs text-white/50">email@contoh.com</p>
            </div>
        </div>

        <div class="space-y-4 text-xs">
            {{-- Data Pribadi & Medis --}}
            <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 space-y-2">
                <div class="text-[11px] uppercase tracking-wider font-bold text-smeck-gold flex items-center gap-1.5">
                    <i class="fa-solid fa-id-card"></i>
                    <span>Identitas KTP & Kontak</span>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div>
                        <span class="text-white/40 block">NIK KTP:</span>
                        <span id="reviewNik" class="font-mono text-white font-bold">1271xxxxxxxxxxxx</span>
                    </div>
                    <div>
                        <span class="text-white/40 block">Nomor WhatsApp:</span>
                        <span id="reviewPhone" class="font-mono text-amber-400 font-bold">08xxxxxxxxxx</span>
                    </div>
                    <div>
                        <span class="text-white/40 block">Golongan Darah:</span>
                        <span id="reviewBloodType" class="text-white font-bold">O</span>
                    </div>
                    <div>
                        <span class="text-white/40 block">Ukuran Kaos Jersey:</span>
                        <span id="reviewShirtSize" class="text-white font-bold">XL</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-white/40 block">Tempat, Tanggal Lahir:</span>
                        <span id="reviewBirth" class="text-white/90">Medan, 01 Jan 2000</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-white/40 block">Alamat Tempat Tinggal:</span>
                        <span id="reviewAddress" class="text-white/90 leading-relaxed">-</span>
                    </div>
                </div>
            </div>

            {{-- Kontak Darurat --}}
            <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 space-y-2">
                <div class="text-[11px] uppercase tracking-wider font-bold text-red-400 flex items-center gap-1.5">
                    <i class="fa-solid fa-phone-volume"></i>
                    <span>Kontak Darurat</span>
                </div>
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div>
                        <span class="text-white/40 block">Nama Kontak:</span>
                        <span id="reviewEmergencyName" class="text-white font-semibold">-</span>
                    </div>
                    <div>
                        <span class="text-white/40 block">Hubungan:</span>
                        <span id="reviewEmergencyRel" class="text-white/80">-</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-white/40 block">Nomor HP Darurat:</span>
                        <span id="reviewEmergencyPhone" class="font-mono text-amber-400 font-bold">-</span>
                    </div>
                </div>
            </div>

            {{-- Foto KTP Preview jika ada --}}
            <div id="ktpContainer" class="p-4 rounded-2xl bg-white/[0.03] border border-white/5 space-y-2 hidden">
                <div class="text-[11px] uppercase tracking-wider font-bold text-white/70">Foto e-KTP Pemohon</div>
                <div class="rounded-xl overflow-hidden max-h-48 bg-black flex items-center justify-center">
                    <img id="reviewKtpImage" src="" alt="KTP" class="max-h-48 w-auto object-contain">
                </div>
            </div>
        </div>

        {{-- Tindakan Verifikasi --}}
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-3 pt-4 border-t border-white/10">
            <button type="button" onclick="closeReviewModal()" class="w-full sm:w-auto px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white text-xs font-semibold">
                Tutup
            </button>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
                {{-- Form Tolak / Revisi --}}
                <form id="formRejectApplicant" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white border border-red-500/20 font-bold text-xs transition-colors flex items-center gap-1.5" onclick="return confirm('Tolak pendaftaran calon anggota ini?')">
                        <i class="fa-solid fa-xmark"></i>
                        <span>Tolak / Revisi</span>
                    </button>
                </form>

                {{-- Form Approve --}}
                <form id="formApproveApplicant" method="POST">
                    @csrf
                    <button type="submit" class="px-5 py-2 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 transition-all flex items-center gap-1.5">
                        <i class="fa-solid fa-check"></i>
                        <span>Setujui (Approve)</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openReviewModal(data) {
        document.getElementById('reviewAvatar').innerText = data.name.charAt(0).toUpperCase();
        document.getElementById('reviewName').innerText = data.name;
        document.getElementById('reviewEmail').innerText = data.email;
        document.getElementById('reviewNik').innerText = data.nik;
        document.getElementById('reviewPhone').innerText = data.phone;
        document.getElementById('reviewBloodType').innerText = data.blood_type;
        document.getElementById('reviewShirtSize').innerText = data.shirt_size;
        document.getElementById('reviewBirth').innerText = data.birth;
        document.getElementById('reviewAddress').innerText = data.address;

        document.getElementById('reviewEmergencyName').innerText = data.emergency_name;
        document.getElementById('reviewEmergencyRel').innerText = data.emergency_rel;
        document.getElementById('reviewEmergencyPhone').innerText = data.emergency_phone;

        const ktpBox = document.getElementById('ktpContainer');
        if (data.ktp_file) {
            document.getElementById('reviewKtpImage').src = data.ktp_file;
            ktpBox.classList.remove('hidden');
        } else {
            ktpBox.classList.add('hidden');
        }

        document.getElementById('formApproveApplicant').action = `/chapter/verifications/${data.id}/approve`;
        document.getElementById('formRejectApplicant').action = `/chapter/verifications/${data.id}/reject`;

        document.getElementById('modalReviewApplicant').classList.remove('hidden');
    }

    function closeReviewModal() {
        document.getElementById('modalReviewApplicant').classList.add('hidden');
    }
</script>
@endsection


@extends('layouts.app')

@section('title', 'Tiket & Awaydays')
@section('header_title', 'E-Tiket & Pendaftaran Awaydays')
@section('header_subtitle', 'Pemesanan kolektif tiket Stadion Teladan dan pendaftaran kursi tur bus awaydays suporter')

@section('content')
<div class="max-w-6xl mx-auto space-y-10">

    {{-- ============================================================== --}}
    {{-- SECTION 1: E-TIKET SAYA (BOARDING PASS MATCHDAY RESMI) --}}
    {{-- ============================================================== --}}
    @if(isset($myOrders) && $myOrders->isNotEmpty())
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-display font-black text-xl text-white flex items-center gap-2">
                        <i class="fa-solid fa-ticket-simple text-amber-400"></i>
                        <span>E-Tiket & Boarding Pass Laga Saya</span>
                    </h3>
                    <p class="text-xs text-white/50">Tunjukkan barcode / QR Code tiket di bawah ini kepada petugas scan gate pintu masuk stadion.</p>
                </div>
                <span class="px-3 py-1 rounded-full bg-smeck-green-500/20 text-smeck-green-300 font-bold text-xs border border-smeck-green-500/30">
                    {{ $myOrders->count() }} Tiket Sah
                </span>
            </div>

            <div class="grid grid-cols-1 gap-6">
                @foreach($myOrders as $order)
                    @php
                        $match = $order->matchTicket;
                        $bookingCode = 'SM-PASS-' . $order->id . '-' . $order->created_at->format('mdHi');
                    @endphp
                    {{-- MATCHDAY BOARDING PASS TICKET CARD --}}
                    <div class="rounded-3xl bg-smeck-dark-900 border border-white/10 hover:border-smeck-green-500/40 transition-all overflow-hidden shadow-2xl relative">
                        <div class="flex flex-col lg:flex-row">
                            
                            {{-- Main Ticket Body (Left / Top) --}}
                            <div class="p-6 sm:p-7 flex-1 space-y-4 bg-gradient-to-br from-emerald-950/70 via-smeck-dark-900 to-black relative">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg overflow-hidden border border-amber-400/50 bg-black p-0.5">
                                            <img src="{{ asset('images/logo-crest.jpg') }}" alt="Logo" class="w-full h-full object-cover rounded">
                                        </div>
                                        <span class="font-display font-black text-xs uppercase tracking-wider text-white">OFFICIAL MATCHDAY PASS</span>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-smeck-green-500/20 text-smeck-green-400 border border-smeck-green-500/30">
                                        <i class="fa-solid fa-circle-check mr-1"></i> LUNAS & AKTIF
                                    </span>
                                </div>

                                <div>
                                    <h4 class="font-display font-black text-xl sm:text-2xl text-white">
                                        {{ $match?->match_title ?? 'PSMS Medan vs Lawan' }}
                                    </h4>
                                    <div class="flex flex-wrap items-center gap-4 text-xs text-white/60 mt-1.5">
                                        <span><i class="fa-regular fa-calendar text-amber-400 mr-1.5"></i>{{ $match?->match_time?->format('l, d M Y - H:i') ?? '-' }} WIB</span>
                                        <span><i class="fa-solid fa-location-dot text-smeck-green-400 mr-1.5"></i>{{ $match?->stadium ?? 'Stadion Teladan' }}</span>
                                    </div>
                                </div>

                                {{-- Holder Metadata Grid --}}
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2 text-xs border-t border-white/10">
                                    <div>
                                        <div class="text-[10px] text-white/40 uppercase">Pemegang Tiket:</div>
                                        <div class="font-bold text-white truncate mt-0.5">{{ auth()->user()->name }}</div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-white/40 uppercase">NIA Resmi:</div>
                                        <div class="font-mono font-bold text-amber-400 mt-0.5">{{ auth()->user()->profile?->nia ?? 'SMCK-1950' }}</div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-white/40 uppercase">Kategori / Pintu:</div>
                                        <div class="font-bold text-smeck-green-400 mt-0.5">Tribun SMeCK (Gate 3)</div>
                                    </div>
                                    <div>
                                        <div class="text-[10px] text-white/40 uppercase">Kode Booking:</div>
                                        <div class="font-mono text-white/70 text-[11px] mt-0.5">{{ $bookingCode }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Perforated Stub Divider (Lg: vertical, Sm: horizontal) --}}
                            <div class="relative flex items-center justify-center bg-smeck-dark-900 border-t lg:border-t-0 lg:border-l border-dashed border-white/20 p-6 lg:p-7 shrink-0 lg:w-64 text-center">
                                <div class="space-y-3 w-full">
                                    {{-- Barcode / QR Simulation --}}
                                    <div class="p-2.5 rounded-2xl bg-white mx-auto inline-block shadow-lg">
                                        <svg class="w-20 h-20 mx-auto" viewBox="0 0 100 100" fill="none">
                                            <rect width="100" height="100" fill="white"/>
                                            <rect x="10" y="10" width="25" height="25" fill="#042008"/>
                                            <rect x="15" y="15" width="15" height="15" fill="white"/>
                                            <rect x="18" y="18" width="9" height="9" fill="#042008"/>
                                            <rect x="65" y="10" width="25" height="25" fill="#042008"/>
                                            <rect x="70" y="15" width="15" height="15" fill="white"/>
                                            <rect x="73" y="18" width="9" height="9" fill="#042008"/>
                                            <rect x="10" y="65" width="25" height="25" fill="#042008"/>
                                            <rect x="15" y="70" width="15" height="15" fill="white"/>
                                            <rect x="18" y="73" width="9" height="9" fill="#042008"/>
                                            <rect x="42" y="15" width="16" height="8" fill="#042008"/>
                                            <rect x="42" y="32" width="10" height="16" fill="#042008"/>
                                            <rect x="58" y="45" width="15" height="10" fill="#042008"/>
                                            <rect x="42" y="75" width="20" height="10" fill="#042008"/>
                                            <rect x="75" y="75" width="15" height="15" fill="#042008"/>
                                        </svg>
                                        <div class="text-[8px] font-mono font-bold text-slate-900 mt-1">SCAN TRIBUN GATE</div>
                                    </div>
                                    <div class="text-[10px] text-white/50 font-mono">{{ $bookingCode }}</div>
                                    <button type="button" onclick="window.print()" class="w-full py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/10 transition-colors flex items-center justify-center gap-1.5">
                                        <i class="fa-solid fa-print text-amber-400"></i>
                                        <span>Cetak Tiket (Offline)</span>
                                    </button>

                                    @php
                                        $ticketWaText = urlencode("🎫 *BOARDING PASS / TIKET RESMI SMECK HOOLIGAN*\n\n⚽ Pertandingan: " . ($order->matchTicket->match_title ?? 'Laga PSMS Medan') . "\n🏟️ Lokasi: " . ($order->matchTicket->stadium ?? 'Stadion Teladan') . "\n👤 Suporter: " . auth()->user()->name . "\n🆔 Kode Booking: " . $bookingCode . "\n🎟️ Jumlah: " . ($order->quantity ?? 1) . " Tiket\n\nTunjukkan pesan ini & KTA di pintu tribun / bus rombongan.\nSalam Sada Roha! 💚");
                                    @endphp
                                    <a href="https://wa.me/?text={{ $ticketWaText }}" target="_blank" class="w-full py-2 rounded-xl bg-emerald-600/25 hover:bg-emerald-600/40 text-emerald-300 font-bold text-xs border border-emerald-500/40 transition-colors flex items-center justify-center gap-1.5">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>Simpan di WhatsApp</span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============================================================== --}}
    {{-- SECTION 2: JADWAL & PEMESANAN TIKET PERTANDINGAN DIBUKA --}}
    {{-- ============================================================== --}}
    <div class="space-y-6">
        <div>
            <h3 class="font-display font-black text-xl sm:text-2xl text-white">Jadwal Tiket Pertandingan Dibuka</h3>
            <p class="text-xs text-white/50">Tiket terbuka untuk seluruh anggota SMeCK Hooligan tanpa batasan basis wilayah.</p>
        </div>

        <div class="space-y-6">
            @forelse($matches as $m)
                <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10 hover:border-smeck-green-500/30 transition-all shadow-xl">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        
                        {{-- Match Details --}}
                        <div class="space-y-2 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $m->type === 'away' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-smeck-green-500/20 text-smeck-green-300 border border-smeck-green-500/30' }}">
                                    {{ $m->type === 'away' ? 'TUR TANDANG (AWAYDAYS)' : 'PERTANDINGAN KANDANG' }}
                                </span>
                                <span class="text-xs text-white/50">• Kuota Tersisa: <strong class="text-smeck-green-400">{{ $m->remaining_quota }} Kursi</strong></span>
                            </div>

                            <h3 class="font-display font-black text-xl sm:text-2xl text-white">
                                {{ $m->match_title }}
                            </h3>

                            <div class="text-xs text-white/60 space-y-1">
                                <div><i class="fa-regular fa-calendar text-smeck-green-400 mr-1.5"></i> {{ $m->match_time->translatedFormat('l, d F Y - H:i') }} WIB</div>
                                <div><i class="fa-solid fa-location-dot text-smeck-green-400 mr-1.5"></i> {{ $m->stadium }}</div>
                                @if($m->bus_transport_info)
                                    <div class="text-amber-400 font-medium"><i class="fa-solid fa-bus mr-1.5"></i> Fasilitas Tur: {{ $m->bus_transport_info }}</div>
                                @endif
                            </div>
                        </div>

                        {{-- Price & Booking Button --}}
                        <div class="lg:text-right shrink-0 pt-4 lg:pt-0 border-t lg:border-t-0 border-white/10 flex flex-row lg:flex-col items-center lg:items-end justify-between gap-4">
                            <div>
                                <span class="text-[10px] text-white/40 block">Biaya Tiket Member</span>
                                <span class="font-display font-black text-2xl text-smeck-gold font-mono">
                                    Rp {{ number_format($m->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <form action="{{ route('member.tickets.order') }}" method="POST">
                                @csrf
                                <input type="hidden" name="match_ticket_id" value="{{ $m->id }}">
                                <button type="submit" onclick="return confirm('Pesan tiket {{ addslashes($m->match_title) }} seharga Rp {{ number_format($m->price, 0, ',', '.') }}?')" class="px-6 py-3 rounded-xl bg-smeck-green-600 hover:bg-smeck-green-500 text-white font-bold text-xs shadow-lg shadow-smeck-green-700/30 transition-all flex items-center gap-2">
                                    <i class="fa-solid fa-ticket"></i>
                                    <span>{{ $m->type === 'away' ? 'Daftar & Pesan Kursi Bus' : 'Pesan Tiket Masuk' }}</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-white/40 rounded-3xl bg-smeck-dark-900 border border-white/10">
                    <i class="fa-regular fa-calendar-xmark text-3xl mb-2 block text-white/20"></i>
                    Belum ada jadwal pertandingan yang dibuka saat ini.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection


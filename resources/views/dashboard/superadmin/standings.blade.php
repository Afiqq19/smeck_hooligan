@extends('layouts.app')

@section('title', 'Klasemen PT LIB Liga 2')
@section('header_title', 'Klasemen Liga 2 & Sinkronisasi PT LIB')
@section('header_subtitle', 'Pantau poin, selisih gol, dan zona promosi PSMS Medan secara otomatis via Scraper PT LIB (100% Gratis)')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-400/60 hover:text-emerald-400">&times;</button>
        </div>
    @endif

    {{-- Top Metrics Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Posisi PSMS --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-emerald-500/30 relative overflow-hidden">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl pointer-events-none"></div>
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Peringkat PSMS</span>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400">Grup Barat</span>
            </div>
            <div class="text-3xl font-display font-black text-emerald-400 flex items-baseline gap-2">
                <span>#{{ $psms?->position ?? 3 }}</span>
                <span class="text-xs font-normal text-white/60">Klasemen</span>
            </div>
            <div class="text-[11px] text-emerald-400/80 mt-1 flex items-center gap-1">
                <i class="fa-solid fa-trophy text-[10px]"></i>
                <span>{{ $psms?->zone_status === 'promotion_playoff' ? 'Zona Playoff Promosi (Top 3)' : 'Wilayah 1' }}</span>
            </div>
        </div>

        {{-- Card 2: Poin PSMS --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Total Poin PSMS</span>
                <i class="fa-solid fa-star text-smeck-gold"></i>
            </div>
            <div class="text-3xl font-display font-black text-white flex items-baseline gap-2">
                <span>{{ $psms?->points ?? 11 }}</span>
                <span class="text-xs font-normal text-white/40">PTS ({{ $psms?->played ?? 6 }} Main)</span>
            </div>
            <div class="text-[11px] text-white/50 mt-1 font-mono">
                {{ $psms?->won ?? 0 }}M · {{ $psms?->drawn ?? 0 }}S · {{ $psms?->lost ?? 0 }}K
            </div>
        </div>

        {{-- Card 3: Selisih Gol PSMS --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Produktivitas Gol</span>
                <i class="fa-solid fa-futbol text-emerald-400"></i>
            </div>
            <div class="text-3xl font-display font-black text-white flex items-baseline gap-2">
                <span class="text-emerald-400">{{ $psms?->goal_difference >= 0 ? '+' : '' }}{{ $psms?->goal_difference ?? 10 }}</span>
                <span class="text-xs font-normal text-white/40">SG</span>
            </div>
            <div class="text-[11px] text-white/50 mt-1">
                {{ $psms?->goals_for ?? 0 }} Gol Masuk · {{ $psms?->goals_against ?? 0 }} Kebobolan
            </div>
        </div>

        {{-- Card 4: Status Sinkronisasi Scraper --}}
        <div class="p-5 rounded-2xl bg-smeck-dark-900 border border-white/10">
            <div class="flex items-center justify-between text-xs text-white/50 mb-1">
                <span>Status PT LIB Scraper</span>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> 100% GRATIS
                </span>
            </div>
            <div class="text-sm font-semibold text-white mt-1">
                {{ $lastSyncedAt ? $lastSyncedAt->translatedFormat('d M Y, H:i') . ' WIB' : 'Belum Sinkron' }}
            </div>
            <div class="text-[11px] text-white/40 mt-1">
                Sistem Scraper Otomatis & Cerdas
            </div>
        </div>
    </div>

    {{-- Main Klasemen Table Card --}}
    <div class="p-6 sm:p-8 rounded-3xl bg-smeck-dark-900 border border-white/10">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                        Pegadaian Liga 2
                    </span>
                    <span class="text-xs text-white/40 font-mono">Grup Barat / Wilayah 1</span>
                </div>
                <h3 class="text-xl font-display font-bold text-white mt-2">Tabel Klasemen Resmi & Ranking Grup</h3>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center gap-3">
                <form action="{{ route('superadmin.standings.sync') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-smeck-green-500 hover:from-emerald-500 hover:to-smeck-green-400 text-white font-bold text-xs shadow-lg shadow-emerald-900/30 transition-all flex items-center gap-2 group">
                        <i class="fa-solid fa-arrows-rotate group-hover:rotate-180 transition-transform duration-500"></i>
                        <span>Sinkronkan dari PT LIB (Gratis)</span>
                    </button>
                </form>
            </div>
        </div>

        {{-- Legend Bar --}}
        <div class="py-3 flex flex-wrap items-center gap-4 text-[11px] text-white/50 border-b border-white/5">
            <span class="font-bold text-white/70">Keterangan Zona:</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-emerald-500"></span> Peringkat 1-3: Babak 8 Besar / Playoff Promosi</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-white/20"></span> Peringkat 4-6: Zona Aman</span>
            <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-red-500/80"></span> Peringkat 7-10: Playoff Degradasi</span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto mt-4">
            <table class="w-full text-left text-xs text-white/80">
                <thead>
                    <tr class="border-b border-white/10 text-white/40 uppercase font-bold text-[10px] tracking-wider">
                        <th class="py-3 px-3 text-center w-12">#</th>
                        <th class="py-3 px-4">Klub / Tim</th>
                        <th class="py-3 px-3 text-center" title="Main">T</th>
                        <th class="py-3 px-3 text-center" title="Menang">M</th>
                        <th class="py-3 px-3 text-center" title="Seri">S</th>
                        <th class="py-3 px-3 text-center" title="Kalah">K</th>
                        <th class="py-3 px-3 text-center" title="Gol Masuk - Kemasukan">Gol</th>
                        <th class="py-3 px-3 text-center" title="Selisih Gol">SG</th>
                        <th class="py-3 px-3 text-center font-bold text-white" title="Poin">PTS</th>
                        <th class="py-3 px-4 text-center">Form (5 Laga)</th>
                        <th class="py-3 px-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($standings as $team)
                        @php
                            $isPsms = $team->is_our_team;
                            $formArr = array_filter(explode(',', $team->form ?? ''));
                        @endphp
                        <tr class="transition-colors {{ $isPsms ? 'bg-emerald-500/10 font-medium border-l-4 border-l-emerald-500 hover:bg-emerald-500/15' : 'hover:bg-white/5' }}">
                            {{-- Position --}}
                            <td class="py-3.5 px-3 text-center font-bold">
                                @if($team->position <= 3)
                                    <span class="w-6 h-6 mx-auto rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs font-black">
                                        {{ $team->position }}
                                    </span>
                                @elseif($team->position >= 7)
                                    <span class="w-6 h-6 mx-auto rounded-full bg-red-500/20 text-red-400 flex items-center justify-center text-xs font-black">
                                        {{ $team->position }}
                                    </span>
                                @else
                                    <span class="w-6 h-6 mx-auto rounded-full bg-white/5 text-white/60 flex items-center justify-center text-xs">
                                        {{ $team->position }}
                                    </span>
                                @endif
                            </td>

                            {{-- Club Name --}}
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg {{ $isPsms ? 'bg-emerald-500 text-white font-black' : 'bg-white/10 text-white/60' }} flex items-center justify-center text-xs font-mono font-bold shadow">
                                        {{ $team->team_code ?? substr($team->team_name, 0, 3) }}
                                    </div>
                                    <div>
                                        <div class="font-bold {{ $isPsms ? 'text-emerald-300 text-sm flex items-center gap-1.5' : 'text-white' }}">
                                            <span>{{ $team->team_name }}</span>
                                            @if($isPsms)
                                                <span class="px-1.5 py-0.5 rounded text-[9px] bg-smeck-gold text-smeck-dark-950 font-black">AYAM KINANTAN</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-white/40">
                                            @if($team->position <= 3)
                                                <span class="text-emerald-400 font-medium">Playoff Promosi</span>
                                            @elseif($team->position >= 7)
                                                <span class="text-red-400 font-medium">Playoff Degradasi</span>
                                            @else
                                                <span>Zona Aman</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Main --}}
                            <td class="py-3.5 px-3 text-center font-mono font-bold">{{ $team->played }}</td>

                            {{-- Menang --}}
                            <td class="py-3.5 px-3 text-center font-mono text-emerald-400">{{ $team->won }}</td>

                            {{-- Seri --}}
                            <td class="py-3.5 px-3 text-center font-mono text-amber-300">{{ $team->drawn }}</td>

                            {{-- Kalah --}}
                            <td class="py-3.5 px-3 text-center font-mono text-red-400">{{ $team->lost }}</td>

                            {{-- Gol --}}
                            <td class="py-3.5 px-3 text-center font-mono text-white/70">{{ $team->goals_for }}:{{ $team->goals_against }}</td>

                            {{-- Selisih Gol --}}
                            <td class="py-3.5 px-3 text-center font-mono font-bold {{ $team->goal_difference > 0 ? 'text-emerald-400' : ($team->goal_difference < 0 ? 'text-red-400' : 'text-white/60') }}">
                                {{ $team->goal_difference > 0 ? '+' : '' }}{{ $team->goal_difference }}
                            </td>

                            {{-- Poin --}}
                            <td class="py-3.5 px-3 text-center font-mono font-black text-sm {{ $isPsms ? 'text-emerald-400' : 'text-white' }}">
                                {{ $team->points }}
                            </td>

                            {{-- Form Guide --}}
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @forelse($formArr as $f)
                                        @if(trim(strtoupper($f)) === 'W')
                                            <span class="w-5 h-5 rounded-md bg-emerald-500/20 text-emerald-400 text-[10px] font-bold flex items-center justify-center" title="Menang">M</span>
                                        @elseif(trim(strtoupper($f)) === 'D')
                                            <span class="w-5 h-5 rounded-md bg-amber-500/20 text-amber-300 text-[10px] font-bold flex items-center justify-center" title="Seri">S</span>
                                        @else
                                            <span class="w-5 h-5 rounded-md bg-red-500/20 text-red-400 text-[10px] font-bold flex items-center justify-center" title="Kalah">K</span>
                                        @endif
                                    @empty
                                        <span class="text-white/30 text-[10px]">-</span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- Action Edit Manual --}}
                            <td class="py-3.5 px-3 text-center">
                                <button onclick="openEditModal({{ json_encode($team) }})" class="p-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-white/60 hover:text-white transition-colors" title="Edit Manual">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="py-8 text-center text-white/40">
                                Belum ada data klasemen. Silakan klik tombol "Sinkronkan dari PT LIB".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Edit Manual Tim / Poin --}}
<div id="editStandingModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-lg bg-smeck-dark-900 border border-white/10 rounded-3xl p-6 sm:p-8 shadow-2xl relative">
        <button onclick="closeEditModal()" class="absolute top-5 right-5 text-white/40 hover:text-white text-xl">&times;</button>
        
        <div class="mb-5">
            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300">Penyesuaian Manual</span>
            <h3 id="modalTeamName" class="text-lg font-display font-bold text-white mt-1">Edit Statistik Tim</h3>
            <p class="text-xs text-white/50">Gunakan bila ada keputusan sanksi pengurangan poin atau koreksi dari PSSI/Komdis.</p>
        </div>

        <form id="editStandingForm" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Main (T)</label>
                    <input type="number" id="editPlayed" name="played" min="0" required class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-mono">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-emerald-400 mb-1">Menang (M)</label>
                    <input type="number" id="editWon" name="won" min="0" required class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-mono">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-amber-300 mb-1">Seri (S)</label>
                    <input type="number" id="editDrawn" name="drawn" min="0" required class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-mono">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-red-400 mb-1">Kalah (K)</label>
                    <input type="number" id="editLost" name="lost" min="0" required class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-mono">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Gol Masuk (GM)</label>
                    <input type="number" id="editGoalsFor" name="goals_for" min="0" required class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-mono">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Kemasukan (GK)</label>
                    <input type="number" id="editGoalsAgainst" name="goals_against" min="0" required class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-mono">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 pt-1">
                <div>
                    <label class="block text-[10px] font-bold uppercase text-smeck-gold mb-1">Total Poin (PTS) <span class="text-xs text-white/40">*Bisa diedit</span></label>
                    <input type="number" id="editPoints" name="points" required class="w-full px-3 py-2 bg-white/5 border border-smeck-gold/30 rounded-xl text-smeck-gold text-sm font-bold font-mono">
                </div>
                <div>
                    <label class="block text-[10px] font-bold uppercase text-white/60 mb-1">Form (W,D,L)</label>
                    <input type="text" id="editForm" name="form" placeholder="Contoh: W,D,W,W,L" class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-xl text-white text-xs font-mono">
                </div>
            </div>

            <div class="pt-3 flex items-center justify-end gap-2 border-t border-white/10">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 text-white/70 text-xs">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg">Simpan Penyesuaian</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(team) {
        document.getElementById('modalTeamName').textContent = 'Edit: ' + team.team_name;
        document.getElementById('editStandingForm').action = "{{ url('superadmin/standings') }}/" + team.id + "/update";
        document.getElementById('editPlayed').value = team.played;
        document.getElementById('editWon').value = team.won;
        document.getElementById('editDrawn').value = team.drawn;
        document.getElementById('editLost').value = team.lost;
        document.getElementById('editGoalsFor').value = team.goals_for;
        document.getElementById('editGoalsAgainst').value = team.goals_against;
        document.getElementById('editPoints').value = team.points;
        document.getElementById('editForm').value = team.form || '';
        document.getElementById('editStandingModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editStandingModal').classList.add('hidden');
    }
</script>
@endsection

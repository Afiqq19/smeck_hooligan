<?php

namespace App\Console\Commands;

use App\Services\PtLibScraperService;
use Illuminate\Console\Command;

class SyncLeagueStandings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'league:sync-standings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi klasemen Liga 2 Indonesia dari PT LIB / Scraper Feed (Opsi B - 100% Gratis)';

    /**
     * Execute the console command.
     */
    public function handle(PtLibScraperService $service): int
    {
        $this->info('Memulai sinkronisasi klasemen Pegadaian Liga 2 (Grup Barat)...');

        $result = $service->syncStandings();

        if ($result['success']) {
            $this->info("BERHASIL! {$result['count']} klub diperbarui.");
            $this->info("PSMS Medan berada di peringkat ke-{$result['psms_position']} dengan {$result['psms_points']} poin.");
            return Command::SUCCESS;
        }

        $this->error('Gagal melakukan sinkronisasi klasemen.');
        return Command::FAILURE;
    }
}

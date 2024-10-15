<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteOldStreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streams:delete-old';
    protected $description = 'Menghapus data dari tabel current_stream yang lebih dari 30 hari';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = Carbon::now()->subDays(30);

        // Hapus data yang lebih lama dari 30 hari
        DB::table('curent_streams')
            ->where('created_at', '<', $date)
            ->delete();

        // Tampilkan pesan sukses
        $this->info('Data yang lebih dari 30 hari telah dihapus dari tabel current_stream.');
    }
}

<?php

namespace App\Console;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $today = Carbon::now()->toDateString();
            $threeDaysLater = Carbon::now()->addDays(3)->toDateString();
            //ambil semua suncription yang dealine hari ini
            $sub = Subscription::where('end_date', '<=', $threeDaysLater)
                ->where('start_date', '>=', $today)
                ->get();
            foreach ($sub as $item) {
                // Cek apakah sudah ada perpanjangan sebelumnya untuk customer ini dan paket ini
                $existingSubscription = Subscription::where('customer_id', $item->customer_id)
                    ->where('packet_id', $item->packet_id)
                    ->where('end_date', '>=', $today)
                    ->exists();

                $paket = Package::find($item->packet_id)->first();
                // Jika tidak ada, buat subscription baru
                if (!$existingSubscription) {
                    Subscription::create([
                        'customer_id' => $item->customer_id,
                        'packet_id' => $item->packet_id,
                        'start_date' => $today,
                        'end_date' => Carbon::parse($today)->addMonth($paket->duration)->toDateString(), // Misalkan perpanjangan 1 bulan
                        'status' => 0
                    ]);
                }
            }


            Customer::whereHas('subcrib', function ($query) use ($today) {
                $query->where('end_date', '<', $today);
            })->update(['is_active' => '0']);
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

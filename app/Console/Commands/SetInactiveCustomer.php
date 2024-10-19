<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SetInactiveCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:customer-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command auto set inactive customer ketika sudah jatuh tempo';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $today = Carbon::now()->toDateString();
        $batchSize = 500;
        
        $processedCount = 0;
        
        Customer::whereHas('subcrib', function ($query) use ($today) {
            $query->where('end_date', '<=', $today)
                ->where('is_active', 1);
        })
        ->chunk($batchSize, function ($customers) use (&$processedCount, $today) {
            foreach ($customers as $customer) {
                // Cek subscription sebelumnya yang sudah jatuh tempo
                $expiredSubscription = $customer->subcrib()
                    ->where('end_date', '<=', $today)
                    ->where('status', 1)
                    ->orderBy('end_date', 'desc')
                    ->first();
        
                if ($expiredSubscription) {
                    // Set customer ke inactive
                    $customer->update(['is_active' => 0]);
                    
                    // // Optional: Update status subscription menjadi tidak aktif
                    //  $expiredSubscription->update(['status' => 1]);
        
                    $processedCount++;
                    \Log::info("Customer {$customer->id} set to inactive. Expired subscription: {$expiredSubscription->id}");
                }
            }
        });
        
        \Log::info("Total customers set to inactive: {$processedCount}");




    }
}

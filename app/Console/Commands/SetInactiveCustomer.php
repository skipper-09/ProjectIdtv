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
        // $today = Carbon::now()->toDateString();
        // $threeDaysLater = Carbon::now()->addDays(3)->toDateString();
        
        // // Fetch subscriptions that are expired or have no payment
        // $subs = Subscription::where('end_date', '<=', $today)
        //     ->orWhereDoesntHave('payment')
        //     ->get();
        
        // foreach ($subs as $subscription) {
        //     $customer = $subscription->customer; // Assuming Subscription has a relationship to Customer
        //     if ($customer) {
        //         $customer->update(['is_active' => 0]);
        //     }
        // }
        
        // \Log::info('Subscriptions processed: ' . $subs->count());
        $today = Carbon::now()->toDateString(); // Get today's date
$batchSize = 500; // Adjust batch size based on your system capacity


Subscription::whereDoesntHave('payment') 
    ->where('end_date', '<=', $today) 
    ->where('status', 1) 
    ->chunk($batchSize, function ($subs) {
        $customerIds = []; 

        foreach ($subs as $subscription) {
            if ($subscription->customer) {
                $customerIds[] = $subscription->customer->id;
            }
        }

        // Bulk update to set customers as inactive
        if (!empty($customerIds)) {
            Customer::whereIn('id', $customerIds)->update(['is_active' => 0]);
        }

        // Log the number of customers updated in each batch
        \Log::info('Processed batch of subscriptions, updated customers: ' . count($customerIds));
    });
    }
}
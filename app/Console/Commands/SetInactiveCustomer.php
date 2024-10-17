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
        $today = Carbon::now()->toDateString();
$batchSize = 500; // Adjust batch size based on your system capacity

// Use chunking to process records in batches
Subscription::where('end_date', '<=', $today)
    ->orWhereDoesntHave('payment')
    ->chunk($batchSize, function ($subs) {
        // Collect customer IDs to update in bulk
        $customerIds = [];

        foreach ($subs as $subscription) {
            if ($subscription->customer) {
                $customerIds[] = $subscription->customer->id;
            }
        }

        // Update customers in bulk to avoid multiple queries
        if (!empty($customerIds)) {
            Customer::whereIn('id', $customerIds)->update(['is_active' => 0]);
        }

        \Log::info('Processed batch of subscriptions, updated customers: ' . count($customerIds));
    });
    }
}

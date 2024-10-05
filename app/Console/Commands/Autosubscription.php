<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class Autosubscription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:renew';
    protected $description = 'Renew subscriptions and update customer status if needed';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Log
        \Log::info('Scheduler started at ' . Carbon::now());


        $today = Carbon::now()->toDateString();
        $threeDaysLater = Carbon::now()->addDays(3);

        // Ambil data secara batch untuk menghindari beban besar pada database
        $subs = Subscription::where('end_date', '<=', $threeDaysLater)
            ->where('start_date', '>=', $today)->get();

        foreach ($subs as $item) {
            // Cek apakah sudah ada perpanjangan sebelumnya
            $existingSubscription = Subscription::where('customer_id', $item->customer_id)
                ->where('packet_id', $item->packet_id)
                ->where(function ($query) use ($today,$item) {
                    $query->where('end_date', '>', $item->end_date);
//                           ->orWhere('status', 0)->whereHas('payment',function($query) use($item){
// $query->where('subscribetion_id',$item->id);
//                           });
                })
                ->exists();

            $paket = Package::find($item->packet_id);
            if (!$existingSubscription && $paket) {
                Subscription::create([
                    'customer_id' => $item->customer_id,
                    'packet_id' => $item->packet_id,
                    'start_date' => null,
                    'end_date' => Carbon::parse($item->end_date)->addMonth($paket->duration)->toDateString(),
                    'status' => false,
                ]);
            }
        }


        Customer::whereHas('subcrib', function ($query) use ($today) {
            $query->where('end_date', '<', $today);
        })->update(['is_active' => 0]);



        //  $today = Carbon::now()->toDateString();
        //  $threeDaysLater = Carbon::now()->addDays(3)->toDateString();

        //  // Ambil data secara batch untuk menghindari beban besar pada database
        //  Subscription::where('end_date', '<=', $threeDaysLater)
        //      ->where('start_date', '>=', $today)
        //      ->chunk(10, function ($subs) use ($today) {
        //          foreach ($subs as $item) {
        //              // Cek apakah sudah ada perpanjangan sebelumnya
        //              $existingSubscription = Subscription::where('customer_id', $item->customer_id)
        //                  ->where('packet_id', $item->packet_id)
        //                  ->where('end_date', '<=', $today)
        //                  ->exists();

        //              $paket = Package::find($item->packet_id);
        //              if (!$existingSubscription && $paket) {
        //                  Subscription::create([
        //                      'customer_id' => $item->customer_id,
        //                      'packet_id' => $item->packet_id,
        //                      'start_date' => null,
        //                      'end_date' => Carbon::parse($today)->addMonth($paket->duration)->toDateString(),
        //                      'status' => false,
        //                  ]);
        //              }
        //          }
        //      });

        //  Customer::whereHas('subcrib', function ($query) use ($today) {
        //      $query->where('end_date', '<', $today);
        //  })->update(['is_active' => '0']);

        // Log selesai
        \Log::info('Scheduler completed at ' . Carbon::now());

    }

}

<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $customer = Customer::create([
            'name' => 'YACUP SUGIARTO / SUTOMO YUWONO - RT5 RW2 KAMPUNG BARU JL JUANDA NO 61',
            'company_id' => 1,
            'region_id' => 1,
            'stb_id' => 1,
            'mac' => '064220908733',
            'nik' => '3510072601520002',
            'phone' => '0876544646',
            'address' => 'RT5 RW2 KAMPUNG BARU JL JUANDA NO 61',
            'username' => 'ahmad',
            'showpassword' => 'ahmad',
            'password' => Hash::make('ahmad'),
        ]);

        $subcription = Subscription::create([
            'customer_id' => $customer->id,
            'packet_id' => 1,
            'start_date' => now(),
            'end_date' => now()->addMonth(1),
        ]);
    }
}

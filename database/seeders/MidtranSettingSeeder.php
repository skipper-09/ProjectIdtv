<?php

namespace Database\Seeders;

use App\Models\MidtransSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MidtranSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MidtransSetting::insert([
            'client_key' => 'clientkey anda',
            'server_key' => 'serverkey anda',
            'url' => 'https://app.sandbox.midtrans.com',
        ]);
    }
}

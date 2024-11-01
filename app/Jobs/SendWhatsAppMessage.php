<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $name;
    protected $address;
    protected $username;
    protected $password;
    protected $phone;

    /**
     * Create a new job instance.
     *
     * @param string $name
     * @param string $address
     * @param string $username
     * @param string $password
     * @param string $phone
     * @return void
     */
    public function __construct($name, $address, $username, $password, $phone)
    {
        $this->name = $name;
        $this->address = $address;
        $this->username = $username;
        $this->password = $password;
        $this->phone = $phone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(40); // Tunggu 40 detik sebelum mengirim pesan

        $pesan = "Halo, *{$this->name}*!\n\nPendaftaran Anda telah berhasil.\nBerikut adalah detail akun Anda:\n\nNama: *{$this->name}*\nAlamat: *{$this->address}*\nUsername: *{$this->username}*\nPassword: *{$this->password}*\n\nSilakan gunakan username dan password ini untuk login ke sistem kami.\nPastikan untuk menjaga kerahasiaan informasi akun Anda.";

        $params = [
            [
                'name' => 'phone',
                'contents' => $this->phone
            ],
            [
                'name' => 'message',
                'contents' => $pesan
            ]
        ];

        $auth = env('WABLAS_TOKEN');
        $url = env('WABLAS_URL');

        Http::withHeaders([
            'Authorization' => $auth,
        ])->asMultipart()->post("$url/api/send-message", $params);
    }
}

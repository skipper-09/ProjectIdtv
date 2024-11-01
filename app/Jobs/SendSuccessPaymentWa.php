<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendSuccessPaymentWa implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $name;
    protected $amount;
    protected $paymnet;
    protected $phone;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name,$amount,$paymnet,$phone)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->paymnet = $paymnet;
        $this->phone = $phone;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        sleep(40);
        $pesan = "Halo, *{$this->name}*!\n\nPembayaran Anda telah berhasil.\n\nDetail pembayaran:\nNama: *{$this->name}*\nJumlah Pembayaran: *Rp {$this->amount}*\nTanggal Pembayaran: *{$this->paymnet->created_at}*\n\nTerima kasih telah melakukan pembayaran. Jika ada pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami.";



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

        $response = Http::withHeaders([
            'Authorization' => $auth,
        ])->asMultipart()->post("$url/api/send-message", $params);

        $responseBody = json_decode($response->body());
    }
}

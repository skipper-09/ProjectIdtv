<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendInvoceWa implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $name;
    protected $tagihan;
    protected $end_date;
    protected $phone;
    
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name, $tagihan, $end_date,$phone)
    {
        $this->name = $name;
        $this->tagihan = $tagihan;
        $this->end_date = $end_date;
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
        $pesan =
        "Halo, *{$this->name}*!\n\nKami ingin menginformasikan bahwa tagihan Anda telah diterbitkan. Berikut adalah rincian tagihan Anda:\n\nNama: *{$this->name}*\nJumlah Tagihan: Rp. *{$this->tagihan}*\nTanggal Jatuh Tempo: *{$this->end_date}*\n\nSilakan lakukan pembayaran sebelum tanggal jatuh tempo\nTerima kasih.";


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

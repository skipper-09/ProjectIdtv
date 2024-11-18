<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PeriodeIncomeExport implements FromCollection, WithHeadings, WithColumnFormatting
{
    protected $start_date;
    protected $end_date;

    // Constructor untuk menerima parameter start_date dan end_date
    public function __construct($start_date, $end_date,$type)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->type = $type;
    }
    public function collection()
    {
        
        return Payment::with('subscrib', 'customer')->whereHas('customer',function($query){
            $query->where('type',$this->type);
        }) 
            ->whereBetween('created_at', [$this->start_date, $this->end_date])
            ->get()
            ->map(function($payment) {
                return [
                    'nama' => $payment->customer->name, 
                    'paket' => $payment->subscrib->customer->type == 'reseller' ? $payment->customer->resellerpaket->name : $payment->customer->paket->name, 
                    'perpanjang'=>$payment->subscrib->start_date,
                    'deadline'=>$payment->subscrib->end_date,
                    'harga pokok'=> $payment->subscrib->customer->type == 'reseller' ? $payment->customer->resellerpaket->total : $payment->customer->paket->price,
                    'fee reseller'=> $payment->subscrib->customer->type == 'reseller' ? $payment->customer->resellerpaket->price : 0,
                    'status'=> 'Lunas',
                    'tanggal bayar' =>$payment->created_at,
                    'metode bayar' =>$payment->payment_type,
                    'owner'=>$payment->subscrib->customer->type == 'reseller' ? $payment->customer->reseller->name : $payment->customer->company->name,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Paket',
            'Perpanjang',
            'Deadline',
            'Harga Pokok',
            'Fee Reseller',
            'Status',
            'Tanggal Bayar',
            'Metode Bayar',
            'Owner'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Format untuk kolom Jumlah Pembayaran
            'D' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Format untuk kolom Fee
        ];
    }
}

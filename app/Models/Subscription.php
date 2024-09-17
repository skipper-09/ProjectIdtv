<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'packet_id',
        'invoices',
        'start_date',
        'end_date',
        'status'
    ];
    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();

        // generate invoice number
        static::creating(function ($invoice) {
            $invoice->invoices = $invoice->generateInvoiceNumber();
        });
    }

    // generate a random invoice number
    public function generateInvoiceNumber()
    {
        $prefix = 'INV-';
        $randomNumber = rand(1000, 9999);
        return $prefix . time() . '-' . $randomNumber;
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function paket()
    {
        return $this->belongsTo(Package::class);
    }
}

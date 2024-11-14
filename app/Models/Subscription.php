<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'packet_id',
        'invoices',
        'start_date',
        'end_date',
        'status',
        'fee',
        'is_claim',
        'midtras_random',
        'midtras_link',
        'tagihan',
        'reseller_package_id'
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
        $randomNumber = rand(10000, 99999) * time();
        return $prefix . $randomNumber;
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class,'customer_id','id');
    }
    public function paket()
    {
        return $this->belongsTo(Package::class, 'packet_id', 'id');
    }
    public function resellerpaket()
    {
        return $this->belongsTo(ResellerPaket::class,'reseller_package_id','id');
    }
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
    public function detailfee()
    {
        return $this->hasMany(DetailClaim::class);
    }
}

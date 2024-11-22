<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscription_id',
        'customer_id',
        'amount',
        'fee',
        'status',
        'tanggal_bayar',
        'checkout_link',
        'payment_type',
    ];
    protected $primaryKey = 'id';

    public function subscrib()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}

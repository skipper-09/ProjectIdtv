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
        'start_date',
        'end_date',
        'status'
    ];
    protected $primaryKey = 'id';

    public function customer()
    {
        return $this->belongsTo(Customer::class,  'id', 'customer_id');
    }
    public function paket()
    {
        return $this->belongsTo(Package::class, 'id', 'packet_id');
    }
}

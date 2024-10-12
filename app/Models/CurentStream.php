<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurentStream extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'chanel_id',
        'started_at',
        'device_id',

    ];
    protected $primaryKey = 'id';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function chanel()
    {
        return $this->belongsTo(Chanel::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResellerPaket extends Model
{
    use HasFactory;
    protected $fillable = [
        'paket_id',
        'reseller_id',
        'name',
        'price',
        'status'
    ];
    protected $primaryKey = 'id';

    public function reseller()
    {
        return $this->belongsTo(Reseller::class);
    }
    public function paket()
    {
        return $this->belongsTo(Package::class);
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = number_format($value, 2, '.', '');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'is_active',
        'owner_id',

    ];
    protected $primaryKey = 'id';

    public function customer()
    {
        return $this->hasMany(Customer::class);
    }
    public function owner()
    {
        return $this->belongsTo(owner::class, 'owner_id', 'id');
    }
}

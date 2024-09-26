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
        'user_id',

    ];
    protected $primaryKey = 'id';

    public function customer()
    {
        return $this->hasMany(Customer::class);
    }
    public function paket()
    {
        return $this->hasMany(Package::class);
    }
    public function owner()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    //static get all self item
    public static function getAllItems()
    {
        return self::all(); // Mengambil semua data dari model Item
    }
}

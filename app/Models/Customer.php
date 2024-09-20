<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Model;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasFactory, HasApiTokens;
    protected $guard = 'customer';
    protected $fillable = [
        'name',
        'address',
        'phone',
        'mac',
        'username',
        'password',
        'showpassword',
        'nik',
        'company_id',
        'stb_id',
        'region_id',
        'is_active'
    ];
    protected $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function stb()
    {
        return $this->belongsTo(Stb::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    public function subcrib()
    {
        return $this->hasMany(Subscription::class);
    }
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
}

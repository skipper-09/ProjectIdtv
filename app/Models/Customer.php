<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'address', 'phone', 'username', 'password'
    ];
    protected $primaryKey = 'id';

    protected $hidden = [
        'password',
    ];

    public function stb()
    {
        return $this->belongsTo(Stb::class, 'stb_id', 'id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
}

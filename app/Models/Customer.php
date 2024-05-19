<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'address', 'phone','mac' ,'username', 'password','ppoe','company_id','stb_id','region_id','is_active'
    ];
    protected $primaryKey = 'id';

    protected $hidden = [
        'password',
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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'duration',
        'company_id'
    ];
    protected $primaryKey = 'id';

    // public function company()
    // {
    //     $this->belongsTo(Company::class,'company_id','id');
    // }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}

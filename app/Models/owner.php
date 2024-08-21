<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class owner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'company_id',
        'username',
        'password'
    ];
    protected $primaryKey = 'id';

    public function company()
    {
        return $this->hasMany(Company::class);
    }
}

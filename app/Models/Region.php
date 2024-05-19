<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'company_id'
    ];
    protected $primaryKey = 'id';

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}

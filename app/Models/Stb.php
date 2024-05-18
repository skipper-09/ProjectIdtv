<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stb extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','ram','internal'
    ];
    protected $primaryKey = 'id';
}

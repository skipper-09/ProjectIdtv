<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chanel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name','id_kategori','url','logo','user_agent','type','security_type','is_active'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MidtransSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_key','server_key','url','production'
    ];
    protected $primaryKey = 'id';
}

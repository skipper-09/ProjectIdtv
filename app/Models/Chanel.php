<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chanel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'categori_id', 'url', 'logo', 'user_agent', 'type', 'security_type', 'is_active', 'security'
    ];
    protected $primaryKey = 'id';


    public function categori()
    {
        return $this->belongsTo(Categori::class);
    }
}

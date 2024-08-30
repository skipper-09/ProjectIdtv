<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chanel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'categori_id',
        'url',
        'replacement_url',
        'logo',
        'user_agent',
        'type',
        'security_type',
        'is_active',
        'security'
    ];
    protected $primaryKey = 'id';


    public function categori()
    {
        return $this->belongsTo(Categori::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($channel) {
            $channel->replacement_url = Str::uuid(); // atau metode lain untuk menghasilkan URL unik
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Chanel extends Model
{
    use HasFactory,LogsActivity;
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
        return $this->belongsTo(Categori::class,'categori_id','id');
    }

    public function curentstream(){
        return $this->hasMany(CurentStream::class);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($channel) {
            $channel->replacement_url = Str::uuid(); // atau metode lain untuk menghasilkan URL unik
        });
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults() ->useLogName('Chanel')->logOnly(['name']);
    }

    public function getDescriptionForEvent(string $event): string
    {
        return "Chanel has been {$event}"; // Mengembalikan deskripsi sesuai dengan event
    }
}

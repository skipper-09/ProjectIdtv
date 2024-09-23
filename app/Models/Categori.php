<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Categori extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'name',
    ];
    protected $primaryKey = 'id';
   
    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults() ->useLogName('Category')->logOnly(['name']);
    }

    public function getDescriptionForEvent(string $event): string
    {
        return "Category has been {$event}"; // Mengembalikan deskripsi sesuai dengan event
    }


    public function chanel()
    {
        return $this->hasMany(Chanel::class);
    }
}

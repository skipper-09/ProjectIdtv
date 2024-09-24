<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Region extends Model
{
    use HasFactory,LogsActivity;
    protected $fillable = [
        'name'
    ];
    protected $primaryKey = 'id';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults() ->useLogName('Area')->logOnly(['name']);
    }

    public function getDescriptionForEvent(string $event): string
    {
        return "Area has been {$event}"; // Mengembalikan deskripsi sesuai dengan event
    }
}

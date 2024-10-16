<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Stb extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'ram',
        'internal'
    ];
    protected $primaryKey = 'id';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Stb')->logOnly(['name', 'ram', 'internal'])->logOnlyDirty();
    }

    public function getDescriptionForEvent(string $event): string
    {
        return "Stb has been {$event}"; // Mengembalikan deskripsi sesuai dengan event
    }
}

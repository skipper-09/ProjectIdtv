<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        return LogOptions::defaults()
            ->logFillable();
        // Chain fluent methods for configuration options
    }

    public function chanel()
    {
        return $this->hasMany(Chanel::class);
    }
}

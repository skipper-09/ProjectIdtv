<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Package extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'name',
        'price',
        'duration',
        'company_id',
        'type_paket',
        'status'
    ];
    protected $primaryKey = 'id';



    public function paket()
    {
        return $this->hasMany(Package::class);
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Paket')->logOnly(['name', 'price', 'duration'])->logOnlyDirty();
    }

    public function getDescriptionForEvent(string $event): string
    {
        return "Paket has been {$event}"; // Mengembalikan deskripsi sesuai dengan event
    }
}

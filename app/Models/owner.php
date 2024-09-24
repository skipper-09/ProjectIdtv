<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class owner extends Model
{
    use HasFactory,LogsActivity;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'username',
        'showpassword',
        'password'
    ];
    protected $primaryKey = 'id';

    public function company()
    {
        return $this->hasMany(Company::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults() ->useLogName('Owner')->logOnly(['name','phone','email','address','username']);
    }

    public function getDescriptionForEvent(string $event): string
    {
        return "Owner has been {$event}"; // Mengembalikan deskripsi sesuai dengan event
    }
}

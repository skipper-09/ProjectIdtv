<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Model;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class Customer extends Model
{
    use HasFactory, HasApiTokens, LogsActivity;
    protected $guard = 'customer';
    protected $fillable = [
        'name',
        'address',
        'phone',
        'mac',
        'username',
        'password',
        'showpassword',
        'nik',
        'company_id',
        'stb_id',
        'region_id',
        'is_active',
        'device_id',
        'is_active',
        'reseller_id',
        'resellerpaket_id',
        'paket_id',
        'type',
        'id_pelanggan',
    ];
    protected $primaryKey = 'id';

    protected $hidden = [
        'password',
        'remember_token',
    ];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate ID pelanggan otomatis jika belum diisi
            if (empty($model->id_pelanggan)) {
                $model->id_pelanggan = self::generateIdPelanggan();
            }
        });
    }

    /**
     * Generate unique ID pelanggan.
     */
    private static function generateIdPelanggan()
    {
        $prefix = 'PLG'; 
        $randomNumber = Str::padLeft(rand(1, 99999), 5, '0'); 
        $timestamp = now()->format('Ymd'); 
        return $prefix . $timestamp . $randomNumber; 
    }

    public function stb()
    {
        return $this->belongsTo(Stb::class, 'stb_id', 'id');
    }
    public function paket()
    {
        return $this->belongsTo(Package::class, 'paket_id', 'id');
    }
    
   
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id', 'id');
    }
    public function subcrib()
    {
        return $this->hasMany(Subscription::class);
    }
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }
    public function reseller(){
        return $this->belongsTo(Reseller::class);
    }
    public function resellerpaket(){
        return $this->belongsTo(ResellerPaket::class);
    }

    public function curentstream()
    {
        return $this->hasMany(CurentStream::class);
    }

    //log automatyly created
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Customer')->logOnly([
            'name',
            'address',
            'phone',
            'mac',
            'username',
            'nik',
            'company.name',
            'stb.name',
            'region.name',
            'device_id',
            'is_active',
        ])->logOnlyDirty();
    }

    public function getDescriptionForEvent(string $event): string
    {
        return "Customer has been {$event}"; // Mengembalikan deskripsi sesuai dengan event

    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory, LogsActivity;
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'is_active',
        'user_id',
        'fee_reseller',
        'rekening',
        'bank_name',
        'owner_rek',
        'referal'

    ];
    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();

        // generate invoice number
        static::creating(function ($company) {
            $company->referal = self::generateUniqueReferralCode();
        });
    }

    // Method untuk membuat kode referral unik
    private static function generateUniqueReferralCode()
    {
        do {
            $code = Str::upper(Str::random(6)); 
        } while (self::where('referal', $code)->exists());

        return $code;
    }

    public function customer()
    {
        return $this->hasMany(Customer::class);
    }
    public function feeclaim()
    {
        return $this->hasMany(Fee_claim::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    //static get all self item
    public static function getAllItems()
    {
        return self::all(); // Mengambil semua data dari model Item
    }


    //save log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->useLogName('Company')->logOnly([
            'name',
            'address',
            'phone',
            'email',
            'is_active',
            'owner.name',
            'fee_reseller',
            'rekening',
            'bank_name',
            'owner_rek',
        ])->logOnlyDirty();
    }


    public function getDescriptionForEvent(string $event): string
    {
        return "Company has been {$event}"; // Mengembalikan deskripsi sesuai dengan event
    }
}

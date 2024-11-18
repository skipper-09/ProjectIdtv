<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reseller extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id',
        'user_id',
        'bank_id',
        'name',
        'address',
        'phone',
        'referal_code',
        'rekening',
        'owner_rek',
        'status',
    ];
    protected $primaryKey = 'id';

    protected static function boot()
    {
        parent::boot();

        // generate invoice number
        static::creating(function ($company) {
            $company->referal_code = self::generateUniqueReferralCode();
        });
    }

    // Method untuk membuat kode referral unik
    private static function generateUniqueReferralCode()
    {
        do {
            $code = Str::upper(Str::random(6));
        } while (self::where('referal_code', $code)->exists());

        return $code;
    }


    protected static function booted()
    {
        static::updated(function ($reseller) {
            if ($reseller->isDirty('status') && $reseller->status == 0) {
                Customer::where('reseller_id', $reseller->id)->update(['is_active' => 0]);
            }elseif ($reseller->isDirty('status') && $reseller->status == 1) {
                Customer::where('reseller_id', $reseller->id)
                    ->whereHas('subcrib', function ($query) {
                        $query->whereDate('end_date', '>=', now())->where('status',1)->orderByDesc('created_at')->limit(1);
                    })
                    ->update(['is_active' => 1]);
            }
        });
    }

    public static function getAllItems()
    {

        return Reseller::all();
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resellerpaket()
    {
        return $this->hasMany(ResellerPaket::class);
    }
}

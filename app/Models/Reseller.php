<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Reseller extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id','user_id','bank_id','name','address','phone','referal_code','rekening','owner_rek'
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


    public function bank(){
        return $this->belongsTo(Bank::class);
    }
    public function company(){
        return $this->belongsTo(Company::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}

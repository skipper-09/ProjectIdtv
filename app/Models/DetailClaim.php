<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailClaim extends Model
{
    use HasFactory;
    protected $fillable = [
        'subscription_id','feeclaim_id'
    ];
    protected $primaryKey = 'id';


    public function subscribe(){
        return $this->belongsTo(Subscription::class,'subscription_id','id');
    }
    public function feeclaim(){
        return $this->belongsTo(Fee_claim::class,'feeclaim_id','id');
    }
}

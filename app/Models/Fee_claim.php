<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee_claim extends Model
{
    use HasFactory;
    protected $fillable = [
        'reseller_id','amount','status','bukti_tf'
    ];
    protected $primaryKey = 'id';

public function reseller(){
   return $this->belongsTo(Reseller::class,'reseller_id','id');
}
public function detailfee(){
   return $this->hasMany(DetailClaim::class);
}



}

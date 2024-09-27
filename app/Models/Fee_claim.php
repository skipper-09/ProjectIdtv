<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee_claim extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_id','amount','status'
    ];
    protected $primaryKey = 'id';

public function company(){
   return $this->belongsTo(Company::class);
}

}

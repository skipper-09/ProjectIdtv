<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VersionControl extends Model
{
    use HasFactory;
    protected $fillable = [
        'version','apk_url','release_note'
    ];
    protected $primaryKey = 'id';
}

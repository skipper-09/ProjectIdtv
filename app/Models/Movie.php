<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = [
        'genre_id',
        'title',
        'rating',
        'cover_image',
        'description',
        'type',
        'url',
        'status'
    ];
    protected $primaryKey = 'id';

    public function genre(){
        return $this->belongsTo(Genre::class);
    }
    public function episode(){
        return $this->hasMany(Episode::class);
    }


}

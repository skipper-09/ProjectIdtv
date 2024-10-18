<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    protected $fillable = [
        'movie_id',
        'title',
        'episode_number',
        'cover_image',
        'url',
        'status'
    ];
    protected $primaryKey = 'id';

    public function movie(){
        return $this->belongsTo(Movie::class);
    }
}

<?php

namespace App\Imports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MovieImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Movie([
            'genre_id' => $row['genre_id'],
            'title' => $row['title'],
            'rating' => $row['rating'],
            'cover_image' => $row['cover_image'],
            'description' => $row['description'],
            'type' => $row['type'],
            'url' => $row['url'],
            'status' => $row['status'],
        ]);
    }
}

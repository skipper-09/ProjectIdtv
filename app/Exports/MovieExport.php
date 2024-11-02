<?php

namespace App\Exports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MovieExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Movie::all();
    }


    public function headings(): array
    {
        return [
            'id',
            'genre_id',
            'title',
            'rating',
            'cover_image',
            'type',
            'url',
            'description',
            'status',
            'created_at',
            'updated_at',
        ];
    }
}
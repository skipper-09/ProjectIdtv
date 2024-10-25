<?php

namespace App\Exports;

use App\Models\Chanel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ChanelExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Chanel::all();
    }
    public function headings(): array
    {
        return [
        'id',
        'categori_id',
        'name',
        'url',
        'replacement_url',
        'logo',
        'user_agent',
        'type',
        'security_type',
        'security',
        'status',
        'created_at',
        'updated_at',
    ];
    }
}

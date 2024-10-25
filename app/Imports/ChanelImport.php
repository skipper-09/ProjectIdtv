<?php

namespace App\Imports;

use App\Models\Chanel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ChanelImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Chanel([
            'categori_id' => $row['categori_id'],
            'name' => $row['name'],
            'url' => $row['url'],
            'replacement_url' => $row['replacement_url'],
            'logo' => $row['logo'],
            'user_agent' => $row['user_agent'],
            'type' => $row['type'],
            'security_type' => $row['security_type'],
            'security' => $row['security'],
            'status' => $row['status'],
        ]);
    }
}

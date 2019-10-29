<?php

namespace App\Imports;

use App\Apoteker;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportApoteker implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Apoteker([
            'apoteker_nik'     => $row[1],
            'apoteker_name'     => $row[2],
            'apoteker_email'     => $row[3],
            'apoteker_number'     => $row[4],
            'apoteker_address'     => $row[5],
        ]);
    }
}

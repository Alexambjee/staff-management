<?php

namespace App\Imports;

use App\Models\IroDwMapping;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithUpserts;


class UserDwMappingImport implements ToModel, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function uniqueBy()
    {
        return 'OFFICER';
    }
    public function model(array $row)
    {
        return new IroDwMapping([
            'OFFICER'     => $row[0],
            'MANAGER'     => $row[1],
            'CHIEF_MANAGER'     => $row[2],
            'AMOUNT'     => $row[3]
        ]);
    }
}

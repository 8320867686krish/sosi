<?php

namespace App\Exports;

use App\Models\CheckHasHazmat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;

class VSCPSheetExport implements FromCollection, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CheckHasHazmat::all();
    }

    public function title(): string
    {
        return 'VSCP';
    }
}

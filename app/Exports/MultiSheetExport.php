<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetExport implements WithMultipleSheets
{
    protected $coverData;
    protected $summaryData;
    protected $vscpData;

    public function __construct($coverData, $summaryData)
    {
        // $this->coverData = $coverData;
        $this->summaryData = $summaryData;
        // $this->vscpData = $vscpData;
        // , $summaryData, $vscpData
    }

    public function sheets(): array
    {
        return [
            // 'COVER' => new CoverSheetExport($this->coverData, "OnBoard IHM Survey Plan"),
            'SUMMARY' => new SummarySheetExport($this->summaryData),
            // 'VSCP' => new VSCPSheetExport($this->vscpData),
        ];
    }
}

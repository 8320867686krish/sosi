<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MultiSheetExport implements WithMultipleSheets
{
    protected $coverData;
    protected $summaryData;
    protected $vscpData;
    protected $isSample;

    public function __construct($coverData, $summaryData, $vscpData,$isSample)
    {
        $this->coverData = $coverData;
        $this->summaryData = $summaryData;
        $this->vscpData = $vscpData;
        $this->isSample = $isSample;

    }

    public function sheets(): array
    {
        return [
            'COVER' => new CoverSheetExport($this->coverData, "Onboard IHM Survey Plan"),
            'SUMMARY' => new SummarySheetExport($this->summaryData),
            'VSCP' => ($this->isSample ? new labResultExport($this->vscpData) : new VSCPSheetExport($this->vscpData))
        ];
    }
}

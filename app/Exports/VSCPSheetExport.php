<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class VSCPSheetExport implements FromView, WithTitle
{
    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Return the view for the Excel sheet.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        return view('exports.vscp_report', [
            'checks' => $this->collection,
        ]);
    }

    public function title(): string
    {
        return 'VSCP';
    }
}

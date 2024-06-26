<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class SummarySheetExport implements FromView, WithTitle
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
        return view('exports.summary_report', [
            'hazmats' => $this->collection,
        ]);
    }

    public function title(): string
    {
        return 'SUMMARY';
    }
}

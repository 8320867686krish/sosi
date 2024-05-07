<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromView;

class CoverSheetExport implements FromView, WithTitle
{
    protected $collection;

    protected string $title;

    public function __construct($collection, string $title)
    {
        $this->collection = $collection;
        $this->title = $title;
    }

    /**
     * Return the view for the Excel sheet.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        return view('exports.cover_report', [
            'project' => $this->collection,
            'title' => $this->title
        ]);
    }

    /**
     * Get the title of the sheet.
     *
     * @return string
     */
    public function title(): string
    {
        return 'COVER';
    }
}

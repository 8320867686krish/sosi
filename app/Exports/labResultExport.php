<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class labResultExport implements FromView, WithTitle, WithStyles
{
    protected $collection;

    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Return the view for the Excel sheet.a
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        return view('exports.leb_vscp_report', [
            'checks' => $this->collection,
        ]);
    }

    public function title(): string
    {
        return 'VSCP';
    }

    public function styles(Worksheet $sheet)
    {
        // Set wrap text for all cells
        // $sheet->getStyle($sheet->calculateWorksheetDimension())->getAlignment()->setWrapText(true);

        // Loop through each column and set the width based on content
        foreach ($sheet->getColumnIterator() as $column) {
            $columnIndex = $column->getColumnIndex();
            $sheet->getColumnDimension($columnIndex)->setAutoSize(true);
        }
    }
}

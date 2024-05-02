<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SummarySheetExport implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    protected $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    public function title(): string
    {
        return 'SUMMARY';
    }

    public function headings(): array
    {
        // Define the column headings for your Excel file
        return [
            'Main Heading 1' => [
                'Hazardous Material',
                'Number of Checks',
            ],
            'Main Heading 2' => [
                'Sub Heading' => [
                    'Table',
                    'Key',
                    'Name',
                ],
                'Sub Heading 2' => [
                    'Sampling',
                    'Visual/Sample',
                    'Total',
                ],
            ],
        ];
    }
}

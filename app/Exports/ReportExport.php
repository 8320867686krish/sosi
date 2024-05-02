<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class ReportExport implements FromView, WithHeadings
{
    protected $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    // /**
    //  * @return \Illuminate\Support\Collection
    //  */
    // public function collection()
    // {
    //     return $this->collection;
    // }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view(): View
    {
        return view('exports.excel_report', [
            'reports' => $this->collection
        ]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Define the column headings for your Excel file
        return [
            'ID',
            'name',
            'last_name',
            'email',
            'email_verified_at',
            'location',
            'phone',
            'isVerified',
            'firebase_token',
            'created_at',
            'updated_at'
        ];
    }
}

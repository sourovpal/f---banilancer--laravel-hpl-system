<?php

namespace App\Exports;

use App\Models\QuotationReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class QuotationReportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    public function collection()
    {
        // return QuotationReport::where('rep_code', auth() -> user() -> id . '_' . date('Ymd'))->get();
        return QuotationReport::get();
    }

    public function headings(): array
    {
        return [
            '#',
            'qi id',
            'qn no',
            'qn date',
            'user',
            'item name',
            'cost center',
            'specification',
            'request qty',
            'unit',
            'pack',
            'price',
            'total price',
            'dep id',
            'rep code',
            'user_id',
            'cc_id',
            'created at',
            'updated at'
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:W1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

}

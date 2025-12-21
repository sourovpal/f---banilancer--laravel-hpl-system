<?php

namespace App\Exports;

use App\Models\DeliveryNoteReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class DeliveryReportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $from_date;
    protected $to_date;
    protected $sq;

    function __construct($from_date, $to_date, $sq)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->sq = $sq;
    }

    public function collection()
    {
        $query = DeliveryNoteReport::select('id', 'note_no', 'user', 'costcentre', 'sign_date', 'dn_date', 'item_code', 'specification',
            'request_qty', 'unit', 'packing', 'unit_price', 'total_price', 'rep_code', 'approver', 'approve_date');

         if ($this->sq == 'so') {
				$query->where('so_id', '!=', '')
					  ->where('so_no', '!=', '');
			} elseif ($this->sq == 'qt') {
				$query->where('qn_id', '!=', '')
					  ->where('qn_no', '!=', '');
			}

        return $query->when((isset($this->from_date) && !empty($this->from_date)), function ($query) {
                $query->where('delivery_note_reports.sign_date', '>=', $this->from_date);
            })
            ->when((isset($this->to_date) && !empty($this->to_date)), function ($query) {
                $query->where('delivery_note_reports.sign_date', '<=', date('Y-m-d' . ' 23:59:59', strtotime($this->to_date)));
            })
            ->orderBy('delivery_note_reports.created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'note_no', 'user', 'costcentre', 'sign_date', 'dn_date', 'item_code', 'specification',
            'request_qty', 'unit', 'packing', 'unit_price', 'total_price', 'rep_code', 'approver', 'approve_date'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:W1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }
}

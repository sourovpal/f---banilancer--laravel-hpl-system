<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DeliveryNoteReportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $from_date;
    protected $to_date;

    public function __construct($from_date, $to_date)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
    }

    public function collection()
    {
        // 构建查询
        $query = DB::table('delivery_notes')
            ->join('sales_orders', 'delivery_notes.so_id', '=', 'sales_orders.id')
            ->join('sales_order_items', function($join) {
                $join->on('sales_order_items.so_id', '=', 'sales_orders.id')
                     ->on('sales_order_items.dn_id', '=', 'delivery_notes.id');
            })
            ->join('items', 'sales_order_items.item_id', '=', 'items.id')
            ->select(
                'delivery_notes.no as DNNO',
                'delivery_notes.created_at as DNDate',
                'sales_orders.no as SONo',
                'items.code as ItemID',
                'sales_order_items.item_qty as DNQty'
            );

        // 应用日期过滤
        if (!empty($this->from_date)) {
            $query->whereDate('delivery_notes.sign_date', '>=', $this->from_date);
        }
        if (!empty($this->to_date)) {
            $query->whereDate('delivery_notes.sign_date', '<=', $this->to_date);
        }

        $data = $query->orderBy('delivery_notes.created_at', 'desc')->get();

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'DNNO',
            'DNDate',
            'SONo',
            'ItemID',
            'DNQty'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:E1'; // 调整范围到第1行
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(true);

                // 设置列宽自动调整
                foreach (range('A', 'E') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
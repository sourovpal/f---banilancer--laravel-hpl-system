<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GR_DN_Export implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function collection()
    {
        $data = collect();

        // 获取 good_receives 数据
        $good_receives = DB::table('good_receives')
            ->join('good_receive_items', 'good_receives.id', '=', 'good_receive_items.gr_id')
            ->join('items', 'good_receive_items.item_id', '=', 'items.id')
            ->select(
                'items.code as ItemID',
                DB::raw('DATE(good_receives.created_at) as TxDate'),
                'good_receive_items.item_qty as InQty',
                DB::raw('0 as OutQty'),
                'good_receives.created_at as DELI_DATE',
                DB::raw('(good_receive_items.item_qty * good_receive_items.item_cost) as Amount')
            )
            ->whereDate('good_receives.created_at', '=', $this->date)
            ->get();

        // 获取 delivery_notes 数据
        $delivery_notes = DB::table('delivery_notes')
            ->join('sales_orders', 'delivery_notes.so_id', '=', 'sales_orders.id')
            ->join('sales_order_items', 'sales_orders.id', '=', 'sales_order_items.so_id')
            ->join('items', 'sales_order_items.item_id', '=', 'items.id')
            ->select(
                'items.code as ItemID',
                DB::raw('DATE(delivery_notes.sign_date) as TxDate'),
                DB::raw('0 as InQty'),
                'sales_order_items.item_qty as OutQty',
                'delivery_notes.created_at as DELI_DATE',
                DB::raw('(sales_order_items.item_qty * items.price) as Amount')
            )
            ->whereDate('delivery_notes.sign_date', '=', $this->date)
            ->get();

        // 合并数据
        $mergedData = $good_receives->concat($delivery_notes);

        return $mergedData;
    }

    public function headings(): array
    {
        return [
            'ItemID',
            'TxDate',
            'InQty',
            'OutQty',
            'DELI_DATE',
            'Amount'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $cellRange = 'A1:F1'; // 调整列范围
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(true);

                // 自动调整列宽
                foreach (range('A', 'F') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
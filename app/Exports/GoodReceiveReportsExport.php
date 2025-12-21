<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GoodReceiveReportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
        $query = DB::table('good_receives')
            ->join('good_receive_items', 'good_receives.id', '=', 'good_receive_items.gr_id')
            ->join('items', 'good_receive_items.item_id', '=', 'items.id')
            ->join('suppliers', 'good_receives.sup_id', '=', 'suppliers.id')
            // 如果可能，左连接 purchase_orders 表（需要确认关联关系）
            // ->leftJoin('purchase_orders', 'good_receives.po_id', '=', 'purchase_orders.id')
            ->select(
                'good_receives.gr_no as GR_NO',
                'suppliers.englishname as Supplier',
                DB::raw('"" as RQ_NO'), // 无法获取，暂时留空
                DB::raw('"" as PO_Date'), // 无法获取，暂时留空
                'good_receives.created_at as DELI_DATE',
                'items.code as Product_Code',
                'items.specification as Description',
                'good_receive_items.item_qty as Request_Qty',
                'items.unit as Unit',
                'items.pack as Packing',
                'good_receive_items.item_cost as Unit_Price',
                DB::raw('(good_receive_items.item_qty * good_receive_items.item_cost) as Total_Price'),
                DB::raw('"" as HPL_PO_NO'), // 无法获取，暂时留空
                'items.location as HPL_Location',
                'good_receives.remarks as GR_Remarks',
                'good_receive_items.remarks as Item_Remarks'
            );

        // 应用日期过滤
        if (!empty($this->from_date)) {
            $query->whereDate('good_receives.created_at', '>=', $this->from_date);
        }
        if (!empty($this->to_date)) {
            $query->whereDate('good_receives.created_at', '<=', $this->to_date);
        }

        $data = $query->orderBy('good_receives.created_at', 'desc')->get();

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'GR NO',
            'Supplier',
            'RQ_NO',
            'PO Date',
            'DELI_DATE',
            'Product_Code',
            'Description',
            'Request_Qty',
            'Unit',
            'Packing',
            'Unit_Price',
            'Total_Price',
            'HPL PO NO',
            'HPL Location',
            'GR Remarks',
            'Item Remarks'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:P1'; // 调整列范围
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(true);

                // 自动调整列宽
                foreach (range('A', 'P') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
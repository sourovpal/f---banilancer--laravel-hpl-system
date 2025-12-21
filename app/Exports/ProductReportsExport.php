<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ProductReportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $product_code;

    public function __construct($product_code)
    {
        $this->product_code = $product_code;
    }

    public function collection()
    {
        $data = DB::table('sales_orders')
            ->join('sales_order_items', 'sales_orders.id', '=', 'sales_order_items.so_id')
            ->join('items', 'sales_order_items.item_id', '=', 'items.id')
            ->leftJoin('users as extuser', 'sales_orders.extuser_id', '=', 'extuser.id')
            ->leftJoin('users as appruser', 'sales_orders.appruser_id', '=', 'appruser.id')
            ->leftJoin('costcenters', 'sales_orders.cc_id', '=', 'costcenters.id')
            ->select(
                'sales_orders.no as Order_No',
                'extuser.username as User',
                'costcenters.name as Cost_Centre',
                'sales_orders.created_at as Order_Date',
                'sales_orders.request_date as Delivery_Date',
                'items.code as ItemID',
                'items.name as Description',
                'sales_order_items.item_qty as Request_Qty',
                'items.unit as Unit',
                'items.pack as Packing',
                'items.price as Unit_Price',
                DB::raw('(items.price * sales_order_items.item_qty) as Total_Price'),
                'items.gl as GL_No',
                'appruser.username as Approver',
                'sales_orders.appr_date as Approval_Date'
            )
            ->where('items.code', '=', $this->product_code)
            ->orderBy('sales_orders.no', 'asc')
            ->get()
            ->map(function ($item) {
                // 格式化日期
                $item->Order_Date = \Carbon\Carbon::parse($item->Order_Date)->format('Y-m-d');
                $item->Delivery_Date = \Carbon\Carbon::parse($item->Delivery_Date)->format('Y-m-d');
                $item->Approval_Date = $item->Approval_Date ? \Carbon\Carbon::parse($item->Approval_Date)->format('Y-m-d') : '';

                // 返回数组，并调整键名以匹配标题
                return [
                    'Order No' => $item->Order_No,
                    'User' => $item->User,
                    'Cost Centre' => $item->Cost_Centre,
                    'Order Date' => $item->Order_Date,
                    'Delivery Date' => $item->Delivery_Date,
                    'ItemID' => $item->ItemID,
                    'Description' => $item->Description,
                    'Request Qty' => $item->Request_Qty,
                    'Unit' => $item->Unit,
                    'Packing' => $item->Packing,
                    'Unit Price' => $item->Unit_Price,
                    'Total Price' => $item->Total_Price,
                    'GL No.' => $item->GL_No,
                    'Approver' => $item->Approver,
                    'Approval Date' => $item->Approval_Date,
                ];
            });

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'Order No',
            'User',
            'Cost Centre',
            'Order Date',
            'Delivery Date',
            'ItemID',
            'Description',
            'Request Qty',
            'Unit',
            'Packing',
            'Unit Price',
            'Total Price',
            'GL No.',
            'Approver',
            'Approval Date',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // 设置标题行字体大小和加粗
                $cellRange = 'A1:O1'; // 这里的 'O' 对应第 15 列，根据您的列数调整
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(true);

                // 可以根据需要设置列的格式和样式
                $event->sheet->getDelegate()->getStyle('A1:O' . $event->sheet->getDelegate()->getHighestRow())
                    ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            },
        ];
    }
}

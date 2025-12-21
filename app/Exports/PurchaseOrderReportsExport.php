<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class PurchaseOrderReportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
        $data = DB::table('purchase_orders')
            ->leftJoin('suppliers', 'purchase_orders.sup_id', '=', 'suppliers.id')
            ->leftJoin('users as extuser', 'purchase_orders.userint_id', '=', 'extuser.id')
            ->join('purchase_order_items', 'purchase_orders.id', '=', 'purchase_order_items.po_id')
            ->join('items', 'purchase_order_items.item_id', '=', 'items.id')
            ->select(
                'purchase_orders.po_no as Order_No',
                'extuser.username as User',
                'suppliers.englishname as Supplier',
                'purchase_orders.created_at as Order_Date',
                'items.code as ItemID',
                'items.name as Description',
                'purchase_order_items.item_qty as Quantity',
                'items.unit as Unit',
                'items.pack as Packing',
                'purchase_order_items.item_cost as Unit_Price',
                DB::raw('(purchase_order_items.item_qty * purchase_order_items.item_cost) as Total_Price'),
                'items.gl as GL_No',
                'purchase_orders.remarks as Remarks',
                'purchase_orders.status as Status'
            )
            ->when(!empty($this->from_date), function ($query) {
                $query->where('purchase_orders.created_at', '>=', $this->from_date . ' 00:00:00');
            })
            ->when(!empty($this->to_date), function ($query) {
                $query->where('purchase_orders.created_at', '<=', $this->to_date . ' 23:59:59');
            })
            ->orderBy('purchase_orders.po_no', 'asc')
            ->get()
            ->map(function ($item) {
                // 格式化日期
                $item->Order_Date = \Carbon\Carbon::parse($item->Order_Date)->format('Y-m-d');

                // 返回数组，并调整键名以匹配标题
                return [
                    'Order No' => $item->Order_No,
                    'User' => $item->User,
                    'Supplier' => $item->Supplier,
                    'Order Date' => $item->Order_Date,
                    'ItemID' => $item->ItemID,
                    'Description' => $item->Description,
                    'Quantity' => $item->Quantity,
                    'Unit' => $item->Unit,
                    'Packing' => $item->Packing,
                    'Unit Price' => $item->Unit_Price,
                    'Total Price' => $item->Total_Price,
                    'GL No.' => $item->GL_No,
                    'Remarks' => $item->Remarks,
                    'Status' => $item->Status,
                ];
            });

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'Order No',
            'User',
            'Supplier',
            'Order Date',
            'ItemID',
            'Description',
            'Quantity',
            'Unit',
            'Packing',
            'Unit Price',
            'Total Price',
            'GL No.',
            'Remarks',
            'Status',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // 设置标题行字体大小和加粗
                $cellRange = 'A1:N1'; // 根据您的列数调整，这里是14列
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(true);

                // 设置列的宽度自动调整
                foreach (range('A', 'N') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }

                // 可以根据需要设置更多样式
            },
        ];
    }
}

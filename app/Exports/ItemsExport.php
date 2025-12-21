<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;

class ItemsExport implements FromCollection
{
    public function collection()
    {
        return Item::all();
    }

	public function headings(): array
    {
        // 返回一个标题数组，每个元素对应你想在 Excel 首行显示的字段名
        return [
            'ID', 'Code', 'Name', 'Specification', 'Unit', 'Pack',
            'Category ID', 'Price', 'Cost', 'GL', 'Stock', 'Min Order',
            'Location', 'Image', 'Status', 'Created At', 'Updated At', 'Remark'
        ];
    }
}

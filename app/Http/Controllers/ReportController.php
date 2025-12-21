<?php

namespace App\Http\Controllers;

use App\Models\Costcenter;
use App\Exports\CostCenterReportsExport;
use App\Exports\DeliveryReportsExport;
use App\Exports\GoodReceiveReportsExport;
use App\Exports\ProductReportsExport;
use App\Exports\PurchaseOrderReportsExport;
use App\Exports\SalesOrderReportsExport;
use App\Exports\GR_DN_Export;
use App\Models\ExternalCompany;
use App\Models\InternalCompany;
use App\Models\Item;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this -> internalcompany = InternalCompany::all();
        $this -> externalcompany = ExternalCompany::all();
    }
    public function deliveryNote()
    {

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('reports.deliveryNote',compact('internalCompany','externalCompany'));
    }

    public function deliveryNoteExport(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        return Excel::download(new DeliveryNoteReportsExport($from_date, $to_date), 'delivery_note_report_' . date('YmdHis') . '.xlsx');
    }


    public function goodReceive()
    {

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('reports.goodReceive',compact('internalCompany','externalCompany'));
    }

    public function goodReceiveExport(Request $request)
    {
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        return Excel::download(new GoodReceiveReportsExport($from_date, $to_date), 'good_receive_report_' . date('YmdHis') . '.xlsx');
    }

	public function GR_DN()
    {
        $internalCompany = $this->internalcompany[0]->name ?? 'Internal Company';
        $externalCompany = $this->externalcompany[0]->name ?? 'External Company';

        return view('reports.GR_DN', compact('internalCompany', 'externalCompany'));
    }

    public function GR_DN_Export(Request $request)
    {
        $date = $request->input('date');

        return Excel::download(new GR_DN_Export($date), 'Gr_Dn_report_' . date('YmdHis') . '.xlsx');
    }

    public function costCenter()
	{
		$internalCompany = $this->internalcompany[0]->name;
		$externalCompany = $this->externalcompany[0]->name;
		$costCenter = Costcenter::orderBy('code', 'ASC')->get();

		return view('reports.costCenter', compact('costCenter', 'internalCompany', 'externalCompany'));
	}

	public function costCenterExport(Request $request)
		{
			$cc_id = $request->input('cc_id');

			// 您可以在此处添加调试信息
			// dd('Received cc_id:', $cc_id);

			return Excel::download(new CostCenterReportsExport($cc_id), 'costcenter_' . date('YmdHis') . '.xlsx');
		}

    public function saleOrder()
    {

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('reports.saleOrder',compact('internalCompany','externalCompany'));
    }

    public function saleOrderExport(Request $request)
    {
        return Excel::download(new SalesOrderReportsExport($request->from_date,$request->to_date), 'sale_order' . date('Ymdhms') . '.xlsx');
    }

    public function purchaseOrder()
    {
        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('reports.purchaseOrder',compact('internalCompany','externalCompany'));
    }

    public function purchaseOrderExport(Request $request)
    {
        return Excel::download(new PurchaseOrderReportsExport($request->from_date,$request->to_date), 'purchase-order' . date('Ymdhms') . '.xlsx');
    }

    public function productCode()
    {
        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;
        $ItemCode = Item::distinct('code')->orderBy('Code', 'ASC')->get();

        return view('reports.productCode',compact('ItemCode','internalCompany','externalCompany'));
    }

    public function productCodeExport(Request $request)
    {
        return Excel::download(new ProductReportsExport($request->product_code), 'product-code' . date('Ymdhms') . '.xlsx');
    }

    public function byMonthYear()
    {
        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;
        $ItemCode = Item::distinct('code')->orderBy('Code', 'ASC')->get();

        return view('reports.byMonthYear', compact('ItemCode', 'internalCompany', 'externalCompany'));
    }

    public function byMonthYearExport(Request $request)
    {
        if($request->report_type == 'SO'){
            return Excel::download(new SalesOrderReportsExport($request->from_date, $request->to_date), 'sale_order' . date('Ymdhms') . '.xlsx');
        }
        elseif ($request->report_type == 'PO'){
            return Excel::download(new PurchaseOrderReportsExport($request->from_date, $request->to_date), 'purchase-order' . date('Ymdhms') . '.xlsx');
        }
        elseif ($request->report_type == 'QO'){
            return Excel::download(new GoodReceiveReportsExport($request->from_date, $request->to_date), 'goodreceivereport_' . date('Ymdhms') . '.xlsx');
        }
        elseif ($request->report_type == 'DN'){
            return Excel::download(new DeliveryReportsExport($request->from_date, $request->to_date), 'deliveryreport_' . date('Ymdhms') . '.xlsx');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Exports\PurchaseOrderReportsExport;
use App\Helpers\Helper;
use App\Models\ExternalCompany;
use App\Models\InternalCompany;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrderReport;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class PurchaseController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this->internalcompany = InternalCompany::all();
        $this->externalcompany = ExternalCompany::all();
    }

    public function purchaseorderList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Purchase Order"], ['name' => "Current Purchase Order List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $purchaseorder_objs = DB::table('purchase_orders')
            ->leftJoin('suppliers', 'purchase_orders.sup_id', '=', 'suppliers.id')
            ->leftJoin('users as intusers', 'purchase_orders.userint_id', '=', 'intusers.id')
            ->select('purchase_orders.*', 'intusers.username as intusername', 'suppliers.englishname as supplier')
            ->where('purchase_orders.status', 0)
            ->orderBy('purchase_orders.created_at', 'desc')  // 修改这里以按 created_at 倒序排序
            ->get();

        $purchaseorders = array();
        $i = 0;
        foreach ($purchaseorder_objs as $purchaseorder_obj) {
            if ($purchaseorder_obj->status == 0 && $purchaseorder_obj->po_no != '') {
                if (auth()->user()->role == 'external') continue;

                $purchaseorders[$i]['id'] = $purchaseorder_obj->id;
                $purchaseorders[$i]['po_no'] = $purchaseorder_obj->po_no;
                $purchaseorders[$i]['orderdate'] = $purchaseorder_obj->created_at;
                $purchaseorders[$i]['supplier'] = $purchaseorder_obj->supplier;
                $purchaseorders[$i]['intusername'] = $purchaseorder_obj->intusername;
                $purchaseorders[$i]['remarks'] = $purchaseorder_obj->remarks;
                $purchaseorders[$i]['status'] = $purchaseorder_obj->status;
                $purchaseorders[$i]['sup_id'] = $purchaseorder_obj->sup_id;
                $purchaseorders[$i]['userint_id'] = $purchaseorder_obj->userint_id;

                $purchaseorderitem_objs = DB::table('purchase_order_items')
                    ->leftJoin('items', 'purchase_order_items.item_id', '=', 'items.id')
                    ->select('purchase_order_items.item_qty as item_qty', 'purchase_order_items.remarks as remarks', 'items.*')
                    ->where('purchase_order_items.po_id', $purchaseorder_obj->id)
                    ->get();

                $purchaseorderitems = array();
                $j = 0;
                foreach ($purchaseorderitem_objs as $purchaseorderitem_obj) {
                    $purchaseorderitems[$j]['id'] = $purchaseorderitem_obj->id;
                    $purchaseorderitems[$j]['code'] = $purchaseorderitem_obj->code;
                    $purchaseorderitems[$j]['name'] = $purchaseorderitem_obj->name;
                    $purchaseorderitems[$j]['specification'] = $purchaseorderitem_obj->specification;
                    $purchaseorderitems[$j]['unit'] = $purchaseorderitem_obj->unit;
                    $purchaseorderitems[$j]['pack'] = $purchaseorderitem_obj->pack;
                    $purchaseorderitems[$j]['qty'] = $purchaseorderitem_obj->item_qty;
                    $purchaseorderitems[$j]['price'] = $purchaseorderitem_obj->price;
                    $purchaseorderitems[$j]['remarks'] = $purchaseorderitem_obj->remarks;
                    $j++;
                }
                $purchaseorders[$i]['purchaseorderitems'] = $purchaseorderitems;
            }
            $i++;
        }
        // dd($purchaseorders);

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-purchase-list', compact('pageConfigs', 'breadcrumbs', 'internalCompany', 'externalCompany', 'purchaseorders'));
    }

    public function purchaseorderCreate($id = 0)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Purchase Order"], ['name' => "Create Purchase Order Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        if (auth()->user()->role == 'external') return back();

        $date = date("Y-m-d");
        $data = ['date' => $date];

        //        if (!$id) {
        //            $purchaseorder = new PurchaseOrder();
        //            $purchaseorder -> userint_id = Auth::id();
        //            $purchaseorder -> status = 0;
        //            $purchaseorder -> save();
        //        }
        //        else {
        //            $purchaseorder = PurchaseOrder::find($id);
        //        }

        $suppliers = Supplier::orderBy('englishname', 'ASC')->get();

        $categories = Category::all();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-purchase-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'suppliers', 'data', 'categories'));
    }

    public function purchaseorderUpdate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Purchase Order"], ['name' => "Update Purchase Order Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        // $purchaseorder = PurchaseOrder::find($id);

        // $purchaseorder -> status = 1;

        // $purchaseorder -> save();

        // return redirect('/current-po');

        $date = date("Y-m-d");
        $purchaseorder_obj = DB::table('purchase_orders')
            ->leftJoin('suppliers', 'purchase_orders.sup_id', '=', 'suppliers.id')
            ->leftJoin('users as intusers', 'purchase_orders.userint_id', '=', 'intusers.id')
            ->select('purchase_orders.*', 'intusers.username as intusername', 'suppliers.code as supplier')
            ->where('purchase_orders.id', $id)
            ->get();

        $purchaseorder['id'] = $purchaseorder_obj[0]->id;
        $purchaseorder['po_no'] = $purchaseorder_obj[0]->po_no;
        $purchaseorder['orderdate'] = $purchaseorder_obj[0]->created_at;
        $purchaseorder['supplier'] = $purchaseorder_obj[0]->supplier;
        $purchaseorder['intusername'] = $purchaseorder_obj[0]->intusername;
        $purchaseorder['remarks'] = $purchaseorder_obj[0]->remarks;
        $purchaseorder['payment_term'] = $purchaseorder_obj[0]->payment_term;
        $purchaseorder['status'] = $purchaseorder_obj[0]->status;
        $purchaseorder['sup_id'] = $purchaseorder_obj[0]->sup_id;
        $purchaseorder['userint_id'] = $purchaseorder_obj[0]->userint_id;

        $purchaseorderitem_objs = DB::table('purchase_order_items')
            ->leftJoin('items', 'purchase_order_items.item_id', '=', 'items.id')
            ->select('purchase_order_items.item_qty as item_qty', 'purchase_order_items.remarks as remarks', 'items.*')
            ->where('purchase_order_items.po_id', $purchaseorder_obj[0]->id)
            ->get();

        $purchaseorderitems = array();
        $j = 0;
        foreach ($purchaseorderitem_objs as $purchaseorderitem_obj) {
            $purchaseorderitems[$j]['id'] = $purchaseorderitem_obj->id;
            $purchaseorderitems[$j]['code'] = $purchaseorderitem_obj->code;
            $purchaseorderitems[$j]['name'] = $purchaseorderitem_obj->name;
            $purchaseorderitems[$j]['specification'] = $purchaseorderitem_obj->specification;
            $purchaseorderitems[$j]['unit'] = $purchaseorderitem_obj->unit;
            $purchaseorderitems[$j]['pack'] = $purchaseorderitem_obj->pack;
            $purchaseorderitems[$j]['qty'] = $purchaseorderitem_obj->item_qty;
            $purchaseorderitems[$j]['price'] = $purchaseorderitem_obj->price;
            $purchaseorderitems[$j]['remark'] = $purchaseorderitem_obj->remarks;
            $j++;
        }
        $purchaseorder['purchaseorderitems'] = $purchaseorderitems;

        $data = ['date' => $date];

        $suppliers = Supplier::orderBy('englishname', 'ASC')->get();

        $categories = Category::all();

        return view('pages.page-purchase-update', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'data', 'suppliers', 'categories', 'purchaseorder'));
    }

    public function purchaseorderItemUpdate(Request $request)
    {
        $purchaseorderitem_objs = PurchaseOrderItem::where('purchase_order_items.item_id', $request->data["itemId"])
            ->where('purchase_order_items.po_id', $request->data["po_id"])
            ->first();
        if (isset($request->data["qty"])) {
            $purchaseorderitem_objs->item_qty = $request->data["qty"];
        }
        if (isset($request->data["remarks"])) {
            $purchaseorderitem_objs->remarks = $request->data["remarks"];
        }
        $purchaseorderitem_objs->save();
    }

    public function purchaseorderReport($status = "initial")
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Purchase Order"], ['name' => "Purchase Order Report"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        if (auth()->user()->role == 'external') return back();

        if ($status == 'initial') {
            $sql = 'select po.*, po.created_at po_date, u.username user, pi.id pi_id, s.code supplier, i.*, i.id item_id, i.code item_code, pi.item_qty request_qty from purchase_orders po
            left join purchase_order_items pi on po.id=pi.po_id
            left join items i on i.id=pi.item_id
            left join suppliers s on s.id=po.sup_id
            left join users u on u.id=po.userint_id';

            $purchaseorders_result = DB::select($sql);

            $rep_code = auth()->user()->id . '_' . date('Ymd');
            DB::table('purchase_order_reports')->where('rep_code', '=', $rep_code)->delete();

            $purchaseorders = array();
            $j = 0;
            foreach ($purchaseorders_result as $purchaseorders_item) {
                if ($purchaseorders_item->po_no) {
                    $purchaseorders[$j]['pi_id'] = $purchaseorders_item->pi_id;
                    $purchaseorders[$j]['po_no'] = $purchaseorders_item->po_no;
                    $purchaseorders[$j]['po_date'] = $purchaseorders_item->po_date;
                    $purchaseorders[$j]['user'] = $purchaseorders_item->user;
                    $purchaseorders[$j]['supplier'] = $purchaseorders_item->supplier;
                    $purchaseorders[$j]['item_code'] = $purchaseorders_item->code;
                    $purchaseorders[$j]['specification'] = $purchaseorders_item->specification;
                    $purchaseorders[$j]['request_qty'] = $purchaseorders_item->request_qty;
                    $purchaseorders[$j]['unit'] = $purchaseorders_item->unit;
                    $purchaseorders[$j]['packing'] = $purchaseorders_item->pack;
                    $purchaseorders[$j]['unit_price'] = $purchaseorders_item->price;
                    $purchaseorders[$j]['total_price'] = $purchaseorders_item->price * $purchaseorders_item->request_qty;

                    $purchaseorderreport = new PurchaseOrderReport();
                    $purchaseorderreport->pi_id = $purchaseorders_item->pi_id;
                    $purchaseorderreport->po_no = $purchaseorders_item->po_no;
                    $purchaseorderreport->po_date = $purchaseorders_item->po_date;
                    $purchaseorderreport->user = $purchaseorders_item->user;
                    $purchaseorderreport->supplier = $purchaseorders_item->supplier;
                    $purchaseorderreport->item_code = $purchaseorders_item->item_code;
                    $purchaseorderreport->specification = isset($purchaseorders_item->specification) ? $purchaseorders_item->specification : '';
                    $purchaseorderreport->request_qty = $purchaseorders_item->request_qty;
                    $purchaseorderreport->unit = $purchaseorders_item->unit;
                    $purchaseorderreport->packing = $purchaseorders_item->pack;
                    $purchaseorderreport->unit_price = $purchaseorders_item->price;
                    $purchaseorderreport->total_price = $purchaseorders_item->price * $purchaseorders_item->request_qty;
                    $purchaseorderreport->sup_id = $purchaseorders_item->sup_id;
                    $purchaseorderreport->item_id = $purchaseorders_item->item_id;
                    $purchaseorderreport->user_id = $purchaseorders_item->userint_id;
                    $purchaseorderreport->rep_code = auth()->user()->id . '_' . date('Ymd');
                    if (isset($purchaseorders_item->pi_id)) {
                        $purchaseorderreport->save();
                    }

                    $purchaseorders[$j]['rep_id'] = $purchaseorderreport->id;
                    $purchaseorders[$j]['rep_code'] = auth()->user()->id . '_' . date('Ymd');

                    $j++;
                }
            }
        } else {
            $rep_code = $status;
            $purchaseorders = PurchaseOrderReport::where('rep_code', $rep_code)->get();
        }

        $items = Item::all();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        $purchaseorder_objs = DB::table('purchase_orders')
            ->leftJoin('suppliers', 'purchase_orders.sup_id', '=', 'suppliers.id')
            ->leftJoin('users as intusers', 'purchase_orders.userint_id', '=', 'intusers.id')
            ->select('purchase_orders.*', 'intusers.username as intusername', 'suppliers.englishname as supplier')
            ->orderBy('created_at', 'desc')
            ->get();

        $purchaseorders_new = array();
        $i = 0;
        foreach ($purchaseorder_objs as $purchaseorder_obj) {
            $purchaseorders_new[$i]['id'] = $purchaseorder_obj->id;
            $purchaseorders_new[$i]['po_no'] = $purchaseorder_obj->po_no;
            $purchaseorders_new[$i]['orderdate'] = $purchaseorder_obj->created_at;
            $purchaseorders_new[$i]['supplier'] = $purchaseorder_obj->supplier;
            $purchaseorders_new[$i]['intusername'] = $purchaseorder_obj->intusername;
            $purchaseorders_new[$i]['remarks'] = $purchaseorder_obj->remarks;
            $purchaseorders_new[$i]['status'] = $purchaseorder_obj->status;
            $purchaseorders_new[$i]['sup_id'] = $purchaseorder_obj->sup_id;
            $purchaseorders_new[$i]['userint_id'] = $purchaseorder_obj->userint_id;

            $purchaseorderitem_objs = DB::table('purchase_order_items')
                ->leftJoin('items', 'purchase_order_items.item_id', '=', 'items.id')
                ->leftJoin('purchase_order_reports', 'purchase_order_items.id', '=', 'purchase_order_reports.pi_id')
                ->select('purchase_order_reports.id as po_re', 'purchase_order_items.item_qty as item_qty', 'purchase_order_items.remarks as remarks', 'items.*')
                ->where('purchase_order_items.po_id', $purchaseorder_obj->id)
                ->get();

            $purchaseorderitems = array();
            $j = 0;
            foreach ($purchaseorderitem_objs as $purchaseorderitem_obj) {
                $purchaseorderitems[$j]['id'] = $purchaseorderitem_obj->id;
                $purchaseorderitems[$j]['po_re'] = $purchaseorderitem_obj->po_re;
                $purchaseorderitems[$j]['name'] = $purchaseorderitem_obj->name;
                $purchaseorderitems[$j]['specification'] = $purchaseorderitem_obj->specification;
                $purchaseorderitems[$j]['unit'] = $purchaseorderitem_obj->unit;
                $purchaseorderitems[$j]['pack'] = $purchaseorderitem_obj->pack;
                $purchaseorderitems[$j]['qty'] = $purchaseorderitem_obj->item_qty;
                $purchaseorderitems[$j]['price'] = $purchaseorderitem_obj->price;
                $purchaseorderitems[$j]['remarks'] = $purchaseorderitem_obj->remarks;
                $j++;
            }
            $purchaseorders_new[$i]['purchaseorderitems'] = $purchaseorderitems;
            $i++;
        }

        return view('pages.page-purchase-report', compact('pageConfigs', 'purchaseorders_new', 'internalCompany', 'externalCompany', 'breadcrumbs', 'purchaseorders', 'items'));
    }

    public function poItems(Request $request)
    {
        $purchaseorderitem_objs = DB::table('purchase_order_items')
            ->leftJoin('items', 'purchase_order_items.item_id', '=', 'items.id')
            ->select('purchase_order_items.item_qty as item_qty', 'purchase_order_items.remarks as remarks', 'items.*')
            ->where('purchase_order_items.po_id', $request->data)
            ->get();

        $purchaseorderitems = array();
        $j = 0;
        foreach ($purchaseorderitem_objs as $purchaseorderitem_obj) {
            $purchaseorderitems[$j]['id'] = $purchaseorderitem_obj->id;
            $purchaseorderitems[$j]['code'] = $purchaseorderitem_obj->code;
            $purchaseorderitems[$j]['name'] = $purchaseorderitem_obj->name;
            $purchaseorderitems[$j]['specification'] = isset($purchaseorderitem_obj->specification) ? $purchaseorderitem_obj->specification : '';
            $purchaseorderitems[$j]['unit'] = $purchaseorderitem_obj->unit;
            $purchaseorderitems[$j]['pack'] = $purchaseorderitem_obj->pack;
            $purchaseorderitems[$j]['qty'] = $purchaseorderitem_obj->item_qty;
            $purchaseorderitems[$j]['price'] = $purchaseorderitem_obj->price;
            $purchaseorderitems[$j]['remark'] = $purchaseorderitem_obj->remarks;
            $j++;
        }
        $result = ['result' => 'success', 'items' => $purchaseorderitems];
        return response()->json($result);
    }

    public function savePOItems(Request $request)
    {
        $items = $request->items;
        $po_id = $request->po_id;
        $remarks = $request->remarks;
        $supplier = $request->supplier;
        $payment_term = $request->payment_term;

        //        dd( $items ,
        //            $po_id ,
        //            $remarks ,
        //            $supplier ,
        //            $payment_term,$request->itemId,
        //            $request->qty,
        //            $request->remark);

        $purchaseorder = PurchaseOrder::find($po_id);
        $purchaseorder->sup_id = $supplier;
        $purchaseorder->remarks = $remarks;
        $purchaseorder->payment_term = $payment_term;

        if ($request->has('status')) {
            $purchaseorder->status = $request->status;
        }
        $purchaseorder->save();

        if (isset($items)) {
            for ($i = 0; $i < count($items); $i++) {
                if ($items[$i]['qty']) {
                    $purchaseorderitem = new PurchaseOrderItem();
                    $purchaseorderitem->po_id = $po_id;
                    $purchaseorderitem->sup_id = $supplier;
                    $purchaseorderitem->item_id = $items[$i]['id'];
                    $purchaseorderitem->item_qty = $items[$i]['qty'];
                    $purchaseorderitem->item_cost = $items[$i]['price'];
                    $purchaseorderitem->remarks = $items[$i]['remark'];
                    $purchaseorderitem->status = 0;
                    $exist = DB::table('purchase_order_items')->where('item_id', $items[$i]['id'])->where('po_id', $po_id)->count();
                    if ($exist <= 0) {
                        $purchaseorderitem->save();
                    }
                }
            }
        }

        if (isset($request->itemId) && count($request->itemId) > 0) {
            for ($i = 0; $i < count($request->itemId); $i++) {
                $purchaseorderitem_objs = PurchaseOrderItem::where('purchase_order_items.item_id', $request->itemId[$i])
                    ->where('purchase_order_items.po_id', $po_id)
                    ->first();
                if (isset($purchaseorderitem_objs) && (isset($request->qty[$i]) || isset($request->remark[$i]))) {
                    if (isset($request->qty[$i])) {
                        $purchaseorderitem_objs->item_qty = $request->qty[$i];
                    }
                    if (isset($request->remark[$i])) {
                        $purchaseorderitem_objs->remarks = $request->remark[$i];
                    }
                    $purchaseorderitem_objs->save();
                }
            }
        }

        //if remove items
        if ($request->remove_po_item && isset($request->remove_po_item)) {
            $remove_po_items_array = explode(",", $request->remove_po_item);
            if (count($remove_po_items_array) > 0) {
                for ($i = 0; $i < count($remove_po_items_array); $i++) {
                    DB::table('purchase_order_items')->where('item_id', $remove_po_items_array[$i])->delete();
                    // dd(count($remove_po_items_array),$remove_po_items_array,$request->remove_po_item);
                }
            }
        }


        $result = ['result' => 'success'];

        return response()->json($result);
    }

    public function createPurchaseOrder(Request $request)
    {
        $supplier = $request->supplier;
        $remarks = $request->remarks;
        $payment_term = $request->payment_term;
        $items = $request->items;

        $purchaseorder = new PurchaseOrder();
        $purchaseorder->userint_id = Auth::id();
        $purchaseorder->status = 0;

        $podate = date('ymd');
        $purchaseorder->sup_id = $supplier;
        $purchaseorder->remarks = $remarks;
        $purchaseorder->payment_term = $payment_term;
        if ($request->has('status')) {
            $purchaseorder->status = $request->status;
        }

        if ($purchaseorder->save()) {
            $po_id = $purchaseorder->id;
            $purchaseorder->po_no = Helper::getSerialNumber($purchaseorder->getTable(), 'po_no', 'PO-');
            $purchaseorder->save();

            DB::table('purchase_order_items')->where('po_id', $po_id)->delete();

            for ($i = 0; $i < count($items); $i++) {
                if ($items[$i]['qty']) {
                    $purchaseorderitem = new PurchaseOrderItem();
                    $purchaseorderitem->po_id = $po_id;
                    $purchaseorderitem->sup_id = $supplier;
                    $purchaseorderitem->item_id = $items[$i]['id'];
                    $purchaseorderitem->item_qty = $items[$i]['qty'];
                    $purchaseorderitem->item_cost = $items[$i]['price'];
                    $purchaseorderitem->remarks = $items[$i]['remark'];
                    $purchaseorderitem->status = 0;

                    $purchaseorderitem->save();
                }
            }
        }


        $result = ['result' => 'success'];

        return response()->json($result);
    }

    public function purchaseorderRegister(Request $request)
    {
        //        $supplier = $request->supplier;
        //        $remarks = $request->remarks;
        //        $payment_term = $request->payment_term;
        //        $po_id = $request->po_id;
        //
        //        $purchaseorder = PurchaseOrder::find($po_id);
        //
        //        $podate = date('Ymd');
        //        $code = '';
        //        if ($po_id / 10 < 1) $code = '000' . $po_id;
        //        else if ($po_id / 100 < 1) $code = '00' . $po_id;
        //        else if ($po_id / 1000 < 1) $code = '0' . $po_id;
        //        else $code = $po_id;
        //
        //        $purchaseorder->po_no = 'CS-PO-' . $podate . $code;
        //        $purchaseorder->sup_id = $supplier;
        //        $purchaseorder->remarks = $remarks;
        //        $purchaseorder->payment_term = $payment_term;
        //
        //        if ($request->has('status')) {
        //            $purchaseorder->status = $request->status;
        //        }
        //
        //        $purchaseorder->save();
        //
        //        // dd($request->itemId,$request->qty,$request->remark);
        //        if (isset($request->itemId) && count($request->itemId) > 0) {
        //            for ($i = 0; $i < count($request->itemId); $i++) {
        //                $purchaseorderitem_objs = PurchaseOrderItem::where('purchase_order_items.item_id', $request->itemId[$i])
        //                    ->where('purchase_order_items.po_id', $po_id)
        //                    ->first();
        //                if (isset($request->qty[$i])) {
        //                    $purchaseorderitem_objs->item_qty = $request->qty[$i];
        //                }
        //                if (isset($request->remark[$i])) {
        //                    $purchaseorderitem_objs->remarks = $request->remark[$i];
        //                }
        //                $purchaseorderitem_objs->save();
        //            }
        //        }
        //
        //          //if remove items
        //          if ($request->remove_po_item && isset($request->remove_po_item)) {
        //            $remove_po_items_array = explode(",", $request->remove_po_item);
        //            if (count($remove_po_items_array) > 0) {
        //                for ($i = 0; $i < count($remove_po_items_array); $i++) {
        //                    DB::table('sales_order_items')->where('item_id', $remove_po_items_array[$i])->delete();
        //                    // dd(count($remove_po_items_array),$remove_po_items_array,$request->remove_po_item);
        //                }
        //            }
        //        }

        return redirect('/current-po');
    }

    public function getReports(Request $request)
    {
        $item = $request->item;
        $po_from = $request->po_from;
        $po_to = $request->po_to;

        $sql = 'select * from purchase_order_reports where rep_code = "' . auth()->user()->id . '_' . date('Ymd') . '"';

        if ($item) $sql .= ' and item_id = ' . $item;

        $purchaseorders_result = DB::select($sql);

        $purchaseorders = array();
        $j = 0;
        foreach ($purchaseorders_result as $purchaseorders_item) {
            $orderdate_array = explode(' ', $purchaseorders_item->po_date);
            $orderdate = $orderdate_array[0];

            $orderdatefrom_flag = 1;
            $orderdateto_flag = 1;

            if ($po_from != '') {
                $po_from_array = explode('/', $po_from);
                $po_from_text = $po_from_array[2] . '-' . $po_from_array[1] . '-' . $po_from_array[0];

                if ($po_from_text <= $orderdate) $orderdatefrom_flag = 1;
                else $orderdatefrom_flag = 0;
            }

            if ($po_to != '') {
                $po_to_array = explode('/', $po_to);
                $po_to_text = $po_to_array[2] . '-' . $po_to_array[1] . '-' . $po_to_array[0];

                if ($po_to_text >= $orderdate) $orderdateto_flag = 1;
                else $orderdateto_flag = 0;
            }

            if ($orderdatefrom_flag && $orderdateto_flag) {
                $purchaseorders[$j]['id'] = $purchaseorders_item->id;
                $purchaseorders[$j]['po_no'] = $purchaseorders_item->po_no;
                $purchaseorders[$j]['user'] = $purchaseorders_item->user;
                $purchaseorders[$j]['supplier'] = $purchaseorders_item->supplier;
                $purchaseorders[$j]['po_date'] = $purchaseorders_item->po_date;
                $purchaseorders[$j]['item_code'] = $purchaseorders_item->item_code;
                $purchaseorders[$j]['specification'] = $purchaseorders_item->specification;
                $purchaseorders[$j]['request_qty'] = $purchaseorders_item->request_qty;
                $purchaseorders[$j]['unit'] = $purchaseorders_item->unit;
                $purchaseorders[$j]['packing'] = $purchaseorders_item->packing;
                $purchaseorders[$j]['unit_price'] = $purchaseorders_item->unit_price;
                $purchaseorders[$j]['total_price'] = $purchaseorders_item->total_price;
                $j++;
            }
        }

        return response()->json($purchaseorders);
    }

    public function printPdf($id)
    {
        $sql = "select po.*, u.telephone tel, u.userid user, sp.*, sp.englishaddress, sp.englishname supplier, po.created_at
				from purchase_orders po
				left join users u on u.id=po.userint_id
				left join suppliers sp on sp.id=po.sup_id
				where po.id=" . $id;
        $purchaseorderreports = DB::select($sql);

        $sql = "select soi.item_qty qty, i.*, soi.remarks from purchase_order_items soi
				left join items i on i.id=soi.item_id
				where soi.po_id = " . $id;
        $items = DB::select($sql);

        $internalcompany = InternalCompany::all();
        $externalcompany = ExternalCompany::all();

        if (!empty($purchaseorderreports)) {
            $supplierName = $purchaseorderreports[0]->supplier;  // 获取供应商英文名
        } else {
            $supplierName = 'UnknownSupplier';  // 如果没有获取到供应商名称，使用默认值
        }

        $data = [
            'title' => 'Purchase Order Report',
            'heading' => 'Purchase Order Report',
            'content' => $purchaseorderreports[0],
            'internalcompany' => $internalcompany[0],
            'external
			company' => $externalcompany[0],
            'items' => $items
        ];

        $pdf = PDF::setOptions(['images' => true, 'isRemoteEnabled' => true])
            ->loadView('pdf.purchaseorder_pdf', $data)
            ->setPaper('a4', 'portrait');

        $filename = 'PO_' . $supplierName . '_' . date('YmdHis') . '.pdf';  // 构建文件名
        return $pdf->download($filename);  // 使用动态文件名
    }

    public function printExcel()
    {
        return Excel::download(new PurchaseOrderReportsExport, 'purchaseorderreport_' . date('Ymdhms') . '.xlsx');
    }
}

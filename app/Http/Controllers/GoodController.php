<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use App\Models\GoodReceive;
use App\Models\GoodReceiveItem;
use App\Models\GoodReceiveReport;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Item;
use App\Models\ItemTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Exports\GoodReceiveReportsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\InternalCompany;
use App\Models\ExternalCompany;

class GoodController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this->internalcompany = InternalCompany::all();
        $this->externalcompany = ExternalCompany::all();
    }

    public function goodreceiverList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Good Receiver"], ['name' => "Current Good Receiver List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $goodreceive_objs = DB::table('good_receives')
            ->leftJoin('suppliers', 'good_receives.sup_id', '=', 'suppliers.id')
            ->leftJoin('users as intusers', 'good_receives.userint_id', '=', 'intusers.id')
            ->select('good_receives.*', 'intusers.username as intusername', 'suppliers.englishname as supplier')
            ->where('good_receives.status', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        //dd($goodreceive_objs);

        $goodreceives = array();
        $i = 0;
        foreach ($goodreceive_objs as $goodreceive_obj) {
            if ($goodreceive_obj->status == 0 && $goodreceive_obj->gr_no != '') {
                if (auth()->user()->role == 'external') continue;

                $goodreceives[$i]['id'] = $goodreceive_obj->id;
                $goodreceives[$i]['gr_no'] = $goodreceive_obj->gr_no;
                $goodreceives[$i]['gr_date'] = $goodreceive_obj->created_at;
                $goodreceives[$i]['supplier'] = $goodreceive_obj->supplier;
                $goodreceives[$i]['intusername'] = $goodreceive_obj->intusername;
                $goodreceives[$i]['remarks'] = $goodreceive_obj->remarks;
                $goodreceives[$i]['status'] = $goodreceive_obj->status;
                $goodreceives[$i]['sup_id'] = $goodreceive_obj->sup_id;
                $goodreceives[$i]['userint_id'] = $goodreceive_obj->userint_id;

                $goodreceiveitem_objs = DB::table('good_receive_items')
                    ->leftJoin('items', 'good_receive_items.item_id', '=', 'items.id')
                    ->select('good_receive_items.item_qty as item_qty', 'good_receive_items.item_cost as item_cost', 'good_receive_items.remarks as remarks', 'items.*')
                    ->where('good_receive_items.gr_id', $goodreceive_obj->id)
                    ->get();

                $goodreceiveitems = array();
                $j = 0;
                foreach ($goodreceiveitem_objs as $goodreceiveitem_obj) {
                    $goodreceiveitems[$j]['id'] = $goodreceiveitem_obj->id;
                    $goodreceiveitems[$j]['name'] = $goodreceiveitem_obj->name;
                    $goodreceiveitems[$j]['specification'] = $goodreceiveitem_obj->specification;
                    $goodreceiveitems[$j]['unit'] = $goodreceiveitem_obj->unit;
                    $goodreceiveitems[$j]['pack'] = $goodreceiveitem_obj->pack;
                    $goodreceiveitems[$j]['qty'] = $goodreceiveitem_obj->item_qty;
                    $goodreceiveitems[$j]['cost'] = $goodreceiveitem_obj->item_cost;
                    $goodreceiveitems[$j]['remarks'] = $goodreceiveitem_obj->remarks;
                    $j++;
                }
                $goodreceives[$i]['goodreceiveitems'] = $goodreceiveitems;
            }
            $i++;
        }

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-good-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'goodreceives'));
    }

    public function goodreceiverCreate(Request $request, $id)
    {

        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Good Receiver"], ['name' => "Create Good Receiver Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        if (auth()->user()->role == 'external') return back();

        $date = date("Y-m-d");
        $data = ['date' => $date];

        //        if (!$id) {
        //            $goodreceive = new GoodReceive();
        //            $goodreceive -> userint_id = Auth::id();
        //            $goodreceive -> status = 0;
        //            $goodreceive -> save();
        //        }
        //        else {
        //            $goodreceive = GoodReceive::find($id);
        //        }
        $purchaseOrder = PurchaseOrder::where('id', $request->pid)->first();
        $selectedSupplier = isset($purchaseOrder->sup_id) ? $purchaseOrder->sup_id : '';
        $purchaseorderitems = array();
        if (!empty($purchaseOrder)) {
            $purchaseorderitem_objs = DB::table('purchase_order_items')
                ->leftJoin('items', 'purchase_order_items.item_id', '=', 'items.id')
                ->select('purchase_order_items.item_qty as item_qty', 'purchase_order_items.remarks as remarks', 'items.*')
                ->where('purchase_order_items.po_id', $purchaseOrder->id)
                ->get();
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
        }
        $suppliers = Supplier::orderBy('englishname', 'ASC')->get();

        $categories = Category::all();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-good-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs',  'suppliers', 'data', 'categories', 'selectedSupplier', 'purchaseorderitems'));
    }

    public function createGoodsReceive(Request $request)
	{
		$supplier = $request->supplier;
		$remarks = $request->remarks;
		$gr_id = $request->gr_id;
		$items = $request->items;

		$goodreceive = new GoodReceive();
		$goodreceive->userint_id = Auth::id();
		$goodreceive->sup_id = $supplier;
		$goodreceive->remarks = $remarks;
		$goodreceive->status = 0;

		if ($goodreceive->save()) {
			$code = str_pad($gr_id, 4, '0', STR_PAD_LEFT);
			$goodreceive->gr_no = 'CS-GR-' . date('Ymd') . $code;
			$goodreceive->save();
			$gr_id = $goodreceive->id;

			DB::table('good_receive_items')->where('gr_id', $gr_id)->delete();

			foreach ($items as $item) {
				if ($item['qty']) {
					$goodreceiveitem = new GoodReceiveItem();
					$goodreceiveitem->gr_id = $gr_id;
					$goodreceiveitem->sup_id = $supplier;
					$goodreceiveitem->item_id = $item['id'];
					$goodreceiveitem->item_qty = $item['qty'];
					$goodreceiveitem->item_cost = $item['cost']; // 確保這裡是 'cost' 而不是 'price'
					$goodreceiveitem->remarks = $item['specification'];
					$goodreceiveitem->status = 0;
					$goodreceiveitem->save();

					$itemModel = Item::find($item['id']);
					$itemModel->stock += $item['qty'];
					$itemModel->save();
				}
			}

			return response()->json(['result' => 'success']);
		}
	}

    public function goodreceiverComplete($id)
    {
        $gr = GoodReceive::find($id);
        $gr->status = 1;
        $gr->save();

        return redirect('/current-good-receive-list');
    }

    public function goodreceiverCancel($id)
    {
        $gr = GoodReceive::find($id);
        $gr->status = 2;
        $gr->save();

        return redirect('/current-good-receive-list');
    }

    public function goodreceiverUpdate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Good Receiver"], ['name' => "Update Good Receiver Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $date = date("Y-m-d");
        $data = ['date' => $date];

        $gr_obj = DB::table('good_receives')
            ->leftJoin('suppliers', 'good_receives.sup_id', '=', 'suppliers.id')
            ->leftJoin('users as userint', 'good_receives.userint_id', '=', 'userint.id')
            ->select('good_receives.*', 'suppliers.id as supplier', 'userint.username as intusername')
            ->where('good_receives.id', $id)
            ->get();
        // dd($gr_obj);

        $gr['id'] = $gr_obj[0]->id;
        $gr['no'] = $gr_obj[0]->gr_no;
        $gr['grdate'] = $gr_obj[0]->created_at;
        $gr['supplier'] = $gr_obj[0]->supplier;
        $gr['intusername'] = $gr_obj[0]->intusername;
        $gr['remarks'] = $gr_obj[0]->remarks;
        $gr['status'] = $gr_obj[0]->status;

        $gritem_objs = DB::table('good_receive_items')
            ->leftJoin('items', 'good_receive_items.item_id', '=', 'items.id')
            ->select('good_receive_items.item_qty as item_qty', 'good_receive_items.item_cost as cost', 'good_receive_items.remarks as remarks', 'items.*')
            ->where('good_receive_items.gr_id', $gr_obj[0]->id)
            ->get();

        $gritems = array();
        $j = 0;
        foreach ($gritem_objs as $gritem_obj) {
            $gritems[$j]['id'] = $gritem_obj->id;
            $gritems[$j]['code'] = $gritem_obj->code;
            $gritems[$j]['name'] = $gritem_obj->name;
            $gritems[$j]['specification'] = $gritem_obj->specification;
            $gritems[$j]['unit'] = $gritem_obj->unit;
            $gritems[$j]['pack'] = $gritem_obj->pack;
            $gritems[$j]['qty'] = $gritem_obj->item_qty;
            $gritems[$j]['cost'] = $gritem_obj->cost;
            $gritems[$j]['remarks'] = $gritem_obj->remarks;
            $j++;
        }
        $gr['gritems'] = $gritems;

        $suppliers = Supplier::orderBy('englishname', 'ASC')->get();

        $categories = Category::all();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;
        //dd($gr);

        return view('pages.page-good-update', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'data', 'suppliers', 'categories', 'gr'));
    }

    public function goodreceiverReport($status)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Good Receiver"], ['name' => "Good Receiver Report"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        if (auth()->user()->role == 'external') return back();

        if ($status == 'initial') {
            $sql = 'select gr.*, gr.created_at gr_date, u.username user, gi.id gi_id, s.code supplier, i.*, i.id item_id, i.code item_code, gi.item_qty request_qty from good_receives gr
            left join good_receive_items gi on gr.id=gi.gr_id
            left join items i on i.id=gi.item_id
            left join suppliers s on s.id=gr.sup_id
            left join users u on u.id=gr.userint_id';

            $goodreceives_result = DB::select($sql);

            $rep_code = auth()->user()->id . '_' . date('Ymd');
            DB::table('good_receive_reports')->where('rep_code', '=', $rep_code)->delete();

            $goodreceives = array();
            $j = 0;
            foreach ($goodreceives_result as $goodreceives_item) {
                if ($goodreceives_item->gr_no && $goodreceives_item->gi_id) {
                    $goodreceives[$j]['gi_id'] = $goodreceives_item->gi_id;
                    $goodreceives[$j]['gr_no'] = $goodreceives_item->gr_no;
                    $goodreceives[$j]['gr_date'] = $goodreceives_item->gr_date;
                    $goodreceives[$j]['user'] = $goodreceives_item->user;
                    $goodreceives[$j]['supplier'] = $goodreceives_item->supplier;
                    $goodreceives[$j]['item_code'] = $goodreceives_item->code;
                    $goodreceives[$j]['specification'] = $goodreceives_item->specification;
                    $goodreceives[$j]['request_qty'] = $goodreceives_item->request_qty;
                    $goodreceives[$j]['unit'] = $goodreceives_item->unit;
                    $goodreceives[$j]['packing'] = $goodreceives_item->pack;
                    $goodreceives[$j]['unit_price'] = $goodreceives_item->price;
                    $goodreceives[$j]['total_price'] = $goodreceives_item->price * $goodreceives_item->request_qty;

                    $goodreceivereport = new GoodReceiveReport();
                    $goodreceivereport->gi_id = $goodreceives_item->gi_id;
                    $goodreceivereport->gr_no = $goodreceives_item->gr_no;
                    $goodreceivereport->gr_date = $goodreceives_item->gr_date;
                    $goodreceivereport->user = $goodreceives_item->user;
                    $goodreceivereport->supplier = $goodreceives_item->supplier;
                    $goodreceivereport->item_code = $goodreceives_item->item_code;
                    $goodreceivereport->specification = $goodreceives_item->specification ? $goodreceives_item->specification : '';
                    $goodreceivereport->request_qty = $goodreceives_item->request_qty;
                    $goodreceivereport->unit = $goodreceives_item->unit;
                    $goodreceivereport->packing = $goodreceives_item->pack;
                    $goodreceivereport->unit_price = $goodreceives_item->price;
                    $goodreceivereport->total_price = $goodreceives_item->price * $goodreceives_item->request_qty;
                    $goodreceivereport->sup_id = $goodreceives_item->sup_id;
                    $goodreceivereport->item_id = $goodreceives_item->item_id;
                    $goodreceivereport->user_id = $goodreceives_item->userint_id;
                    $goodreceivereport->rep_code = auth()->user()->id . '_' . date('Ymd');

                    $goodreceivereport->save();

                    $itemtransaction = new ItemTransaction();

                    $old_itemtransaction = ItemTransaction::where('tx_doc', $goodreceivereport->note_no)->where('item_id', $goodreceivereport->item_id)->get();
                    if (count($old_itemtransaction)) {
                        $itemtransaction = $old_itemtransaction[0];
                    }

                    $itemtransaction->tx_doc = $goodreceivereport->gr_no;
                    $itemtransaction->tx_type = 0;
                    $itemtransaction->supplier = $goodreceivereport->sup_id;
                    $itemtransaction->item_id = $goodreceivereport->item_id;
                    $itemtransaction->tx_out = 0;
                    $itemtransaction->tx_in = $goodreceivereport->request_qty;

                    $itemtransaction->save();

                    $goodreceivereport->itemtx_id = $itemtransaction->id;
                    $goodreceivereport->save();

                    $goodreceives[$j]['rep_id'] = $goodreceivereport->id;
                    $goodreceives[$j]['rep_code'] = auth()->user()->id . '_' . date('Ymd');

                    $j++;
                }
            }
        } else {
            $rep_code = $status;
            $goodreceives = GoodReceiveReport::where('rep_code', $rep_code)->get();
        }

        $items = Item::all();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        $goodreceive_objs = DB::table('good_receives')
            ->leftJoin('suppliers', 'good_receives.sup_id', '=', 'suppliers.id')
            ->leftJoin('users as intusers', 'good_receives.userint_id', '=', 'intusers.id')
            ->select('good_receives.*', 'intusers.username as intusername', 'suppliers.code as supplier')
            ->orderBy('good_receives.created_at', 'desc')
            ->get();

        $goodreceives_new = array();
        $i = 0;
        foreach ($goodreceive_objs as $goodreceive_obj) {
            $goodreceives_new[$i]['id'] = $goodreceive_obj->id;
            $goodreceives_new[$i]['gr_no'] = $goodreceive_obj->gr_no;
            $goodreceives_new[$i]['gr_date'] = $goodreceive_obj->created_at;
            $goodreceives_new[$i]['supplier'] = $goodreceive_obj->supplier;
            $goodreceives_new[$i]['intusername'] = $goodreceive_obj->intusername;
            $goodreceives_new[$i]['remarks'] = $goodreceive_obj->remarks;
            $goodreceives_new[$i]['status'] = $goodreceive_obj->status;
            $goodreceives_new[$i]['sup_id'] = $goodreceive_obj->sup_id;
            $goodreceives_new[$i]['userint_id'] = $goodreceive_obj->userint_id;

            $goodreceiveitem_objs = DB::table('good_receive_items')
                ->leftJoin('items', 'good_receive_items.item_id', '=', 'items.id')
                ->leftJoin('good_receive_reports', 'good_receive_items.id', '=', 'good_receive_reports.gi_id')
                ->select('good_receive_reports.id as gi_re', 'good_receive_items.item_qty as item_qty', 'good_receive_items.item_cost as item_cost', 'good_receive_items.remarks as remarks', 'items.*')
                ->where('good_receive_items.gr_id', $goodreceive_obj->id)
                ->where('good_receive_reports.rep_code', auth()->user()->id . '_' . date('Ymd'))
                ->get();

            $goodreceiveitems = array();
            $j = 0;
            foreach ($goodreceiveitem_objs as $goodreceiveitem_obj) {
                $goodreceiveitems[$j]['id'] = $goodreceiveitem_obj->id;
                $goodreceiveitems[$j]['gi_re'] = $goodreceiveitem_obj->gi_re;
                $goodreceiveitems[$j]['name'] = $goodreceiveitem_obj->name;
                $goodreceiveitems[$j]['specification'] = $goodreceiveitem_obj->specification;
                $goodreceiveitems[$j]['unit'] = $goodreceiveitem_obj->unit;
                $goodreceiveitems[$j]['pack'] = $goodreceiveitem_obj->pack;
                $goodreceiveitems[$j]['qty'] = $goodreceiveitem_obj->item_qty;
                $goodreceiveitems[$j]['cost'] = $goodreceiveitem_obj->item_cost;
                $goodreceiveitems[$j]['remarks'] = $goodreceiveitem_obj->remarks;
                $j++;
            }
            $goodreceives_new[$i]['goodreceiveitems'] = $goodreceiveitems;
            $i++;
        }

        return view('pages.page-good-report', compact('pageConfigs', 'goodreceives_new', 'internalCompany', 'externalCompany', 'breadcrumbs', 'goodreceives', 'items'));
    }

    public function goodreceiveSpecialhandling($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Good Receiver"], ['name' => "Good Receiver Special Handling"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $goodreceivereport = GoodReceiveReport::find($id);
        $goodreceiveitem = GoodReceiveItem::find($goodreceivereport->gi_id);
        $goodreceive = GoodReceive::find($goodreceiveitem->gr_id);

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-good-special-handling', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'goodreceivereport', 'goodreceive'));
    }

    public function goodreceivereportUpdate(Request $request)
    {
        $goodreceivereport = GoodReceiveReport::find($request->rep_id);

        $goodreceivereport->user = $request->user;
        $goodreceivereport->supplier = $request->supplier;
        $goodreceivereport->gr_date = $request->gr_date;
        $goodreceivereport->item_code = $request->item_code;
        $goodreceivereport->specification = $request->specification;
        $goodreceivereport->request_qty = $request->request_qty;
        $goodreceivereport->unit = $request->unit;
        $goodreceivereport->packing = $request->packing;
        $goodreceivereport->unit_price = $request->unit_price;
        $goodreceivereport->total_price = $request->total_price;

        $goodreceivereport->save();
        $goodreceiveitem = GoodReceiveItem::find($goodreceivereport->gi_id);
        $goodreceive = GoodReceive::find($goodreceiveitem->gr_id);

        if ($request->status == 1) {
            $itemtransaction = new ItemTransaction();

            $old_itemtransaction = ItemTransaction::where('tx_doc', $goodreceivereport->note_no)->where('item_id', $goodreceivereport->item_id)->get();
            if (count($old_itemtransaction)) {
                $itemtransaction = $old_itemtransaction[0];
            }

            $itemtransaction->tx_doc = $goodreceivereport->gr_no;
            $itemtransaction->tx_type = 0;
            $itemtransaction->supplier = $goodreceivereport->sup_id;
            $itemtransaction->item_id = $goodreceivereport->item_id;
            $itemtransaction->tx_out = 0;
            $itemtransaction->tx_in = $goodreceivereport->request_qty;

            $itemtransaction->save();

            $goodreceivereport->itemtx_id = $itemtransaction->id;
            $goodreceivereport->save();
        } else if ($request->status == 2) {
            $goodreceive->status = 2;
            $goodreceive->save();

            $itemtransaction_id = $goodreceivereport->itemtx_id;

            if ($itemtransaction_id) {
                $itemtransaction = ItemTransaction::find($itemtransaction_id);

                $itemtransaction->tx_out = 0;
                $itemtransaction->tx_in = 0;

                $itemtransaction->save();
            }
        }

        return redirect('/good-receive-report/' . $goodreceivereport->rep_code);
    }

    public function saveGRItems(Request $request)
    {
        $items = $request->items;
        $gr_id = $request->gr_id;
        $supplier = $request->supplier;
        $status = $request->status;
        $goodreceive = GoodReceive::where('id', $gr_id)->first();
        DB::table('good_receive_items')->where('gr_id', $gr_id)->delete();

        for ($i = 0; $i < count($items); $i++) {
            if ($items[$i]['qty']) {
                $goodreceiveitem = new GoodReceiveItem();
                $goodreceiveitem->gr_id = $gr_id;
                $goodreceiveitem->sup_id = $supplier;
                $goodreceiveitem->item_id = $items[$i]['id'];
                $goodreceiveitem->item_qty = $items[$i]['qty'];
                $goodreceiveitem->item_cost = $items[$i]['price'];
                $goodreceiveitem->remarks = $items[$i]['specification'];
                $goodreceiveitem->status = 0;

                $goodreceiveitem->save();

                $item = Item::find($items[$i]['id']);
                $item->stock = ($item->stock + $items[$i]['qty']);

                $item->save();
                if ($status) {
                    $itemtransaction = new ItemTransaction();

                    $itemtransaction->tx_doc = $goodreceive->gr_no;
                    $itemtransaction->tx_type = 0;
                    $itemtransaction->supplier = $goodreceive->sup_id;
                    $itemtransaction->item_id = $item['id'];
                    $itemtransaction->tx_out = 0;
                    $itemtransaction->tx_in = $items[$i]['qty'];

                    $itemtransaction->save();
                }
            }
        }

        $result = ['result' => 'success'];

        return response()->json($result);
    }

    public function goodreceiveRegister(Request $request)
    {
        $supplier = $request->supplier;
        $remarks = $request->remarks;
        $gr_id = $request->gr_id;
        $status = $request->status;

        $goodreceive = GoodReceive::find($gr_id);

        $grdate = date('Ymd');
        $code = '';
        if ($gr_id / 10 < 1) $code = '000' . $gr_id;
        else if ($gr_id / 100 < 1) $code = '00' . $gr_id;
        else if ($gr_id / 1000 < 1) $code = '0' . $gr_id;
        else $code = $gr_id;

        $goodreceive->gr_no = 'CS-GR-' . $grdate . $code;
        $goodreceive->sup_id = $supplier;
        $goodreceive->remarks = $remarks;
        $goodreceive->status = $status;

        $goodreceive->save();


        return redirect('/current-good-receive-list');
    }

    public function getReports(Request $request)
    {
        $item = $request->item;
        $gr_from = $request->gr_from;
        $gr_to = $request->gr_to;

        $sql = 'select * from good_receive_reports where rep_code = "' . auth()->user()->id . '_' . date('Ymd') . '"';

        if ($item) $sql .= ' and item_id = ' . $item;

        $goodreceives_result = DB::select($sql);

        $goodreceives = array();
        $j = 0;
        foreach ($goodreceives_result as $goodreceives_item) {
            $grdate_array = explode(' ', $goodreceives_item->gr_date);
            $grdate = $grdate_array[0];

            $grdatefrom_flag = 1;
            $grdateto_flag = 1;

            if ($gr_from != '') {
                $gr_from_array = explode('/', $gr_from);
                $gr_from_text = $gr_from_array[2] . '-' . $gr_from_array[1] . '-' . $gr_from_array[0];

                if ($gr_from_text <= $grdate) $grdatefrom_flag = 1;
                else $grdateto_flag = 0;
            }

            if ($gr_to != '') {
                $gr_to_array = explode('/', $gr_to);
                $gr_to_text = $gr_to_array[2] . '-' . $gr_to_array[1] . '-' . $gr_to_array[0];

                if ($gr_to_text >= $grdate) $grdatefrom_flag = 1;
                else $grdateto_flag = 0;
            }

            if ($grdatefrom_flag && $grdateto_flag) {
                $goodreceives[$j]['id'] = $goodreceives_item->id;
                $goodreceives[$j]['gr_no'] = $goodreceives_item->gr_no;
                $goodreceives[$j]['user'] = $goodreceives_item->user;
                $goodreceives[$j]['supplier'] = $goodreceives_item->supplier;
                $goodreceives[$j]['gr_date'] = $goodreceives_item->gr_date;
                $goodreceives[$j]['item_code'] = $goodreceives_item->item_code;
                $goodreceives[$j]['specification'] = $goodreceives_item->specification;
                $goodreceives[$j]['request_qty'] = $goodreceives_item->request_qty;
                $goodreceives[$j]['unit'] = $goodreceives_item->unit;
                $goodreceives[$j]['packing'] = $goodreceives_item->packing;
                $goodreceives[$j]['unit_price'] = $goodreceives_item->unit_price;
                $goodreceives[$j]['total_price'] = $goodreceives_item->total_price;
                $j++;
            }
        }

        return response()->json($goodreceives);
    }

    public function printPdf($id)
    {
        $sql = "select gr.*, u.telephone tel, u.userid user, sp.englishname supplier from good_receives gr left join users u on u.id=gr.userint_id left join suppliers sp on sp.id=gr.sup_id where gr.id=" . $id;
        $goodreceivereports = DB::select($sql);

        $sql = "select soi.item_qty qty, i.* from good_receive_items soi left join items i on i.id=soi.item_id where soi.gr_id = " . $id;
        $items = DB::select($sql);

        $internalcompany = InternalCompany::all();
        $externalcompany = ExternalCompany::all();

        $data = [
            'title' => 'Good Receive Report',
            'heading' => 'Good Receive Report',
            'content' => $goodreceivereports[0],
            'internalcompany' => $internalcompany[0],
            'externalcompany' => $externalcompany[0],
            'items' => $items
        ];

        $pdf = PDF::setOptions([
            'images' => true,
            'isRemoteEnabled' => true
        ])->loadView('pdf.goodreceive_pdf', $data)->setPaper('a4', 'portrait');;
        return $pdf->download('goodreceivereport_' . date('Ymdhms') . '.pdf');
    }

    public function printExcel()
    {
        return Excel::download(new GoodReceiveReportsExport('',''), 'goodreceivereport_' . date('Ymdhms') . '.xlsx');
    }
}

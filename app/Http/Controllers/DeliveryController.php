<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\DeliveryNote;
use App\Models\DeliveryNoteReport;
use Illuminate\Support\Facades\DB;
use App\Models\Costcenter;
use App\Models\SalesOrderItem;
use App\Models\Item;
use App\Models\Department;
use App\Models\User;
use App\Models\ItemTransaction;
use PDF;
use Illuminate\Http\Request;
use App\Exports\DeliveryNoteReportsExport;
use App\Helpers\Helper;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\InternalCompany;
use App\Models\ExternalCompany;

class DeliveryController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this->internalcompany = InternalCompany::all();
        $this->externalcompany = ExternalCompany::all();
    }

    public function deliverynoteList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Delivery Note"], ['name' => "Current Delivery Note List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        if (auth()->user()->role == 'external') return back();

        $deliverynote_objs = DB::table('delivery_notes')
            ->leftJoin('departments', 'delivery_notes.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'delivery_notes.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'delivery_notes.userext_id', '=', 'extusers.id')
            ->leftJoin('users as deliver', 'delivery_notes.userint_id', '=', 'deliver.id')
            ->select(
                'delivery_notes.*',
                'departments.name as department',
                'costcenters.name as costcentre',
                'extusers.username as extusername',
                'deliver.username as delivername'
            )
            ->where('delivery_notes.status', 0)
            ->orderBy('delivery_notes.created_at', 'DESC') // 添加排序條件
            ->get();

        $deliverynotes = array();
        $i = 0;
        foreach ($deliverynote_objs as $deliverynote_obj) {
            $deliverynotes[$i]['id'] = $deliverynote_obj->id;
            $deliverynotes[$i]['no'] = $deliverynote_obj->no;
            $deliverynotes[$i]['notedate'] = $deliverynote_obj->created_at;
            $deliverynotes[$i]['department'] = $deliverynote_obj->department;
            $deliverynotes[$i]['costcentre'] = $deliverynote_obj->costcentre;
            $deliverynotes[$i]['extusername'] = $deliverynote_obj->extusername;
            $deliverynotes[$i]['delivername'] = $deliverynote_obj->delivername;
            $deliverynotes[$i]['sign_date'] = $deliverynote_obj->sign_date;
            $deliverynotes[$i]['remarks'] = $deliverynote_obj->remarks;
            $deliverynotes[$i]['status'] = $deliverynote_obj->status;
            $deliverynotes[$i]['so_id'] = $deliverynote_obj->so_id;
            $deliverynotes[$i]['dep_id'] = $deliverynote_obj->dep_id;
            $deliverynotes[$i]['extuser_id'] = $deliverynote_obj->userext_id;

            $deliverynoteitems = array(); // 確保變量初始化
            if ($deliverynote_obj->so_id) {

                $deliverynoteitem_objs = DB::table('sales_order_items')
                    ->leftJoin('items', 'sales_order_items.item_id', '=', 'items.id')
                    ->select(
                        'sales_order_items.item_qty as item_qty',
                        'sales_order_items.remarks as remarks',
                        'items.*'
                    )
                    ->where('sales_order_items.so_id', $deliverynote_obj->so_id)
                    ->get();

                $j = 0;
                foreach ($deliverynoteitem_objs as $deliverynoteitem_obj) {
                    $deliverynoteitems[$j]['id'] = $deliverynoteitem_obj->id;
                    $deliverynoteitems[$j]['name'] = $deliverynoteitem_obj->name;
                    $deliverynoteitems[$j]['specification'] = $deliverynoteitem_obj->specification;
                    $deliverynoteitems[$j]['unit'] = $deliverynoteitem_obj->unit;
                    $deliverynoteitems[$j]['pack'] = $deliverynoteitem_obj->pack;
                    $deliverynoteitems[$j]['qty'] = $deliverynoteitem_obj->item_qty;
                    $deliverynoteitems[$j]['price'] = $deliverynoteitem_obj->price;
                    $deliverynoteitems[$j]['remarks'] = $deliverynoteitem_obj->remarks;
                    $j++;
                }
            } else if ($deliverynote_obj->qn_id) {

                $deliverynoteitem_objs = QuotationItem::where('qn_id', $deliverynote_obj->qn_id)->get();

                $j = 0;
                foreach ($deliverynoteitem_objs as $deliverynoteitem_obj) {
                    $deliverynoteitems[$j]['id'] = $deliverynoteitem_obj->id;
                    $deliverynoteitems[$j]['name'] = $deliverynoteitem_obj->name;
                    $deliverynoteitems[$j]['specification'] = $deliverynoteitem_obj->specification;
                    $deliverynoteitems[$j]['unit'] = $deliverynoteitem_obj->unit;
                    $deliverynoteitems[$j]['pack'] = $deliverynoteitem_obj->pack;
                    $deliverynoteitems[$j]['qty'] = $deliverynoteitem_obj->qty;
                    $deliverynoteitems[$j]['price'] = $deliverynoteitem_obj->price;
                    $deliverynoteitems[$j]['remarks'] = $deliverynoteitem_obj->remarks;
                    $j++;
                }
            }

            $deliverynotes[$i]['deliverynoteitems'] = $deliverynoteitems;
            $i++;
        }

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-delivery-list',  compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'deliverynotes'));
    }

    public function deliverynoteCreate()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Delivery Note"], ['name' => "Create Delivery Note Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-delivery-create', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);
    }

    public function deliverynoteUpdate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Delivery Note"], ['name' => "Update Delivery Note Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $deliverynote = DeliveryNote::find($id);

        $deliverynote->status = 1;

        $deliverynote->save();

        return redirect('/current-dn');
    }

    public function deliverynoteReport(Request $request, $status = "initial")
    {
        $to    = '';
        $from    = '';

        if ($request->has('from_date')) {
            if ($request->get('from_date')) {
                $from = date('Y-m-d', strtotime($request->get('from_date')));
            }
        }

        if ($request->has('to_date')) {
            if ($request->get('to_date')) {
                $to = date('Y-m-d', strtotime($request->get('to_date')));
            }
        }

        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Delivery Note"], ['name' => "Delivery Note Report"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        if (auth()->user()->role == 'external') return back();

        if ($status == 'initial') {
            $sql = 'select si.id id, dn.id dn_id, dn.no note_no, dn.created_at dn_date, dn.sign_date sign_date, so.id so_id, so.no order_no, uu.username user, c.name costcentre, so.created_at order_date, i.code item_code, i.specification specification, si.item_qty request_qty, i.unit unit, i.pack packing, i.price unit_price, u.username approver, so.appr_date approve_date, so.dn_id dn_id, so.dn_no dn_no, so.dn_date dn_date, so.cc_id cc_id, i.id item_id from delivery_notes dn
            left join sales_orders so on dn.so_id=so.id
            left join sales_order_items si on so.id=si.so_id
            left join items i on i.id=si.item_id
            left join costcenters c on c.id=so.cc_id
            left join users u on u.id=so.appruser_id
            left join users uu on uu.id=so.extuser_id
            where dn.no IS NOT NULL
            AND dn.so_id IS NOT NULL';
            if ($from > 0 && $to > 0) {
                $sql .= ' AND dn.sign_date >=' . $from . ' AND dn.sign_date <= ' . $to;
            }

            $deliverynotes_result_so = DB::select($sql);

            $sql = 'select qi.id id, dn.id dn_id, dn.no note_no, dn.created_at dn_date, dn.sign_date sign_date, qn.id qn_id, qn.code order_no, uu.username user, c.name costcentre, qn.created_at order_date, qi.specification specification, qi.qty request_qty, qi.unit unit, qi.pack packing, qi.price unit_price, u.username approver, qn.appr_date approve_date, qn.dn_id dn_id, qn.dn_no dn_no, qn.dn_date dn_date, qn.cc_id cc_id, qi.id item_id, qi.id item_code from delivery_notes dn
            left join quotations qn on dn.qn_id=qn.id
            left join quotation_items qi on qn.id=qi.qn_id
            left join costcenters c on c.id=qn.cc_id
            left join users u on u.id=qn.appruser_id
            left join users uu on uu.id=qn.userext_id
            where dn.no IS NOT NULL
            AND dn.qn_id IS NOT NULL';
            if ($from > 0 && $to > 0) {
                $sql .= ' AND dn.sign_date >=' . $from . ' AND dn.sign_date <= ' . $to;
            }

            $deliverynotes_result_qn = DB::select($sql);

            $deliverynotes_result = collect();
            $deliverynotes_result = $deliverynotes_result->merge($deliverynotes_result_so);
            $deliverynotes_result = $deliverynotes_result->merge($deliverynotes_result_qn);

            $rep_code = auth()->user()->id . '_' . date('Ymd');
            DB::table('delivery_note_reports')->where('rep_code', '=', $rep_code)->delete();

            $deliverynotes = array();
            $j = 0;
            foreach ($deliverynotes_result as $deliverynotes_item) {
                $deliverynotes[$j]['id'] = $deliverynotes_item->id;
                $deliverynotes[$j]['note_no'] = $deliverynotes_item->note_no;
                $deliverynotes[$j]['user'] = $deliverynotes_item->user;
                $deliverynotes[$j]['costcentre'] = $deliverynotes_item->costcentre;
                $deliverynotes[$j]['dn_date'] = $deliverynotes_item->dn_date;
                $deliverynotes[$j]['sign_date'] = $deliverynotes_item->sign_date;
                $deliverynotes[$j]['item_code'] = $deliverynotes_item->item_code;
                $deliverynotes[$j]['specification'] = $deliverynotes_item->specification;
                $deliverynotes[$j]['request_qty'] = $deliverynotes_item->request_qty;
                $deliverynotes[$j]['unit'] = $deliverynotes_item->unit;
                $deliverynotes[$j]['packing'] = $deliverynotes_item->packing;
                $deliverynotes[$j]['unit_price'] = $deliverynotes_item->unit_price;
                $deliverynotes[$j]['total_price'] = $deliverynotes_item->unit_price * $deliverynotes_item->request_qty;
                if (isset($deliverynotes_item->so_id)) {
                    $deliverynotes[$j]['so_id'] = $deliverynotes_item->so_id;
                    $deliverynotes[$j]['so_no'] = $deliverynotes_item->order_no;
                } else if (isset($deliverynotes_item->qn_id)) {
                    $deliverynotes[$j]['qn_id'] = $deliverynotes_item->qn_id;
                    $deliverynotes[$j]['qn_no'] = $deliverynotes_item->order_no;
                }
                $deliverynotes[$j]['dn_id'] = $deliverynotes_item->dn_id;
                $deliverynotes[$j]['approver'] = $deliverynotes_item->approver;
                $deliverynotes[$j]['approve_date'] = $deliverynotes_item->approve_date;

                $deliverynotereport = new DeliveryNoteReport();
                if (isset($deliverynotes_item->so_id)) {
                    $deliverynotereport->si_id = $deliverynotes_item->id;
                } else if (isset($deliverynotes_item->qn_id)) {
                    $deliverynotereport->qi_id = $deliverynotes_item->id;
                }
                $deliverynotereport->note_no = $deliverynotes_item->note_no;
                $deliverynotereport->user = $deliverynotes_item->user;
                $deliverynotereport->costcentre = $deliverynotes_item->costcentre;
                $deliverynotereport->dn_date = $deliverynotes_item->dn_date;
                $deliverynotereport->sign_date = $deliverynotes_item->sign_date;
                $deliverynotereport->item_code = $deliverynotes_item->item_code;
                $deliverynotereport->specification = isset($deliverynotes_item->specification) ? $deliverynotes_item->specification : '';
                $deliverynotereport->request_qty = $deliverynotes_item->request_qty;
                $deliverynotereport->unit = $deliverynotes_item->unit;
                $deliverynotereport->packing = $deliverynotes_item->packing;
                $deliverynotereport->unit_price = $deliverynotes_item->unit_price;
                $deliverynotereport->total_price = $deliverynotes_item->unit_price * $deliverynotes_item->request_qty;
                $deliverynotereport->dn_id = $deliverynotes_item->dn_id;
                if (isset($deliverynotes_item->so_id)) {
                    $deliverynotereport->so_id = $deliverynotes_item->so_id;
                    $deliverynotereport->so_no = $deliverynotes_item->order_no;
                } else if (isset($deliverynotes_item->qn_id)) {
                    $deliverynotereport->qn_id = $deliverynotes_item->qn_id;
                    $deliverynotereport->qn_no = $deliverynotes_item->order_no;
                }
                $deliverynotereport->cc_id = $deliverynotes_item->cc_id;
                $deliverynotereport->item_id = $deliverynotes_item->item_id;
                $deliverynotereport->approver = $deliverynotes_item->approver;
                $deliverynotereport->approve_date = $deliverynotes_item->approve_date;
                $deliverynotereport->rep_code = auth()->user()->id . '_' . date('Ymd');

                $deliverynotereport->save();

                $deliverynotes[$j]['rep_id'] = $deliverynotereport->id;
                $deliverynotes[$j]['rep_code'] = auth()->user()->id . '_' . date('Ymd');


                if (isset($deliverynotes_item->so_id)) {
                    $itemtransaction;

                    $old_itemtransaction = ItemTransaction::where('tx_doc', $deliverynotereport->note_no)->where('item_id', $deliverynotereport->item_id)->get();

                    if (count($old_itemtransaction)) {
                        $itemtransaction = $old_itemtransaction[0];
                    } else {
                        $itemtransaction = new ItemTransaction();
                    }
                    $itemtransaction->tx_doc = $deliverynotereport->note_no;
                    $itemtransaction->supplier = 0;
                    $itemtransaction->tx_type = 1;
                    $itemtransaction->item_id = $deliverynotereport->item_id;
                    $itemtransaction->tx_out = $deliverynotereport->request_qty;
                    $itemtransaction->tx_in = 0;

                    $itemtransaction->save();

                    $deliverynotereport->itemtx_id = $itemtransaction->id;
                    $deliverynotereport->save();
                }

                $j++;
            }
        } else {
            $rep_code = $status;
            $deliverynotes = DeliveryNoteReport::where('rep_code', $rep_code)->get();
        }

        $costcenters = Costcenter::all();

        $items = Item::all();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        $deliverynote_objs = DB::table('delivery_notes')
            ->leftJoin('departments', 'delivery_notes.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'delivery_notes.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'delivery_notes.userext_id', '=', 'extusers.id')
            ->leftJoin('users as deliver', 'delivery_notes.userint_id', '=', 'deliver.id')
            ->select('delivery_notes.*', 'departments.name as department', 'costcenters.code as costcentre', 'extusers.username as extusername', 'deliver.username as delivername')
            ->when($request->get('from_date'), function ($query, $formDate) {
                $query->where('delivery_notes.sign_date', '>=', $formDate);
            })
            ->when($request->get('to_date'), function ($query, $toDate) {
                $query->where('delivery_notes.sign_date', '<=', date('Y-m-d' . ' 11:45:00', strtotime($toDate)));
            })
            ->orderBy('delivery_notes.sign_date', 'desc')
            ->get();

        $deliverynotes_new = array();
        $i = 0;
        foreach ($deliverynote_objs as $deliverynote_obj) {
            $deliverynotes_new[$i]['id'] = $deliverynote_obj->id;
            $deliverynotes_new[$i]['no'] = $deliverynote_obj->no;
            $deliverynotes_new[$i]['notedate'] = $deliverynote_obj->created_at;
            $deliverynotes_new[$i]['department'] = $deliverynote_obj->department;
            $deliverynotes_new[$i]['costcentre'] = $deliverynote_obj->costcentre;
            $deliverynotes_new[$i]['extusername'] = $deliverynote_obj->extusername;
            $deliverynotes_new[$i]['delivername'] = $deliverynote_obj->delivername;
            $deliverynotes_new[$i]['sign_date'] = $deliverynote_obj->sign_date;
            $deliverynotes_new[$i]['remarks'] = $deliverynote_obj->remarks;
            $deliverynotes_new[$i]['status'] = $deliverynote_obj->status;
            $deliverynotes_new[$i]['so_id'] = $deliverynote_obj->so_id;
            $deliverynotes_new[$i]['dep_id'] = $deliverynote_obj->dep_id;
            $deliverynotes_new[$i]['extuser_id'] = $deliverynote_obj->userext_id;

            if ($deliverynote_obj->so_id) {
                $deliverynoteitem_objs = DB::table('sales_order_items')
                    ->leftJoin('items', 'sales_order_items.item_id', '=', 'items.id')
                    ->leftJoin('delivery_note_reports', 'delivery_note_reports.si_id', '=', 'sales_order_items.id')
                    ->select('delivery_note_reports.id as dn_re', 'sales_order_items.item_qty as item_qty', 'sales_order_items.remarks as remarks', 'items.*')
                    ->where('sales_order_items.so_id', $deliverynote_obj->so_id)
                    ->where('delivery_note_reports.rep_code', auth()->user()->id . '_' . date('Ymd'))
                    ->get();

                $deliverynoteitems = array();
                $j = 0;
                foreach ($deliverynoteitem_objs as $deliverynoteitem_obj) {
                    $deliverynoteitems[$j]['id'] = $deliverynoteitem_obj->id;
                    $deliverynoteitems[$j]['name'] = $deliverynoteitem_obj->name;
                    $deliverynoteitems[$j]['specification'] = isset($deliverynoteitem_obj->specification) ? $deliverynoteitem_obj->specification : '';
                    $deliverynoteitems[$j]['unit'] = $deliverynoteitem_obj->unit;
                    $deliverynoteitems[$j]['pack'] = $deliverynoteitem_obj->pack;
                    $deliverynoteitems[$j]['qty'] = $deliverynoteitem_obj->item_qty;
                    $deliverynoteitems[$j]['price'] = $deliverynoteitem_obj->price;
                    $deliverynoteitems[$j]['remarks'] = $deliverynoteitem_obj->remarks;
                    $deliverynoteitems[$j]['dn_re'] = $deliverynoteitem_obj->dn_re;
                    $j++;
                }
            } else if ($deliverynote_obj->qn_id) {
                $deliverynoteitem_objs = QuotationItem::where('qn_id', $deliverynote_obj->qn_id)->get();

                // $deliverynoteitem_objs = DB::table('sales_order_items')
                //     ->leftJoin('items', 'sales_order_items.item_id', '=', 'items.id')
                //     ->leftJoin('delivery_note_reports', 'delivery_note_reports.si_id', '=', 'sales_order_items.id')
                //     ->select('delivery_note_reports.id as dn_re', 'sales_order_items.item_qty as item_qty', 'sales_order_items.remarks as remarks', 'items.*')
                //     ->where('sales_order_items.so_id', $deliverynote_obj -> so_id)
                //     ->where('delivery_note_reports.rep_code', auth() -> user() -> id . '_' . date('Ymd'))
                //     ->get();

                $deliverynoteitems = array();
                $j = 0;
                foreach ($deliverynoteitem_objs as $deliverynoteitem_obj) {

                    $deliNoteReport = DeliveryNoteReport::where('qn_id', $deliverynote_obj->qn_id)
                        ->where('qi_id', $deliverynoteitem_obj->id)
                        ->first();

                    $deliverynoteitems[$j]['id'] = $deliverynoteitem_obj->id;
                    $deliverynoteitems[$j]['name'] = $deliverynoteitem_obj->name;
                    $deliverynoteitems[$j]['specification'] = $deliverynoteitem_obj->specification;
                    $deliverynoteitems[$j]['unit'] = $deliverynoteitem_obj->unit;
                    $deliverynoteitems[$j]['pack'] = $deliverynoteitem_obj->pack;
                    $deliverynoteitems[$j]['qty'] = $deliverynoteitem_obj->qty;
                    $deliverynoteitems[$j]['price'] = $deliverynoteitem_obj->price;
                    $deliverynoteitems[$j]['remarks'] = $deliverynoteitem_obj->remarks;
                    $deliverynoteitems[$j]['dn_re'] = (!empty($deliNoteReport) ? $deliNoteReport->id : '');
                    $j++;
                }
            }
            $deliverynotes_new[$i]['deliverynoteitems'] = $deliverynoteitems;
            $i++;
        }

        return view('pages.page-delivery-report', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'deliverynotes_new', 'deliverynotes', 'costcenters', 'items', 'to', 'from'));
    }

    public function bulkDeliveryNoteReportPdf(Request $request)
    {
        $to    = date('Y-m-d', time());
        $from    = date('Y-m-d', time());

        if ($request->has('from_date')) {
            if ($request->get('from_date')) {
                $from = date('Y-m-d', strtotime($request->get('from_date')));
            }
        }

        if ($request->has('to_date')) {
            if ($request->get('to_date')) {
                $to = date('Y-m-d', strtotime($request->get('to_date')));
            }
        }


        $deliverynote_objs = DB::table('delivery_notes')
            ->leftJoin('departments', 'delivery_notes.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'delivery_notes.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'delivery_notes.userext_id', '=', 'extusers.id')
            ->leftJoin('users as deliver', 'delivery_notes.userint_id', '=', 'deliver.id')
            ->select('delivery_notes.*', 'departments.name as department', 'costcenters.name as costcentre', 'extusers.username as extusername', 'deliver.username as delivername')
            ->when($request->get('from_date'), function ($query, $request) {
                $query->where('delivery_notes.sign_date', '>=', $request);
            })
            ->when($request->get('to_date'), function ($query, $request) {
                $query->where('delivery_notes.sign_date', '<=', date('Y-m-d' . ' 11:45:00', strtotime($request)));
            })
            ->orderBy('delivery_notes.created_at', 'desc')
            ->get();

        // dd($deliverynote_objs);


        $data = array();
        for ($i = 0; count($deliverynote_objs) > $i; $i++) {
            // dd($deliverynote_objs[$i]->id);
            $deliNote = DeliveryNote::find($deliverynote_objs[$i]->id);

            if ($deliNote && $deliNote->so_id) {
                $sql = "select dnr.*, dn.so_id, u.telephone tel, so.created_at order_date, uu.usercode intuser, dp.name department from delivery_note_reports dnr
                    left join delivery_notes dn on dn.id=dnr.dn_id
                    left join users u on u.id=dn.userext_id
                    left join sales_orders so on so.id=dn.so_id
                    left join departments dp on dp.id=so.dep_id
                    left join users uu on uu.id=dn.userint_id
                    where dn.id=" . $deliverynote_objs[$i]->id;

                $deliverynotereports = DB::select($sql);

                if ($deliverynotereports) {
                    $sql = "select soi.item_qty qty, i.* from sales_order_items soi
                    left join items i on i.id=soi.item_id
                    where soi.so_id = " . $deliverynotereports[0]->so_id;

                    $items = DB::select($sql);
                }
            } else if ($deliNote && $deliNote->qn_id) {
                $sql = "select dnr.*, dn.qn_id, u.telephone tel, qn.created_at order_date, uu.usercode intuser, dp.name department from delivery_note_reports dnr
                    left join delivery_notes dn on dn.id=dnr.dn_id
                    left join users u on u.id=dn.userext_id
                    left join quotations qn on qn.id=dn.qn_id
                    left join departments dp on dp.id=dn.dep_id
                    left join users uu on uu.id=dn.userint_id
                    where dn.id=" . $deliverynote_objs[$i]->id;

                $deliverynotereports = DB::select($sql);

                // $sql = "select soi.item_qty qty, i.* from sales_order_items soi
                //     left join items i on i.id=soi.item_id
                //     where soi.so_id = " . $deliverynotereports[0]->qn_id;

                // $items = DB::select($sql);

                $items = QuotationItem::where('qn_id', $deliverynotereports[0]->qn_id)->get();
            }


            // $sql = "select i.*, dnr.request_qty qty from items i left join delivery_note_reports dnr on i.id=dnr.item_id where dnr.dn_id=" . $id;
            // return $items = DB::select($sql);

            $internalcompany = InternalCompany::all();
            $externalcompany = ExternalCompany::all();

            $data[$i] = [
                'title' => 'Delivery Note Report',
                'heading' => 'Delivery Note Report',
                'content' => $deliverynotereports ? $deliverynotereports[0] : null,
                'internalcompany' => $internalcompany[0],
                'externalcompany' => $externalcompany[0],
                'items' => $items
            ];
        }
        // $offline = array();
        // $offline =  $data;
        // dd("Done",$offline);
        $pdf = PDF::setOptions([
            'images' => true,
            'isRemoteEnabled' => true
        ])->loadView('pdf.bluk_deliverynote_pdf', compact('data'))->setPaper('a4', 'portrait');
        return $pdf->download('deliverynotereport_' . date('Ymdhms') . '.pdf');
    }

    public function getReports(Request $request)
    {
        $costcentre = $request->costcentre;
        $item = $request->item;
        $dn_from = $request->dn_from;
        $dn_to = $request->dn_to;
        $sign_from = $request->sign_from;
        $sign_to = $request->sign_to;

        $sql = 'select * from delivery_note_reports where rep_code = "' . auth()->user()->id . '_' . date('Ymd') . '"';

        if ($costcentre) $sql .= ' and cc_id = ' . $costcentre;

        if ($item) $sql .= ' and item_id = ' . $item;

        $deliverynotes_result = DB::select($sql);

        $deliverynotes = array();
        $j = 0;
        foreach ($deliverynotes_result as $deliverynotes_item) {
            $notedate_array = explode(' ', $deliverynotes_item->dn_date);
            $notedate = $notedate_array[0];

            $signdate_array = explode(' ', $deliverynotes_item->sign_date);
            $signdate = $signdate_array[0];

            $notedatefrom_flag = 1;
            $notedateto_flag = 1;

            $signdatefrom_flag = 1;
            $signdateto_flag = 1;

            if ($dn_from != '') {
                $dn_from_array = explode('/', $dn_from);
                $dn_from_text = $dn_from_array[2] . '-' . $dn_from_array[1] . '-' . $dn_from_array[0];

                if ($dn_from_text <= $notedate) $notedatefrom_flag = 1;
                else $notedatefrom_flag = 0;
            }

            if ($dn_to != '') {
                $dn_to_array = explode('/', $dn_to);
                $dn_to_text = $dn_to_array[2] . '-' . $dn_to_array[1] . '-' . $dn_to_array[0];

                if ($dn_to_text >= $notedate) $notedateto_flag = 1;
                else $notedateto_flag = 0;
            }

            if ($sign_from != '') {
                $sign_from_array = explode('/', $sign_from);
                $sign_from_text = $sign_from_array[2] . '-' . $sign_from_array[1] . '-' . $sign_from_array[0];

                if ($sign_from_text <= $signdate && $signdate_array) $signdatefrom_flag = 1;
                else $signdatefrom_flag = 0;
            }

            if ($sign_to != '') {
                $sign_to_array = explode('/', $sign_to);
                $sign_to_text = $sign_to_array[2] . '-' . $sign_to_array[1] . '-' . $sign_to_array[0];

                if ($sign_to_text >= $signdate && $signdate_array) $signdateto_flag = 1;
                else $signdateto_flag = 0;
            }

            if ($notedatefrom_flag && $notedateto_flag && $signdatefrom_flag && $signdateto_flag) {
                $deliverynotes[$j]['id'] = $deliverynotes_item->id;
                $deliverynotes[$j]['so_no'] = $deliverynotes_item->so_no;
                $deliverynotes[$j]['user'] = $deliverynotes_item->user;
                $deliverynotes[$j]['costcentre'] = $deliverynotes_item->costcentre;
                $deliverynotes[$j]['item_code'] = $deliverynotes_item->item_code;
                $deliverynotes[$j]['specification'] = $deliverynotes_item->specification;
                $deliverynotes[$j]['request_qty'] = $deliverynotes_item->request_qty;
                $deliverynotes[$j]['unit'] = $deliverynotes_item->unit;
                $deliverynotes[$j]['packing'] = $deliverynotes_item->packing;
                $deliverynotes[$j]['unit_price'] = $deliverynotes_item->unit_price;
                $deliverynotes[$j]['total_price'] = $deliverynotes_item->total_price;
                $deliverynotes[$j]['approver'] = $deliverynotes_item->approver;
                $deliverynotes[$j]['approve_date'] = $deliverynotes_item->approve_date;
                $deliverynotes[$j]['dn_id'] = $deliverynotes_item->dn_id;
                $deliverynotes[$j]['note_no'] = $deliverynotes_item->note_no;
                $deliverynotes[$j]['dn_date'] = $deliverynotes_item->dn_date;
                $deliverynotes[$j]['sign_date'] = $deliverynotes_item->sign_date;
                $j++;
            }
        }

        return response()->json($deliverynotes);
    }

    public function deliverynoteSpecialhandling($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Delivery Note"], ['name' => "Delivery Note Special Handling"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $deliverynotereport = DeliveryNoteReport::find($id);
        $deliverynote = DeliveryNote::find($deliverynotereport->dn_id);

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-delivery-special-handling', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'deliverynotereport', 'deliverynote'));
    }

    public function deliverynotereportUpdate(Request $request)
    {
        $deliverynotereport = DeliveryNoteReport::find($request->rep_id);

        $deliverynotereport->user = $request->user;
        $deliverynotereport->costcentre = $request->costcentre;
        $deliverynotereport->dn_date = $request->notedate;
        $deliverynotereport->item_code = $request->itemcode;
        $deliverynotereport->specification = $request->description;
        $deliverynotereport->request_qty = $request->qty;
        $deliverynotereport->unit = $request->unit;
        $deliverynotereport->packing = $request->packing;
        $deliverynotereport->unit_price = $request->unitprice;
        $deliverynotereport->total_price = $request->total_price;

        $deliverynotereport->save();

        $deliverynote = DeliveryNote::find($deliverynotereport->dn_id);

        if ($request->status == 1) {
            $itemtransaction = new ItemTransaction();

            $old_itemtransaction = ItemTransaction::where('tx_doc', $deliverynotereport->note_no)->where('item_id', $deliverynotereport->item_id)->get();
            if (count($old_itemtransaction)) {
                $itemtransaction = $old_itemtransaction[0];
                $itemtransaction->tx_type = 2;
            } else {
                $itemtransaction->tx_type = 1;
            }
            $itemtransaction->tx_doc = $deliverynotereport->note_no;
            $itemtransaction->supplier = 0;
            $itemtransaction->item_id = $deliverynotereport->item_id;
            $itemtransaction->tx_out = $deliverynotereport->request_qty;
            $itemtransaction->tx_in = 0;

            $itemtransaction->save();

            $deliverynotereport->itemtx_id = $itemtransaction->id;
            $deliverynotereport->save();
        } else if ($request->status == 2) {
            $deliverynote->status = 2;
            $deliverynote->save();

            $itemtransaction_id = $deliverynotereport->itemtx_id;

            if ($itemtransaction_id) {
                $itemtransaction = ItemTransaction::find($itemtransaction_id);

                $itemtransaction->tx_out = 0;
                $itemtransaction->tx_in = 0;

                $itemtransaction->save();
            }
        }

        return redirect('/dn-history/' . $deliverynotereport->rep_code);
    }

    public function createDelivernote($id)
    {
        $salesorder = SalesOrder::find($id);

        $deliverynote = new DeliveryNote();

        $deliverynote->so_id = $id;
        $deliverynote->so_no = $salesorder->no;
        $deliverynote->dep_id = $salesorder->dep_id;
        $deliverynote->cc_id = $salesorder->cc_id;
        $deliverynote->userext_id = $salesorder->extuser_id;
        $deliverynote->userint_id = auth()->user()->id;
        $deliverynote->status = 0;
        $deliverynote->no = Helper::getSerialNumber($deliverynote->getTable(), 'no', 'CS-DN-');
        $deliverynote->save();


        $salesorder->status = 2; // waiting for deliver
        $salesorder->dn_id = $deliverynote->id;
        $salesorder->dn_no = $deliverynote->no;
        $salesorder->dn_date = date('Y-m-d h:m:s');

        $salesorder->save();

        return redirect('/current-dn');
    }

    public function createDelivernoteFromQuotation($id)
    {
        $quotation = Quotation::find($id);

        $deliverynote = new DeliveryNote();

        $deliverynote->qn_id = $id;
        $deliverynote->qn_no = $quotation->code;
        $deliverynote->dep_id = $quotation->dep_id;
        $deliverynote->cc_id = $quotation->cc_id;
        $deliverynote->userext_id = $quotation->userext_id;
        $deliverynote->userint_id = auth()->user()->id;
        $deliverynote->status = 0;
        $deliverynote->no = Helper::getSerialNumber($deliverynote->getTable(), 'no', 'CS-DN-');
        $deliverynote->save();

        $quotation->status = 1; // waiting for deliver
        $quotation->dn_id = $deliverynote->id;
        $quotation->dn_no = $deliverynote->no;
        $quotation->dn_date = date('Y-m-d h:m:s');

        $quotation->save();

        return redirect('/current-dn');
    }

    public function makeDelivery($id)
    {
        $deliverynote = DeliveryNote::find($id);
        $deliverynote->status = 1;
        $deliverynote->sign_date = date('Y-m-d h:m:s');
        $deliverynote->save();

        if ($deliverynote->so_id) {
            $salesorder = SalesOrder::find($deliverynote->so_id);
            $salesorder->status = 3; // delivered
            $salesorder->save();
        } else if ($deliverynote->qn_id) {
            $quotation = Quotation::find($deliverynote->qn_id);
            $quotation->status = 3; // delivered
            $quotation->appruser_id = auth()->user()->id;
            $quotation->appr_date = date('Y-m-d h:m:s');
            $quotation->save();
        }

        return redirect('/current-dn');
    }

    public function printPdf($id)
    {
        $deliNote = DeliveryNote::find($id);

        if ($deliNote && $deliNote->so_id) {
            $sql = "SELECT dnr.*, dn.so_id, u.telephone tel, so.created_at order_date, uu.usercode intuser, dp.name department, cc.code as costcenter_code
					FROM delivery_note_reports dnr
					LEFT JOIN delivery_notes dn ON dn.id = dnr.dn_id
					LEFT JOIN users u ON u.id = dn.userext_id
					LEFT JOIN sales_orders so ON so.id = dn.so_id
					LEFT JOIN departments dp ON dp.id = so.dep_id
					LEFT JOIN users uu ON uu.id = dn.userint_id
					LEFT JOIN costcenters cc ON cc.name = dnr.costcentre
					WHERE dn.id = ?";
            $deliverynotereports = DB::select($sql, [$id]);

            if (!empty($deliverynotereports)) {
                $sql = "SELECT soi.item_qty qty, i.*
						FROM sales_order_items soi
						LEFT JOIN items i ON i.id = soi.item_id
						WHERE soi.so_id = ?";
                $items = DB::select($sql, [$deliverynotereports[0]->so_id]);
            } else {
                return redirect()->back()->withErrors('No delivery note reports found.');
            }
        } else if ($deliNote && $deliNote->qn_id) {
            $sql = "SELECT dnr.*, dn.qn_id, u.telephone tel, qn.created_at order_date, uu.usercode intuser, dp.name department, cc.code as costcenter_code
					FROM delivery_note_reports dnr
					LEFT JOIN delivery_notes dn ON dn.id = dnr.dn_id
					LEFT JOIN users u ON u.id = dn.userext_id
					LEFT JOIN quotations qn ON qn.id = dn.qn_id
					LEFT JOIN departments dp ON dp.id = dn.dep_id
					LEFT JOIN users uu ON uu.id = dn.userint_id
					LEFT JOIN costcenters cc ON cc.name = dnr.costcentre
					WHERE dn.id = ?";
            $deliverynotereports = DB::select($sql, [$id]);

            if (!empty($deliverynotereports)) {
                $items = QuotationItem::where('qn_id', $deliverynotereports[0]->qn_id)->get();
            } else {
                return redirect()->back()->withErrors('No delivery note reports found.');
            }
        } else {
            return redirect()->back()->withErrors('Delivery note not found.');
        }

        $internalcompany = InternalCompany::first();
        $externalcompany = ExternalCompany::first();

        $data = [
            'title' => 'Delivery Note Report',
            'heading' => 'Delivery Note Report',
            'content' => $deliverynotereports[0],
            'internalcompany' => $internalcompany,
            'externalcompany' => $externalcompany,
            'items' => $items
        ];

        $pdf = PDF::setOptions([
            'images' => true,
            'isRemoteEnabled' => true
        ])->loadView('pdf.deliverynote_pdf', $data)->setPaper('a4', 'portrait');

        return $pdf->download('deliverynotereport_' . date('YmdHis') . '.pdf');
    }

    public function printExcel(Request $request)
    {
        return Excel::download(new DeliveryNoteReportsExport($request->form_date, $request->to_date), 'deliverynotereport_' . date('Ymdhms') . '.xlsx');
    }
}

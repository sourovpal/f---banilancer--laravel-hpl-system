<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Costcenter;
use App\Models\Quotation;
use App\Models\Department;
use App\Models\QuotationItem;
use App\Models\QuotationReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF, Redirect;
use App\Exports\QuotationReportsExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\InternalCompany;
use App\Models\ExternalCompany;

class QuotationController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this->internalcompany = InternalCompany::all();
        $this->externalcompany = ExternalCompany::all();
    }

    public function quotationList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Small Order"], ['name' => "Current Small Order List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $quotation_objs = DB::table('quotations')
            ->leftJoin('departments', 'quotations.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'quotations.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'quotations.userext_id', '=', 'extusers.id')
            ->leftJoin('users as intusers', 'quotations.userint_id', '=', 'intusers.id')
            ->select('quotations.*', 'departments.name as department', 'costcenters.code as costcentre', 'extusers.username as extusername', 'intusers.username as intusername')
            ->where('quotations.status', 0)
            ->where('quotations.code', '!=', '')
            ->orderBy('created_at', 'desc')
            ->get();

        $quotations = array();
        $i = 0;
        foreach ($quotation_objs as $quotation_obj) {
            if (auth()->user()->role != 'master' && auth()->user()->role != 'internal' && auth()->user()->admin_role == 0 && auth()->user()->appr_role == 2 && auth()->user()->dep_id != $quotation_obj->dep_id) continue;

            if (auth()->user()->role != 'master' && auth()->user()->role != 'internal' && auth()->user()->admin_role == 0 && (auth()->user()->appr_role == 1 || auth()->user()->appr_role == 0) && auth()->user()->id != $quotation_obj->userext_id) continue;

            $quotations[$i]['id'] = $quotation_obj->id;
            $quotations[$i]['code'] = $quotation_obj->code;
            $quotations[$i]['department'] = $quotation_obj->department;
            $quotations[$i]['costcentre'] = $quotation_obj->costcentre;
            $quotations[$i]['extuser'] = $quotation_obj->extusername;
            $quotations[$i]['intuser'] = $quotation_obj->intusername;
            $quotations[$i]['remarks'] = $quotation_obj->remarks;
            $quotations[$i]['status'] = $quotation_obj->status;
            $quotations[$i]['date'] = $quotation_obj->created_at;

            $quotationitem_objs = DB::table('quotation_items')
                ->where('qn_id', $quotation_obj->id)
                ->get();

            $quotationitems = array();
            $j = 0;
            foreach ($quotationitem_objs as $quotationitem_obj) {
                $quotationitems[$j]['id'] = $quotationitem_obj->id;
                $quotationitems[$j]['name'] = $quotationitem_obj->name;
                $quotationitems[$j]['specification'] = $quotationitem_obj->specification;
                $quotationitems[$j]['unit'] = $quotationitem_obj->unit;
                $quotationitems[$j]['pack'] = $quotationitem_obj->pack;
                $quotationitems[$j]['qty'] = $quotationitem_obj->qty;
                $quotationitems[$j]['price'] = $quotationitem_obj->price;
                $quotationitems[$j]['remarks'] = $quotationitem_obj->remarks;
                $j++;
            }
            $quotations[$i]['quotationitems'] = $quotationitems;
            $i++;
        }

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-quotation-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'quotations'));
    }

    public function quotationCreate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Small Order"], ['name' => "Create Small Order Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        if (auth()->user()->role == 'internal') return back();

        $date = date("Y-m-d");

        if (!$id) {
            $quotation = new Quotation();
            $quotation->dep_id = auth()->user()->dep_id ? auth()->user()->dep_id : 0;
            $quotation->userext_id = Auth::id();
            $quotation->status = 0;
            $quotation->save();

            $data = ['date' => $date, 'quotation' => $quotation->id];
        } else {
            $data = ['date' => $date, 'quotation' => $id];

            $quotation = Quotation::find($id);

            if (!empty($quotation)) {
                $data['cc_id'] = $quotation->cc_id;
            }
        }

        if (auth()->user()->role == "master" || auth()->user()->role == "internal") {
            $department = 'Indifinite';
        } else {
            $department_obj = Department::find(auth()->user()->dep_id);
            if ($department_obj) {
                $department = $department_obj->name;
            } else {
                $department = 'Indifinite';
            }
        }

        $quotationitems = DB::table('quotation_items')
            ->where('qn_id', $data['quotation'])
            ->get();

        $costcenters = Costcenter::orderBy('code', 'asc')->get();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-quotation-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'data', 'costcenters', 'quotationitems', 'department'));
    }


    public function quotationListRender(Request $request)
    {

        $quotation_objs = DB::table('quotations')
            ->leftJoin('departments', 'quotations.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'quotations.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'quotations.userext_id', '=', 'extusers.id')
            ->leftJoin('users as intusers', 'quotations.userint_id', '=', 'intusers.id')
            ->select('quotations.*', 'departments.name as department', 'costcenters.name as costcentre', 'extusers.username as extusername', 'intusers.username as intusername')
            ->where('quotations.status', 0)
            ->where('quotations.code', '!=', '')
            ->where('quotations.code', '=', $request->code)
            ->orderBy('created_at', 'desc')
            ->first();

        $quotationitems = array();

        if (isset($quotation_objs)) {

            $quotationitem_objs = DB::table('quotation_items')->where('qn_id', $quotation_objs->id)->get();

            $j = 0;
            foreach ($quotationitem_objs as $quotationitem_obj) {
                $quotationitems[$j]['id'] = $quotationitem_obj->id;
                $quotationitems[$j]['name'] = $quotationitem_obj->name;
                $quotationitems[$j]['specification'] = $quotationitem_obj->specification;
                $quotationitems[$j]['unit'] = $quotationitem_obj->unit;
                $quotationitems[$j]['pack'] = $quotationitem_obj->pack;
                $quotationitems[$j]['qty'] = $quotationitem_obj->qty;
                $quotationitems[$j]['price'] = $quotationitem_obj->price;
                $quotationitems[$j]['remarks'] = $quotationitem_obj->remarks;
                $j++;
            }
        }

        return response()->json($quotationitems);
    }

    public function quotationUpdate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Small Order"], ['name' => "Update Small Order Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        // if (auth() -> user() -> role == 'external') return back();

        $date = date("Y-m-d");
        $data = ['date' => $date, 'quotation' => $id];

        $costcenters = Costcenter::all();
        /*
        $quotation_obj = DB::table('quotations')
            ->leftJoin('departments', 'quotations.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'quotations.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'quotations.userext_id', '=', 'extusers.id')
            ->leftJoin('users as intusers', 'quotations.userint_id', '=', 'intusers.id')
            ->select('quotations.*', 'departments.name as department', 'costcenters.name as costcentre', 'extusers.username as extusername', 'intusers.username as intusername', 'quotation_items.image as image')
                ->leftJoin('quotation_items', 'quotations.id', '=', 'quotation_items.qn_id')
            ->where('quotations.id', $id)
            ->where('quotations.status', 0)
            ->where('quotations.code', '!=', '')
            ->get();

        $quotation = array();

        $quotation['id'] = $quotation_obj[0] -> id;
        $quotation['code'] = $quotation_obj[0] -> code;
        $quotation['department'] = $quotation_obj[0] -> department;
        $quotation['costcentre'] = $quotation_obj[0] -> costcentre;
        $quotation['extuser'] = $quotation_obj[0] -> extusername;
        $quotation['intuser'] = $quotation_obj[0] -> intusername;
        $quotation['remarks'] = $quotation_obj[0] -> remarks;
        $quotation['status'] = $quotation_obj[0] -> status;
        $quotation['date'] = $quotation_obj[0] -> created_at;
        $quotation['image'] = $quotation_obj[0]->image;

        $quotationitem_objs = DB::table('quotation_items')->where('qn_id', $quotation_obj[0] -> id)->get();

        $quotationitems = array();

        $j = 0;

        foreach ($quotationitem_objs as $quotationitem_obj) {
            $quotationitems[$j]['id'] = $quotationitem_obj -> id;
            $quotationitems[$j]['name'] = $quotationitem_obj -> name;
            $quotationitems[$j]['specification'] = $quotationitem_obj -> specification;
            $quotationitems[$j]['unit'] = $quotationitem_obj -> unit;
            $quotationitems[$j]['pack'] = $quotationitem_obj -> pack;
            $quotationitems[$j]['qty'] = $quotationitem_obj -> qty;
            $quotationitems[$j]['price'] = $quotationitem_obj -> price;
            $quotationitems[$j]['remarks'] = $quotationitem_obj -> remarks;
             $quotationitems[$j]['image'] = $quotationitem_obj->image;
            $j++;
        }

        $quotation['quotationitems'] = $quotationitems;

        if (auth() -> user() -> role == "master") {
            $department = 'Indifinite';
        }
        else {
            $department_obj = Department::find($quotation_obj[0] -> dep_id);
            $department = $department_obj -> name;
        }

        $internalCompany = $this -> internalcompany[0] -> name;
        $externalCompany = $this -> externalcompany[0] -> name;

        return view('pages.page-quotation-update', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'quotation', 'costcenters', 'data', 'department'));
        */
        $status = DB::table('quotations')->where('id', $id)->where('code', '!=', '')->first();

        if ($status->status == 3) {
            return Redirect::back()->withErrors(['msg' => 'Quotation has been Closed']);
        }


        $quotation_obj = DB::table('quotations')
            ->leftJoin('departments', 'quotations.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'quotations.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'quotations.userext_id', '=', 'extusers.id')
            ->leftJoin('users as intusers', 'quotations.userint_id', '=', 'intusers.id')
            ->select('quotations.*', 'departments.name as department', 'costcenters.name as costcentre', 'extusers.username as extusername', 'intusers.username as intusername', 'quotation_items.image as image')
            ->leftJoin('quotation_items', 'quotations.id', '=', 'quotation_items.qn_id')
            ->where('quotations.id', $id)
            ->where('quotations.status', 0)
            ->where('quotations.code', '!=', '')
            ->first();


        $quotation = array();

        if ($quotation_obj) {
            $quotation['id'] = $quotation_obj->id;
            $quotation['code'] = $quotation_obj->code;
            $quotation['department'] = $quotation_obj->department;
            $quotation['costcentre'] = $quotation_obj->costcentre;
            $quotation['extuser'] = $quotation_obj->extusername;
            $quotation['intuser'] = $quotation_obj->intusername;
            $quotation['remarks'] = $quotation_obj->remarks;
            $quotation['status'] = $quotation_obj->status;
            $quotation['date'] = $quotation_obj->created_at;
            $quotation['image'] = $quotation_obj->image;

            $quotationitem_objs = DB::table('quotation_items')->where('qn_id', $quotation_obj->id)->get();

            $quotationitems = array();

            $j = 0;

            foreach ($quotationitem_objs as $quotationitem_obj) {
                $quotationitems[$j]['id'] = $quotationitem_obj->id;
                $quotationitems[$j]['name'] = $quotationitem_obj->name;
                $quotationitems[$j]['specification'] = $quotationitem_obj->specification;
                $quotationitems[$j]['unit'] = $quotationitem_obj->unit;
                $quotationitems[$j]['pack'] = $quotationitem_obj->pack;
                $quotationitems[$j]['qty'] = $quotationitem_obj->qty;
                $quotationitems[$j]['price'] = $quotationitem_obj->price;
                $quotationitems[$j]['remarks'] = $quotationitem_obj->remarks;
                $quotationitems[$j]['image'] = $quotationitem_obj->image;
                $j++;
            }

            $quotation['quotationitems'] = $quotationitems;

            if (auth()->user()->role == "master" || auth()->user()->role == "") {
                $department = 'Indifinite';
            } else {
                $department_obj = Department::find($quotation_obj->dep_id);
                if ($department_obj) {
                    $department = $department_obj->name;
                }
            }

            $internalCompany = $this->internalcompany[0]->name;
            $externalCompany = $this->externalcompany[0]->name;

            return view('pages.page-quotation-update', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'quotation', 'costcenters', 'data', 'department'));
        } else {
            return Redirect::back()->withErrors(['msg' => 'Quotation does not Found']);
        }
    }

    public function quotationReport($status = "initial")
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Small Order"], ['name' => "Small Order Report"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        if (auth()->user()->role == 'external') return back();

        if ($status == 'initial') {
            $sql = 'select qi.*, qn.created_at qn_date, qn.code qn_no, qn.userext_id, qn.cc_id, cc.code costcenter, u.username user, qn.dep_id, d.name department from quotation_items qi
                    left join quotations qn on qn.id=qi.qn_id
                    left join departments d on d.id=qn.dep_id
                    left join users u on u.id=qn.userext_id
                    left join costcenters cc on cc.id=qn.cc_id
                    where qn.code IS NOT NULL';

            $quotations_result = DB::select($sql);

            $rep_code = auth()->user()->id . '_' . date('Ymd');
            DB::table('quotation_reports')->where('rep_code', '=', $rep_code)->delete();

            $quotations = array();
            $j = 0;
            foreach ($quotations_result as $quotations_item) {
                $quotations[$j]['qi_id'] = $quotations_item->id;
                $quotations[$j]['qn_no'] = $quotations_item->qn_no;
                $quotations[$j]['qn_date'] = $quotations_item->qn_date;
                $quotations[$j]['user'] = $quotations_item->user;
                $quotations[$j]['department'] = $quotations_item->department;
                $quotations[$j]['item_name'] = $quotations_item->name;
                $quotations[$j]['specification'] = $quotations_item->specification;
                $quotations[$j]['request_qty'] = $quotations_item->qty;
                $quotations[$j]['unit'] = $quotations_item->unit;
                $quotations[$j]['pack'] = $quotations_item->pack;
                $quotations[$j]['price'] = $quotations_item->price;
                $quotations[$j]['total_price'] = $quotations_item->price * $quotations_item->qty;

                $quotationreport = new QuotationReport();
                $quotationreport->qi_id = $quotations_item->id;
                $quotationreport->qn_no = $quotations_item->qn_no;
                $quotationreport->qn_date = $quotations_item->qn_date;
                $quotationreport->user = $quotations_item->user;
                $quotationreport->costcenter = $quotations_item->costcenter;
                $quotationreport->item_name = $quotations_item->name;
                $quotationreport->specification = $quotations_item->specification;
                $quotationreport->request_qty = $quotations_item->qty;
                $quotationreport->unit = $quotations_item->unit;
                $quotationreport->pack = $quotations_item->pack;
                $quotationreport->price = $quotations_item->price;
                $quotationreport->total_price = $quotations_item->price * $quotations_item->qty;
                $quotationreport->dep_id = $quotations_item->dep_id;
                $quotationreport->cc_id = $quotations_item->cc_id;
                $quotationreport->user_id = $quotations_item->userext_id;
                $quotationreport->rep_code = auth()->user()->id . '_' . date('Ymd');

                $quotationreport->save();

                $quotations[$j]['rep_id'] = $quotationreport->id;
                $quotations[$j]['rep_code'] = auth()->user()->id . '_' . date('Ymd');

                $j++;
            }
        } else {
            $rep_code = $status;
            $quotations = QuotationReport::where('rep_code', $rep_code)->get();
        }

        $costcenters = Costcenter::all();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        $quotation_objs = DB::table('quotations')
            ->leftJoin('departments', 'quotations.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'quotations.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'quotations.userext_id', '=', 'extusers.id')
            ->leftJoin('users as intusers', 'quotations.userint_id', '=', 'intusers.id')
            ->select('quotations.*', 'departments.name as department', 'costcenters.code as costcentre', 'extusers.username as extusername', 'intusers.username as intusername')
            ->where('quotations.code', '!=', '')
            ->orderBy('quotations.created_at', 'desc')
            ->get();

        $quotations_new = array();
        $i = 0;
        foreach ($quotation_objs as $quotation_obj) {
            $quotations_new[$i]['id'] = $quotation_obj->id;
            $quotations_new[$i]['code'] = $quotation_obj->code;
            $quotations_new[$i]['department'] = $quotation_obj->department;
            $quotations_new[$i]['costcentre'] = $quotation_obj->costcentre;
            $quotations_new[$i]['extuser'] = $quotation_obj->extusername;
            $quotations_new[$i]['intuser'] = $quotation_obj->intusername;
            $quotations_new[$i]['remarks'] = $quotation_obj->remarks;
            $quotations_new[$i]['status'] = $quotation_obj->status;
            $quotations_new[$i]['date'] = $quotation_obj->created_at;

            $quotationitem_objs = DB::table('quotation_items')
                ->where('qn_id', $quotation_obj->id)
                ->get();

            $quotationitems = array();
            $j = 0;
            foreach ($quotationitem_objs as $quotationitem_obj) {
                $quotationitems[$j]['id'] = $quotationitem_obj->id;
                $quotationitems[$j]['name'] = $quotationitem_obj->name;
                $quotationitems[$j]['specification'] = $quotationitem_obj->specification;
                $quotationitems[$j]['unit'] = $quotationitem_obj->unit;
                $quotationitems[$j]['pack'] = $quotationitem_obj->pack;
                $quotationitems[$j]['qty'] = $quotationitem_obj->qty;
                $quotationitems[$j]['price'] = $quotationitem_obj->price;
                $quotationitems[$j]['remarks'] = $quotationitem_obj->remarks;
                $j++;
            }
            $quotations_new[$i]['quotationitems'] = $quotationitems;
            $i++;
        }

        return view('pages.page-quotation-report', compact('quotations_new', 'pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'quotations', 'costcenters'));
    }

    public function quotationItemRegister(Request $request)
    {
        $qn_id = $request->quotation;
        $costcentre = $request->costcentre;
        $name = $request->itemname;
        $specification = $request->itemspecification;
        $unit = $request->itemunit;
        $pack = $request->itempack;
        $qty = $request->itemqty;
        $price = $request->itemprice;
        $remarks = $request->remarks;
        $itemimage = $request->itemimage;

        $filename = '';
        if ($request->itemimage != null) {
            $itemimage = $request->file('itemimage');
            $filename = date('Ymdhms') . '___' . $itemimage->getClientOriginalName();
            $itemimage->move(public_path('/customImage/images'), $filename);
        }

        $quotationitem = new QuotationItem();
        $quotationitem->qn_id = $qn_id;
        $quotationitem->specification = $specification;
        $quotationitem->unit = $unit;
        $quotationitem->pack = $pack;
        $quotationitem->name = $name;
        $quotationitem->qty = $qty;
        $quotationitem->price = $price;
        $quotationitem->remarks = $remarks;
        $quotationitem->image = $filename;
        $quotationitem->save();

        $quotation = Quotation::find($qn_id);
        $quotation->cc_id = $costcentre;
        $quotation->save();

        return redirect('/new-quotation/' . $qn_id);
    }

    public function quotationRegister(Request $request)
    {
        $qn_id = $request->qn_id;
        $costcentre = $request->costcentre;

        $remarks = $request->remarks;

        $quotation = Quotation::find($qn_id);
        $quotation->code = 'QSM' . date('Ymdhms') . $qn_id;
        // $quotation -> dep_id = 1;
        $quotation->userext_id = Auth::id();
        $quotation->userint_id = Auth::id();
        $quotation->cc_id = $costcentre;
        $quotation->remarks = $remarks;
        if ($request->filled('status')) {
            $quotation->status = $request->status;
        }
        $quotation->save();

        //return redirect('/current-quotation');
    }

    public function quotationItemDelete($id)
    {
        $quottationitem = QuotationItem::find($id);
        $qn_id = $quottationitem->qn_id;
        $quottationitem->delete();
        return redirect('/new-quotation/' . $qn_id);
    }

    public function getReports(Request $request)
    {
        $costcentre = $request->costcentre;
        $qn_from = $request->qn_from;
        $qn_to = $request->qn_to;

        $sql = 'select * from quotation_reports where rep_code = "' . auth()->user()->id . '_' . date('Ymd') . '"';

        if ($costcentre) $sql .= ' and cc_id = ' . $costcentre;

        $quotations_result = DB::select($sql);

        $quotations = array();
        $j = 0;
        foreach ($quotations_result as $quotations_item) {
            $qndate_array = explode(' ', $quotations_item->qn_date);
            $qndate = $qndate_array[0];

            $qndatefrom_flag = 1;
            $qndateto_flag = 1;

            if ($qn_from != '') {
                $qn_from_array = explode('/', $qn_from);
                $qn_from_text = $qn_from_array[2] . '-' . $qn_from_array[1] . '-' . $qn_from_array[0];

                if ($qn_from_text <= $qndate) $qndatefrom_flag = 1;
                else $qndatefrom_flag = 0;
            }

            if ($qn_to != '') {
                $qn_to_array = explode('/', $qn_to);
                $qn_to_text = $qn_to_array[2] . '-' . $qn_to_array[1] . '-' . $qn_to_array[0];

                if ($qn_to_text >= $qndate) $qndateto_flag = 1;
                else $qndateto_flag = 0;
            }

            if ($qndatefrom_flag && $qndateto_flag) {
                $quotations[$j]['id'] = $quotations_item->id;
                $quotations[$j]['qn_no'] = $quotations_item->qn_no;
                $quotations[$j]['user'] = $quotations_item->user;
                $quotations[$j]['department'] = $quotations_item->dep_id;
                $quotations[$j]['item_name'] = $quotations_item->item_name;
                $quotations[$j]['specification'] = $quotations_item->specification;
                $quotations[$j]['costcenter'] = $quotations_item->costcenter;
                $quotations[$j]['request_qty'] = $quotations_item->request_qty;
                $quotations[$j]['unit'] = $quotations_item->unit;
                $quotations[$j]['pack'] = $quotations_item->pack;
                $quotations[$j]['price'] = $quotations_item->price;
                $quotations[$j]['total_price'] = $quotations_item->total_price;
                $quotations[$j]['qn_date'] = $quotations_item->qn_date;
                $quotations[$j]['rep_code'] = $quotations_item->rep_code;
                $quotations[$j]['user_id'] = $quotations_item->user_id;
                $j++;
            }
        }

        return response()->json($quotations);
    }

    public function printPdf(Request $request, $id)
    {
        $sql = "select qn.*, u.telephone tel, u.userid user, u.username name, dp.name department from quotations qn left join users u on u.id=qn.userext_id left join departments dp on dp.id=qn.dep_id where qn.id=" . $id;
        $quotations = DB::select($sql);

        $sql = "select * from quotation_items where qn_id = " . $id;
        $items = DB::select($sql);

        $internalcompany = InternalCompany::all();
        $externalcompany = ExternalCompany::all();

        $data = [
            'title' => 'Quotation Report',
            'heading' => 'Quotation Report',
            'content' => $quotations[0],
            'internalcompany' => $internalcompany[0],
            'externalcompany' => $externalcompany[0],
            'items' => $items,
            'invoice_print' => $request->has('invoice')
        ];

        $pdf = PDF::setOptions([
            'images' => true,
            'isRemoteEnabled' => true
        ])->loadView('pdf.quotation_pdf', $data)->setPaper('a4', 'portrait');

        if ($request->has('invoice')) {
            return $pdf->download('invoice_report_' . date('Ymdhms') . '.pdf');
        }
        return $pdf->download('quotationreport_' . date('Ymdhms') . '.pdf');
    }

    public function printExcel()
    {
        return Excel::download(new QuotationReportsExport, 'quotationreport_' . date('Ymdhms') . '.xlsx');
    }

    public function saveItemsQuotation(Request $request)
    {
        $items = $request->items;
        $qn_id = $request->qn_id;

        foreach ($items as $item) {
            $quotationItem = QuotationItem::find($item['id']);
            $quotationItem->qty = $item['qty'];
            $quotationItem->price = $item['price'];
            $quotationItem->save();
        }

        // DB::table('quotation_items')->where('qn_id', $so_id)->delete();

        // for ($i = 0; $i < count($items); $i++) {
        //     if ($items[$i]['qty']) {
        //         $salesorderitem = new QuotationItem();
        //         $salesorderitem -> so_id = $so_id;
        //         $salesorderitem -> dn_id = $dn_id;
        //         $salesorderitem -> item_id = $items[$i]['id'];
        //         $salesorderitem -> item_qty = $items[$i]['qty'];
        //         $salesorderitem -> remarks = $items[$i]['specification'];

        //         $salesorderitem -> save();
        //     }
        // }

        $result = ['result' => 'success'];

        return response()->json($result);
    }

    public function getQuotationItems(Request $request)
    {
        $items = QuotationItem::where('qn_id', $request->q_id)->get();

        return response()->json($items);
    }
}
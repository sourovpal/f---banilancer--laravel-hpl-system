<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Costcenter;
use App\Models\Department;
use App\Exports\SalesOrderReportsExport;
use App\Helpers\Helper;
use App\Models\ExternalCompany;
use App\Models\InternalCompany;
use App\Models\Item;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\SalesOrderReport;
use App\Models\User;
use Artisan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use PDF;

class SalesController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this->internalcompany = InternalCompany::all();
        $this->externalcompany = ExternalCompany::all();
    }

    public function salesorderList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Order Information"], ['name' => "My Order"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $date = date("Y-m-d");
        $data = ['date' => $date];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        // 初始查詢
        $salesorder_objs = DB::table('sales_orders')
            ->leftJoin('departments', 'sales_orders.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'sales_orders.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'sales_orders.extuser_id', '=', 'extusers.id')
            ->leftJoin('users as approvers', 'sales_orders.appruser_id', '=', 'approvers.id')
            ->select(
                'sales_orders.*',
                'departments.name as department',
                'costcenters.code as costcentre',
                'costcenters.name as name_costcentre',
                'extusers.username as extusername',
                'approvers.username as approver'
            )
            ->whereIn('sales_orders.status', [0, 1, 2])
            ->where('sales_orders.no', '!=', '');

        // 根據用戶角色進行條件判斷
        if (auth()->user()->role != 'internal') {
            $salesorder_objs = $salesorder_objs->where(function ($query) {
                $userId = auth()->user()->id;  // 獲取當前用戶的 ID
                // 添加條件，檢查 extuser_id 或 appruser_id 是否等於當前用戶 ID
                $query->where('sales_orders.extuser_id', $userId)
                    ->where('sales_orders.appruser_id', '!=', '1')
                    ->orWhere('sales_orders.appruser_id', $userId);
            });
        }

        $salesorder_objs = $salesorder_objs
            ->orderBy('sales_orders.created_at', 'DESC')
            ->get();

        $salesorders = array();
        $i = 0;
        foreach ($salesorder_objs as $salesorder_obj) {
            // 構建 $salesorders 數組
            $salesorders[$i]['id'] = $salesorder_obj->id;
            $salesorders[$i]['no'] = $salesorder_obj->no;
            $salesorders[$i]['orderdate'] = $salesorder_obj->created_at;
            $salesorders[$i]['department'] = $salesorder_obj->department;
            $salesorders[$i]['costcentre'] = $salesorder_obj->costcentre;
            $salesorders[$i]['name_costcentre'] = $salesorder_obj->name_costcentre;
            $salesorders[$i]['extusername'] = $salesorder_obj->extusername;
            $salesorders[$i]['approver'] = $salesorder_obj->approver;
            $salesorders[$i]['approverdate'] = $salesorder_obj->appr_date;
            $salesorders[$i]['remarks'] = $salesorder_obj->remarks;
            $salesorders[$i]['status'] = $salesorder_obj->status;
            $salesorders[$i]['appruser_id'] = $salesorder_obj->appruser_id;
            $salesorders[$i]['dep_id'] = $salesorder_obj->dep_id;
            $salesorders[$i]['extuser_id'] = $salesorder_obj->extuser_id;

            $salesorderitem_objs = DB::table('sales_order_items')
                ->leftJoin('items', 'sales_order_items.item_id', '=', 'items.id')
                ->select('sales_order_items.item_qty as item_qty', 'sales_order_items.remarks as remarks', 'items.*')
                ->where('sales_order_items.so_id', $salesorder_obj->id)
                ->get();

            $salesorderitems = array();
            $j = 0;
            foreach ($salesorderitem_objs as $salesorderitem_obj) {
                $salesorderitems[$j]['id'] = $salesorderitem_obj->id;
                $salesorderitems[$j]['image'] = $salesorderitem_obj->image;
                $salesorderitems[$j]['code'] = $salesorderitem_obj->code;
                $salesorderitems[$j]['name'] = $salesorderitem_obj->name;
                $salesorderitems[$j]['specification'] = $salesorderitem_obj->specification;
                $salesorderitems[$j]['unit'] = $salesorderitem_obj->unit;
                $salesorderitems[$j]['pack'] = $salesorderitem_obj->pack;
                $salesorderitems[$j]['qty'] = $salesorderitem_obj->item_qty;
                $salesorderitems[$j]['price'] = $salesorderitem_obj->price;
                $salesorderitems[$j]['remarks'] = $salesorderitem_obj->remarks;
                $j++;
            }
            $salesorders[$i]['salesorderitems'] = $salesorderitems;

            $i++;
        }

        return view('pages.page-sales-list', compact('pageConfigs', 'breadcrumbs', 'data', 'salesorders', 'internalCompany', 'externalCompany'));
    }

    public function salesorderCreate($id = 0)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Order Information"], ['name' => "New Request"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        $date = date("Y-m-d");
        $department = "";
        $department_id = 0;
        if (auth()->user()->role == 'external') {
            $department_obj = Department::find(auth()->user()->dep_id);
            $department = $department_obj->name;
            $department_id = auth()->user()->dep_id;
        }
        // else return back();

        //        if (!$id) {
        //            $salesorder = new SalesOrder();
        //            $salesorder -> dep_id = $department_id;
        //            $salesorder -> dn_id = 0;
        //            $salesorder -> extuser_id = Auth::id();
        //            $salesorder -> status = 0;
        //            $salesorder -> save();
        //        }
        //        else {
        //            $salesorder = SalesOrder::find($id);
        //        }

        $approvers = DB::table('users')
            ->where('role', 'external')
            ->where('appr_role', '!=', 0)
            ->where('dep_id', auth()->user()->dep_id)
            ->get();

        $data = ['date' => $date, 'salesorder' => "", 'department' => $department, 'approvers' => $approvers];
        $costcenters = Costcenter::where('dep_id', auth()->user()->dep_id)->orderBy('code', 'asc')->get();
        $categories = Category::all();

        return view('pages.page-sales-create', compact('pageConfigs', 'breadcrumbs', 'internalCompany', 'externalCompany', 'data', 'costcenters', 'categories'));
    }

    public function createSalesOrder(Request $request)
    {
        $costcentre = $request->costcentre;
        $approver = 1;
        $remarks = $request->remarks;
        $items = $request->input('items', []);

        $totalAmount = 0;

        foreach ($items as $item) {
            $totalAmount += $item['price'] * $item['qty'];
        }

        session([
            'costcentre' => $costcentre,
            'remarks' => $remarks,
            'totalAmount' => $totalAmount
        ]);

        /*
		if ($totalAmount > 100000) {
			$over_total_message = "The total amount exceeds the limit of $100,000!";
			return view('pages.page-sales-create', compact('over_total_message'));

		}
		*/


        $salesorder = new SalesOrder();
        $department_id = 0;
        if (auth()->user()->role == 'external') {
            $department_id = auth()->user()->dep_id;
        }
        $salesorder->dep_id = $department_id;
        $salesorder->dn_id = 0;
        $salesorder->extuser_id = Auth::id();
        $salesorder->status = 0;
        $nodate = date('Ymd');
        $salesorder->cc_id = $costcentre;
        $salesorder->remarks = $remarks;
        $salesorder->appruser_id = $approver;
        $salesorder->dn_id = 0;
        $salesorder->request_date = $request->filled('request_date') ? $request->request_date : date("Y-m-d");
        if ($request->filled('extuser_id')) {
            $salesorder->extuser_id = $request->extuser_id;
        }

        if ($salesorder->save()) {
            $so_id = $salesorder->id;
            $salesorder->no = Helper::getSerialNumber($salesorder->getTable(), 'no', 'SO-');
            $salesorder->save();
            $items = $request->items;
            $dn_id = $salesorder->dn_id;

            DB::table('sales_order_items')->where('so_id', $so_id)->delete();
            for ($i = 0; $i < count($items); $i++) {
                if ($items[$i]['qty']) {
                    $salesorderitem = new SalesOrderItem();
                    $salesorderitem->so_id = $so_id;
                    $salesorderitem->dn_id = $dn_id;
                    $salesorderitem->item_id = $items[$i]['id'];
                    $salesorderitem->item_qty = $items[$i]['qty'];
                    $salesorderitem->remarks = $items[$i]['remark'];

                    $salesorderitem->save();
                }
            }
        }

        //dd($salesorder);

        $user_approver = User::find($approver);
        $approver_email = $user_approver->email;
        $approver_name = $user_approver->username;

        $user_creator = User::find($salesorder->extuser_id);
        $creator_email = $user_creator->email;
        $creator_name = $user_creator->username;

        $manager = User::where('dep_id', $salesorder->dep_id)->where('appr_role', 2)->first(); // appr_role: 2 means Manager
        if ($manager) {
            $manager_name = $manager->username;
            $manager_email = $manager->email;

            $data = array('approver_name' => $manager_name, 'creator_name' => $creator_name);
            try {
                Mail::send('email.requestapprove', $data, function ($message) use ($manager_name, $manager_email, $creator_email) {
                    $message->to($manager_email, $manager_name)
                        ->subject('Request For Approval Of Sales Order From Orders & Inventory System');
                    $message->from($creator_email, 'Request For Approval Of Sales Order From Orders & Inventory System');
                });

                $data = array('approver_name' => $approver_name, 'creator_name' => $creator_name);
                Mail::send('email.requestapprove', $data, function ($message) use ($approver_name, $approver_email, $creator_email) {
                    $message->to($approver_email, $approver_name)
                        ->subject('Request For Approval Of Sales Order From Orders & Inventory System');
                    $message->from($creator_email, 'Request For Approval Of Sales Order From Orders & Inventory System');
                });
            } catch (Exception $e) {
            }
        }
        $result = ['result' => 'success'];

        return response()->json($result);
    }

    public function createSalesOrder_process(Request $request)
    {
        $costcentreId = session('costcentre');
        $remarks = session('remarks');
        $totalAmount = session('totalAmount');

        // 使用 Eloquent 查找对应的 CostCenter
        $costCenter = CostCenter::find($costcentreId);

        // 检查是否找到对应的 CostCenter
        if ($costCenter) {
            $costCentreCode = $costCenter->code; // 获取 code
        } else {
            // 如果没有找到，处理错误或者给一个默认值
            $costCentreCode = 'Not found';
        }

        // 获取当前登录用户的部门ID
        $currentUserDeptId = auth()->user()->dep_id;

        // 根据 totalAmount 确定 approver 的级别并检查部门ID
        if ($totalAmount >= 1 && $totalAmount <= 1000) {
            $approvers = User::where('appr_role', 1)
                ->where('dep_id', $currentUserDeptId)
                ->orderBy('username', 'asc')
                ->get();
        } elseif ($totalAmount >= 1001 && $totalAmount <= 25000) {
            $approvers = User::where('appr_role', 2)
                ->where('dep_id', $currentUserDeptId)
                ->orderBy('username', 'asc')
                ->get();
        } elseif ($totalAmount >= 25001 && $totalAmount <= 100000) {
            $approvers = User::where('appr_role', 3)
                ->where('dep_id', $currentUserDeptId)
                ->orderBy('username', 'asc')
                ->get();
        } else {
            $approvers = collect(); // 空集合，如果没有匹配的范围
        }

        return view('pages.page-salesorder_create_process', compact('costCentreCode', 'remarks', 'totalAmount', 'approvers', 'currentUserDeptId'));
    }

    public function SOCupdateApprover(Request $request)
    {
        $approverId = $request->input('approver');

        // 获取当前用户最后创建的 sales_order 记录的 ID
        $lastSalesOrder = SalesOrder::where('extuser_id', Auth::id())
            ->latest()
            ->first();

        if ($approverId !== '0' && $lastSalesOrder) {
            $lastSalesOrder->appruser_id = $approverId;
            $lastSalesOrder->save();

            return redirect('/my-order');
        }

        return redirect()->back()->with('error', 'Invalid operation.');
    }

    public function approveSales(Request $request, $id)
    {
        // 查找指定ID的銷售訂單
        $salesOrder = SalesOrder::find($id);

        // 檢查該銷售訂單是否存在
        if ($salesOrder) {
            // 更新該訂單的狀態為1
            $salesOrder->status = 1;
            // 保存更新
            $salesOrder->save();

            // 返回成功訊息
            return redirect('/my-order');
        }

        // 如果訂單不存在，返回錯誤訊息
        //return response()->json(['message' => 'Sales order not found.'], 404);
    }




    public function salesorderUpdate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Sales Order"], ['name' => "Update Sales Order Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        $date = date("Y-m-d");
        $salesorder_obj = DB::table('sales_orders')
            ->leftJoin('departments', 'sales_orders.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'sales_orders.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'sales_orders.extuser_id', '=', 'extusers.id')
            ->leftJoin('users as approvers', 'sales_orders.appruser_id', '=', 'approvers.id')
            ->select('sales_orders.*', 'departments.name as department', 'costcenters.name as costcentre', 'extusers.username as extusername', 'approvers.username as approver')
            ->where('sales_orders.id', $id)
            ->get();

        $salesorder['id'] = $salesorder_obj[0]->id;
        $salesorder['dn_id'] = $salesorder_obj[0]->dn_id;
        $salesorder['no'] = $salesorder_obj[0]->no;
        $salesorder['orderdate'] = $salesorder_obj[0]->created_at;
        $salesorder['department'] = $salesorder_obj[0]->department;
        $salesorder['costcentre'] = $salesorder_obj[0]->costcentre;
        $salesorder['extusername'] = $salesorder_obj[0]->extusername;
        $salesorder['approver'] = $salesorder_obj[0]->approver;
        $salesorder['approverdate'] = $salesorder_obj[0]->appr_date;
        $salesorder['remarks'] = $salesorder_obj[0]->remarks;
        $salesorder['status'] = $salesorder_obj[0]->status;
        $salesorder['request_date'] = $salesorder_obj[0]->request_date ? date('Y-m-d', strtotime($salesorder_obj[0]->request_date)) : date('Y-m-d');

        $salesorderitem_objs = DB::table('sales_order_items')
            ->leftJoin('items', 'sales_order_items.item_id', '=', 'items.id')
            ->select('sales_order_items.item_qty as item_qty', 'sales_order_items.remarks as remarks', 'items.*')
            ->where('sales_order_items.so_id', $salesorder_obj[0]->id)
            ->get();

        $salesorderitems = array();
        $j = 0;
        foreach ($salesorderitem_objs as $salesorderitem_obj) {
            $salesorderitems[$j]['id'] = $salesorderitem_obj->id;
            $salesorderitems[$j]['code'] = $salesorderitem_obj->code;
            $salesorderitems[$j]['name'] = $salesorderitem_obj->name;
            $salesorderitems[$j]['specification'] = $salesorderitem_obj->specification;
            $salesorderitems[$j]['unit'] = $salesorderitem_obj->unit;
            $salesorderitems[$j]['pack'] = $salesorderitem_obj->pack;
            $salesorderitems[$j]['qty'] = $salesorderitem_obj->item_qty;
            $salesorderitems[$j]['price'] = $salesorderitem_obj->price;
            $salesorderitems[$j]['remark'] = $salesorderitem_obj->remarks;
            $j++;
        }
        $salesorder['salesorderitems'] = $salesorderitems;

        $approvers = DB::table('users')
            ->where('role', 'external')
            ->orWhere('admin_role', 0)
            ->get();

        $staffs = DB::table('users')
            // ->where('role', 'external')
            ->pluck('username', 'id');

        $data = ['date' => $date];
        $costcenters = Costcenter::all();
        $categories = Category::all();

        return view('pages.page-sales-update', compact('pageConfigs', 'staffs', 'breadcrumbs', 'internalCompany', 'externalCompany', 'data', 'costcenters', 'categories', 'salesorder', 'approvers'));
    }

    public function salesorderItemUpdate(Request $request)
    {

        $salesorderitem_objs = SalesOrderItem::where('sales_order_items.item_id', $request->data["itemId"])
            ->where('sales_order_items.so_id', $request->data["so_id"])
            ->first();
        if (isset($request->data["qty"])) {
            $salesorderitem_objs->item_qty = $request->data["qty"];
        }
        if (isset($request->data["remarks"])) {
            $salesorderitem_objs->remarks = $request->data["remarks"];
        }
        $salesorderitem_objs->save();
    }

    public function salesorderReport($status = "initial")
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Order Information"], ['name' => "Order History"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        if ($status == 'initial') {
            $sql = 'select si.id id, so.no order_no, so.dep_id dep_id, so.extuser_id user_id, uu.username user, c.name costcentre, so.created_at order_date, i.code item_code, i.specification specification, si.item_qty request_qty, i.unit unit, i.pack packing, i.price unit_price, u.username approver, so.appr_date approve_date, so.dn_id dn_id, so.dn_no dn_no, so.dn_date dn_date, so.cc_id cc_id, i.id item_id from sales_orders so
            left join sales_order_items si on so.id=si.so_id
            left join items i on i.id=si.item_id
            left join costcenters c on c.id=so.cc_id
            left join users u on u.id=so.appruser_id
            left join users uu on uu.id=so.extuser_id
            where so.no IS NOT NULL';

            if (auth()->user()->role == 'external' && auth()->user()->appr_role) $sql .= ' and so.dep_id = ' . auth()->user()->dep_id;

            if (auth()->user()->role == 'external' && auth()->user()->appr_role == 0) $sql .= ' and so.extuser_id = ' . auth()->user()->id;

            $sql .= " order by so.dn_date desc";

            $salesorders_result = DB::select($sql);
            // dd($salesorders_result); exit();

            $rep_code = auth()->user()->id . '_' . date('Ymd');
            DB::table('sales_order_reports')->where('rep_code', '=', $rep_code)->delete();

            $salesorders = array();
            $j = 0;
            foreach ($salesorders_result as $salesorders_item) {
                $salesorders[$j]['id'] = $salesorders_item->id;
                $salesorders[$j]['order_no'] = $salesorders_item->order_no;
                $salesorders[$j]['user'] = $salesorders_item->user;
                $salesorders[$j]['costcentre'] = $salesorders_item->costcentre;
                $salesorders[$j]['order_date'] = $salesorders_item->order_date;
                $salesorders[$j]['item_code'] = $salesorders_item->item_code;
                $salesorders[$j]['specification'] = $salesorders_item->specification;
                $salesorders[$j]['request_qty'] = $salesorders_item->request_qty;
                $salesorders[$j]['unit'] = $salesorders_item->unit;
                $salesorders[$j]['packing'] = $salesorders_item->packing;
                $salesorders[$j]['unit_price'] = $salesorders_item->unit_price;
                $salesorders[$j]['total_price'] = $salesorders_item->unit_price * $salesorders_item->request_qty;
                $salesorders[$j]['approver'] = $salesorders_item->approver;
                $salesorders[$j]['approve_date'] = $salesorders_item->approve_date;
                $salesorders[$j]['dn_id'] = $salesorders_item->dn_id;
                $salesorders[$j]['dn_no'] = $salesorders_item->dn_no;
                $salesorders[$j]['dn_date'] = $salesorders_item->dn_date;

                $salesorderreport = new SalesOrderReport();
                $salesorderreport->si_id = $salesorders_item->id;
                $salesorderreport->order_no = $salesorders_item->order_no;
                $salesorderreport->user = $salesorders_item->user;
                $salesorderreport->costcentre = $salesorders_item->costcentre;
                $salesorderreport->order_date = $salesorders_item->order_date;
                $salesorderreport->item_code = $salesorders_item->item_code;
                $salesorderreport->specification = isset($salesorders_item->specification) ? $salesorders_item->specification : '';
                $salesorderreport->request_qty = $salesorders_item->request_qty;
                $salesorderreport->unit = $salesorders_item->unit;
                $salesorderreport->packing = $salesorders_item->packing;
                $salesorderreport->unit_price = $salesorders_item->unit_price;
                $salesorderreport->total_price = $salesorders_item->unit_price * $salesorders_item->request_qty;
                $salesorderreport->approver = $salesorders_item->approver;
                $salesorderreport->approve_date = $salesorders_item->approve_date;
                $salesorderreport->dn_id = $salesorders_item->dn_id;
                $salesorderreport->dn_no = $salesorders_item->dn_no;
                $salesorderreport->dn_date = $salesorders_item->dn_date;
                $salesorderreport->cc_id = $salesorders_item->cc_id;
                $salesorderreport->item_id = $salesorders_item->item_id;
                $salesorderreport->dep_id = $salesorders_item->dep_id;
                $salesorderreport->user_id = $salesorders_item->user_id;
                $salesorderreport->rep_code = auth()->user()->id . '_' . date('Ymd');

                if (isset($salesorders_item->id)) {
                    $salesorderreport->save();
                }

                $salesorders[$j]['rep_id'] = $salesorderreport->id;
                $salesorders[$j]['rep_code'] = auth()->user()->id . '_' . date('Ymd');

                $j++;
            }
        } else {
            $rep_code = $status;
            $salesorders = SalesOrderReport::where('rep_code', $rep_code)->get();
        }

        $salesorder_objs = DB::table('sales_orders')
            ->leftJoin('departments', 'sales_orders.dep_id', '=', 'departments.id')
            ->leftJoin('costcenters', 'sales_orders.cc_id', '=', 'costcenters.id')
            ->leftJoin('users as extusers', 'sales_orders.extuser_id', '=', 'extusers.id')
            ->leftJoin('users as approvers', 'sales_orders.appruser_id', '=', 'approvers.id')
            ->select('sales_orders.*', 'departments.name as department', 'costcenters.code as costcentre', 'extusers.username as extusername', 'approvers.username as approver')
            ->whereIn('sales_orders.status', [3, 4])
            ->where('sales_orders.no', '!=', '')
            ->orderBy('sales_orders.dn_date', 'DESC')
            ->get();


        $salesorders_new = array();
        $i = 0;
        foreach ($salesorder_objs as $salesorder_obj) {
            if (auth()->user()->role == 'external' && auth()->user()->admin_role == 0 && auth()->user()->appr_role == 0 && auth()->user()->id != $salesorder_obj->extuser_id) continue;
            //dd((auth() -> user()->toArray()));
            if (auth()->user()->role == 'external' && auth()->user()->admin_role == 0 && auth()->user()->dep_id != $salesorder_obj->dep_id) continue;

            $salesorders_new[$i]['id'] = $salesorder_obj->id;
            $salesorders_new[$i]['no'] = $salesorder_obj->no;
            $salesorders_new[$i]['orderdate'] = $salesorder_obj->dn_date;
            $salesorders_new[$i]['department'] = $salesorder_obj->department;
            $salesorders_new[$i]['costcentre'] = $salesorder_obj->costcentre;
            $salesorders_new[$i]['extusername'] = $salesorder_obj->extusername;
            $salesorders_new[$i]['approver'] = $salesorder_obj->approver;
            $salesorders_new[$i]['approverdate'] = $salesorder_obj->appr_date;
            $salesorders_new[$i]['remarks'] = $salesorder_obj->remarks;
            $salesorders_new[$i]['status'] = $salesorder_obj->status;
            $salesorders_new[$i]['appruser_id'] = $salesorder_obj->appruser_id;
            $salesorders_new[$i]['dep_id'] = $salesorder_obj->dep_id;
            $salesorders_new[$i]['extuser_id'] = $salesorder_obj->extuser_id;

            $salesorderitem_objs = DB::table('sales_order_items')
                ->leftJoin('items', 'sales_order_items.item_id', '=', 'items.id')
                ->leftJoin('sales_order_reports', 'sales_order_items.id', '=', 'sales_order_reports.si_id')
                ->select('sales_order_reports.id as so_re', 'sales_order_items.item_qty as item_qty', 'sales_order_items.remarks as remarks', 'items.*')
                ->where('sales_order_items.so_id', $salesorder_obj->id)
                ->get();

            $salesorderitems = array();
            $j = 0;
            foreach ($salesorderitem_objs as $salesorderitem_obj) {
                $salesorderitems[$j]['id'] = $salesorderitem_obj->id;
                $salesorderitems[$j]['so_re'] = $salesorderitem_obj->so_re;
                $salesorderitems[$j]['name'] = $salesorderitem_obj->name;
                $salesorderitems[$j]['specification'] = $salesorderitem_obj->specification;
                $salesorderitems[$j]['unit'] = $salesorderitem_obj->unit;
                $salesorderitems[$j]['pack'] = $salesorderitem_obj->pack;
                $salesorderitems[$j]['qty'] = $salesorderitem_obj->item_qty;
                $salesorderitems[$j]['price'] = $salesorderitem_obj->price;
                $salesorderitems[$j]['remarks'] = $salesorderitem_obj->remarks;
                $j++;
            }
            $salesorders_new[$i]['salesorderitems'] = $salesorderitems;
            $i++;
        }

        $costcenters = Costcenter::all();

        $items = Item::all();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-sales-report', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'salesorders', 'salesorders_new', 'costcenters', 'items'));
    }

    public function getReports(Request $request)
    {
        $costcentre = $request->costcentre;
        $item = $request->item;
        $so_from = $request->so_from;
        $so_to = $request->so_to;
        $dn_from = $request->dn_from;
        $dn_to = $request->dn_to;

        $sql = 'select * from sales_order_reports where rep_code = "' . auth()->user()->id . '_' . date('Ymd') . '"';

        if (auth()->user()->role == 'external' && auth()->user()->appr_role) $sql .= ' dep_id = ' . auth()->user()->dep_id;

        if (auth()->user()->role == 'external' && auth()->user()->appr_role == 0) $sql .= ' user_id = ' . auth()->user()->id;

        if ($costcentre) $sql .= ' and cc_id = ' . $costcentre;

        if ($item) $sql .= ' and item_id = ' . $item;

        $salesorders_result = DB::select($sql);

        $salesorders = array();
        $j = 0;
        foreach ($salesorders_result as $salesorders_item) {
            $orderdate_array = explode(' ', $salesorders_item->order_date);
            $orderdate = $orderdate_array[0];

            $notedate_array = explode(' ', $salesorders_item->dn_date);
            $notedate = $notedate_array[0];

            $orderdatefrom_flag = 1;
            $orderdateto_flag = 1;

            $notedatefrom_flag = 1;
            $notedateto_flag = 1;

            if ($so_from != '') {
                $so_from_array = explode('/', $so_from);
                $so_from_text = $so_from_array[2] . '-' . $so_from_array[1] . '-' . $so_from_array[0];

                if ($so_from_text <= $orderdate) $orderdatefrom_flag = 1;
                else $orderdatefrom_flag = 0;
            }

            if ($so_to != '') {
                $so_to_array = explode('/', $so_to);
                $so_to_text = $so_to_array[2] . '-' . $so_to_array[1] . '-' . $so_to_array[0];

                if ($so_to_text >= $orderdate) $orderdateto_flag = 1;
                else $orderdateto_flag = 0;
            }

            if ($dn_from != '') {
                $dn_from_array = explode('/', $dn_from);
                $dn_from_text = $dn_from_array[2] . '-' . $dn_from_array[1] . '-' . $dn_from_array[0];

                if ($dn_from_text <= $notedate && $notedate_array) $notedatefrom_flag = 1;
                else $notedatefrom_flag = 0;
            }

            if ($dn_to != '') {
                $dn_to_array = explode('/', $dn_to);
                $dn_to_text = $dn_to_array[2] . '-' . $dn_to_array[1] . '-' . $dn_to_array[0];

                if ($dn_to_text >= $notedate && $notedate_array) $notedateto_flag = 1;
                else $notedateto_flag = 0;
            }

            if ($orderdatefrom_flag && $orderdateto_flag && $notedatefrom_flag && $notedateto_flag) {
                $salesorders[$j]['id'] = $salesorders_item->id;
                $salesorders[$j]['order_no'] = $salesorders_item->order_no;
                $salesorders[$j]['user'] = $salesorders_item->user;
                $salesorders[$j]['costcentre'] = $salesorders_item->costcentre;
                $salesorders[$j]['order_date'] = $salesorders_item->order_date;
                $salesorders[$j]['item_code'] = $salesorders_item->item_code;
                $salesorders[$j]['specification'] = $salesorders_item->specification;
                $salesorders[$j]['request_qty'] = $salesorders_item->request_qty;
                $salesorders[$j]['unit'] = $salesorders_item->unit;
                $salesorders[$j]['packing'] = $salesorders_item->packing;
                $salesorders[$j]['unit_price'] = $salesorders_item->unit_price;
                $salesorders[$j]['total_price'] = $salesorders_item->total_price;
                $salesorders[$j]['approver'] = $salesorders_item->approver;
                $salesorders[$j]['approve_date'] = $salesorders_item->approve_date;
                $salesorders[$j]['dn_id'] = $salesorders_item->dn_id;
                $salesorders[$j]['dn_no'] = $salesorders_item->dn_no;
                $salesorders[$j]['dn_date'] = $salesorders_item->dn_date;
                $j++;
            }
        }

        //return response() -> json($salesorders);
    }

    public function salesorderSpecialhandling($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Sales Order"], ['name' => "Sales Order Special Handling"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $salesorderreport = SalesOrderReport::find($id);
        $salesorderitem = SalesOrderItem::find($salesorderreport->si_id);
        $salesorder = SalesOrder::find($salesorderitem->so_id);

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-sales-special-handling', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'salesorderreport', 'salesorder'));
    }

    public function salesorderreportUpdate(Request $request)
    {
        $salesorderreport = SalesOrderReport::find($request->rep_id);

        $salesorderreport->user = $request->user;
        $salesorderreport->costcentre = $request->costcentre;
        $salesorderreport->order_date = $request->order_date;
        $salesorderreport->item_code = $request->item_code;
        $salesorderreport->specification = $request->specification;
        $salesorderreport->request_qty = $request->request_qty;
        $salesorderreport->unit = $request->unit;
        $salesorderreport->packing = $request->packing;
        $salesorderreport->unit_price = $request->unit_price;
        $salesorderreport->total_price = $request->total_price;
        $salesorderreport->approver = $request->approver;
        $salesorderreport->approve_date = $request->approve_date;
        $salesorderreport->dn_no = $request->dn_no;
        $salesorderreport->dn_date = $request->dn_date;

        $salesorderreport->save();

        if ($request->dn_no != null) {
            $sql = 'select u.* from users u left join sales_orders so on so.appruser_id = u.id left join sales_order_items si on si.so_id = so.id left join sales_order_reports sr on sr.si_id = si.id where sr.id = ' . $request->rep_id;

            $approver = DB::select($sql);

            $approver_email = $approver[0]->email;
            $approver_name = $approver[0]->username;

            $user_creator = User::find($salesorderreport->user_id);
            $creator_email = $user_creator->email;
            $creator_name = $user_creator->username;

            $int_users = User::where('rcvemail', 1)->get();

            foreach ($int_users as $int_user) {
                $int_user_email = $int_user->email;
                $int_user_name = $int_user->username;

                $data = array('int_user' => $int_user_name, 'update_user' => $creator_name);
                Mail::send('email.updatesalesorder', $data, function ($message) use ($int_user_name, $creator_email, $int_user_email) {
                    $message->to($int_user_email, $int_user_name)
                        ->subject('Resubmit Of Sales Order From Orders & Inventory System');
                    $message->from($creator_email, 'Resubmit Of Sales Order From Orders & Inventory System');
                });
            }
        }

        if ($request->status == 5) {
            $salesorderreport = SalesOrderReport::find($request->rep_id);
            $salesorderitem = SalesOrderItem::find($salesorderreport->si_id);
            $salesorder = SalesOrder::find($salesorderitem->so_id);

            $salesorder->status = 5;
            $salesorder->save();

            $int_users = User::where('rcvemail', 1)->get();

            foreach ($int_users as $int_user) {
                $int_user_email = $int_user->email;
                $int_user_name = $int_user->username;

                $creator_email = auth()->user()->email;
                $creator_name = auth()->user()->username;

                $data = array('int_user' => $int_user_name, 'update_user' => $creator_name);
                Mail::send('email.cancelsalesorder', $data, function ($message) use ($int_user_name, $creator_email, $int_user_email) {
                    $message->to($int_user_email, $int_user_name)
                        ->subject('Cancel Of Sales Order From Orders & Inventory System');
                    $message->from($creator_email, 'Cancel Of Sales Order From Orders & Inventory System');
                });
            }
        }

        return redirect('/order-history/' . $salesorderreport->rep_code);
    }

    public function salesorderRegister(Request $request)
    {

        $costcentre = $request->costcentre;
        $approver = $request->approver;
        $remarks = $request->remarks;
        $so_id = $request->so_id;
        $dn_id = $request->dn_id;

        $salesorder = SalesOrder::find($so_id);

        $salesorder->no = Helper::getSerialNumber($salesorder->getTable(), 'no', 'SO-');
        $salesorder->cc_id = $costcentre;
        $salesorder->remarks = $remarks;
        $salesorder->appruser_id = $approver;
        $salesorder->dn_id = $dn_id;
        $salesorder->request_date = $request->filled('request_date') ? $request->request_date : date("Y-m-d");
        if ($request->filled('extuser_id')) {
            $salesorder->extuser_id = $request->extuser_id;
        }

        $salesorder->save();

        if (isset($request->itemId) && count($request->itemId) > 0) {
            for ($i = 0; $i < count($request->itemId); $i++) {
                $salesorderitem_objs = SalesOrderItem::where('sales_order_items.item_id', $request->itemId[$i])->where('sales_order_items.so_id', $so_id)->first();
                if (isset($salesorderitem_objs)) {
                    if (isset($request->qty[$i])) {
                        $salesorderitem_objs->item_qty = $request->qty[$i];
                    }
                    if (isset($request->remark[$i])) {
                        $salesorderitem_objs->remarks = $request->remark[$i];
                    }
                    $salesorderitem_objs->save();
                }
            }
        }

        //if remove items
        if ($request->remove_so_item && isset($request->remove_so_item)) {
            $remove_so_items_array = explode(",", $request->remove_so_item);
            if (count($remove_so_items_array) > 0) {
                for ($i = 0; $i < count($remove_so_items_array); $i++) {
                    DB::table('sales_order_items')->where('item_id', $remove_so_items_array[$i])->delete();
                    // dd(count($remove_so_items_array),$remove_so_items_array,$request->remove_so_item);
                }
            }
        }


        $user_approver = User::find($approver);
        $approver_email = $user_approver->email;
        $approver_name = $user_approver->username;

        $user_creator = User::find($salesorder->extuser_id);
        $creator_email = $user_creator->email;
        $creator_name = $user_creator->username;

        $manager = User::where('dep_id', $salesorder->dep_id)->where('appr_role', 2)->first(); // appr_role: 2 means Manager
        if ($manager) {
            $manager_name = $manager->username;
            $manager_email = $manager->email;

            $data = array('approver_name' => $manager_name, 'creator_name' => $creator_name);
            try {
                Mail::send('email.requestapprove', $data, function ($message) use ($manager_name, $manager_email, $creator_email) {
                    $message->to($manager_email, $manager_name)
                        ->subject('Request For Approval Of Sales Order From Orders & Inventory System');
                    $message->from($creator_email, 'Request For Approval Of Sales Order From Orders & Inventory System');
                });

                $data = array('approver_name' => $approver_name, 'creator_name' => $creator_name);
                Mail::send('email.requestapprove', $data, function ($message) use ($approver_name, $approver_email, $creator_email) {
                    $message->to($approver_email, $approver_name)
                        ->subject('Request For Approval Of Sales Order From Orders & Inventory System');
                    $message->from($creator_email, 'Request For Approval Of Sales Order From Orders & Inventory System');
                });
            } catch (Exception $e) {
            }
        }
        return redirect('/my-order');
    }

    public function soItems(Request $request)
    {
        $salesorderitem_objs = DB::table('sales_order_items')
            ->leftJoin('items', 'sales_order_items.item_id', '=', 'items.id')
            ->select('sales_order_items.item_qty as item_qty', 'sales_order_items.remarks as remarks', 'items.*')
            ->where('sales_order_items.so_id', $request->data)
            ->get();

        $salesorderitems = array();
        $j = 0;
        foreach ($salesorderitem_objs as $salesorderitem_obj) {
            $salesorderitems[$j]['id'] = $salesorderitem_obj->id;
            $salesorderitems[$j]['code'] = $salesorderitem_obj->code;
            $salesorderitems[$j]['name'] = $salesorderitem_obj->name;
            $salesorderitems[$j]['specification'] = isset($salesorderitem_obj->specification) ? $salesorderitem_obj->specification : '';
            $salesorderitems[$j]['unit'] = $salesorderitem_obj->unit;
            $salesorderitems[$j]['pack'] = $salesorderitem_obj->pack;
            $salesorderitems[$j]['qty'] = $salesorderitem_obj->item_qty;
            $salesorderitems[$j]['price'] = $salesorderitem_obj->price;
            $salesorderitems[$j]['remark'] = $salesorderitem_obj->remarks;
            $j++;
        }
        $result = ['result' => 'success', 'items' => $salesorderitems];
        return response()->json($result);
    }

    public function saveItems(Request $request)
    {

        $dn_id = $request->dn_id;
        $so_id = $request->so_id;
        $items = $request->items;
        $remarks = $request->remarks;
        $approver = $request->approver;
        $costcentre = $request->costcentre;


        // dd($items,
        // $dn_id,
        // $remarks,
        // $approver,
        // $costcentre,
        // $request->itemId,
        // $request->remark,
        // $request->qty,
        // $request->remove_so_item);

        $salesorder = SalesOrder::find($so_id);

        $salesorder->dn_id = $dn_id;
        $salesorder->remarks = $remarks;
        $salesorder->cc_id = $costcentre;
        $salesorder->appruser_id = $approver;

        $salesorder->save();
        // DB::table('sales_order_items')->where('so_id', $so_id)->delete();
        // dd($items);

        if (isset($items)) {
            for ($i = 0; $i < count($items); $i++) {
                if ($items[$i]['qty']) {
                    $salesorderitem = new SalesOrderItem();
                    $salesorderitem->so_id = $so_id;
                    $salesorderitem->dn_id = $dn_id;
                    $salesorderitem->item_id = $items[$i]['id'];
                    $salesorderitem->item_qty = $items[$i]['qty'];
                    $salesorderitem->remarks = $items[$i]['remark'];

                    $exist = DB::table('sales_order_items')->where('item_id', $items[$i]['id'])->where('so_id', $so_id)->count();
                    if ($exist <= 0) {
                        $salesorderitem->save();
                    }
                }
            }
        }

        if (isset($request->itemId) && count($request->itemId) > 0) {
            for ($i = 0; $i < count($request->itemId); $i++) {
                $salesorderitem_objs = SalesOrderItem::where('sales_order_items.item_id', $request->itemId[$i])->where('sales_order_items.so_id', $so_id)->first();
                if (isset($salesorderitem_objs) && (isset($request->qty[$i]) || isset($request->remark[$i]))) {
                    if (isset($request->qty[$i])) {
                        $salesorderitem_objs->item_qty = $request->qty[$i];
                    }
                    if (isset($request->remark[$i])) {
                        $salesorderitem_objs->remarks = $request->remark[$i];
                    }
                    $salesorderitem_objs->save();
                }
            }
        }

        //if remove items
        if ($request->remove_so_item && isset($request->remove_so_item)) {
            $remove_so_items_array = explode(",", $request->remove_so_item);
            if (count($remove_so_items_array) > 0) {
                for ($i = 0; $i < count($remove_so_items_array); $i++) {
                    DB::table('sales_order_items')->where('item_id', $remove_so_items_array[$i])->delete();
                    // dd(count($remove_so_items_array),$remove_so_items_array,$request->remove_so_item);
                }
            }
        }

        $result = ['result' => 'success'];

        return response()->json($result);
    }

    public function approveSO(Request $request)
    {
        $id = $request->id;
        $salesorder = SalesOrder::find($id);
        $salesorder->status = 1;
        $salesorder->appr_date = date('Y-m-d h:m:s');
        $salesorder->save();

        $salesorderitem_objs = DB::table('sales_order_items')
            ->leftJoin('items', 'sales_order_items.item_id', '=', 'items.id')
            ->select('sales_order_items.item_qty as item_qty', 'sales_order_items.remarks as remarks', 'items.*')
            ->where('sales_order_items.so_id', $salesorder->id)
            ->get();

        foreach ($salesorderitem_objs as $salesorderitem_obj) {
            $item = Item::find($salesorderitem_obj->id);
            if ($item) {
                $item->stock = $salesorderitem_obj->stock - $salesorderitem_obj->item_qty;
                $item->save();
            }
        }

        $approve_user = User::find($salesorder->appruser_id);
        $approver_email = $approve_user->email;
        $approver_name = $approve_user->username;

        $int_users = User::where('rcvemail', 1)->get();

        try {
            foreach ($int_users as $int_user) {
                $int_user_email = $int_user->email;
                $int_user_name = $int_user->username;

                $data = array('int_user' => $int_user_name, 'approve_user' => $approver_name);
                Mail::send('email.requestdeliver', $data, function ($message) use ($int_user_name, $approver_email, $int_user_email) {
                    $message->to($int_user_email, $int_user_name)
                        ->subject('Request For Deliver Of Sales Order From Orders & Inventory System');
                    $message->from($approver_email, 'Request For Deliver Of Sales Order From Orders & Inventory System');
                });
            }
        } catch (Exception $e) {
        }
        $result = ['result' => 'success'];

        return response()->json($result);
    }

    public function rejectSO(Request $request)
    {
        $id = $request->id;
        $salesorder = SalesOrder::find($id);
        $salesorder->status = 4;
        $salesorder->save();

        $result = ['result' => 'success'];

        return response()->json($result);
    }

    public function printPdf($id)
    {
        // 更新查询以包拨 costcenters 表，并以适当的逻辑进行连接
        $sql = "SELECT so.*, u.telephone AS tel, u.username AS user, dp.name AS department, cc.code AS costcenter_code
				FROM sales_orders so
				LEFT JOIN users u ON u.id = so.extuser_id
				LEFT JOIN departments dp ON dp.id = so.dep_id
				LEFT JOIN sales_order_reports sor ON sor.order_no = so.no
				LEFT JOIN costcenters cc ON cc.name = sor.costcentre
				WHERE so.id = " . $id;
        $salesorderreports = DB::select($sql);

        $sql = "SELECT soi.item_qty AS qty, i.*
				FROM sales_order_items soi
				LEFT JOIN items i ON i.id = soi.item_id
				WHERE soi.so_id = " . $id;
        $items = DB::select($sql);

        $internalcompany = InternalCompany::all();
        $externalcompany = ExternalCompany::all();

        $data = [
            'title' => 'Sales Order Report',
            'heading' => 'Sales Order Report',
            'content' => $salesorderreports[0],
            'internalcompany' => $internalcompany[0],
            'externalcompany' => $externalcompany[0],
            'items' => $items
        ];

        $pdf = PDF::setOptions([
            'images' => true,
            'isRemoteEnabled' => true
        ])->loadView('pdf.salesorder_pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download('salesorderreport_' . date('Ymdhms') . '.pdf');
    }

    public function printExcel()
    {
        return Excel::download(new SalesOrderReportsExport, 'salesorderreport_' . date('Ymdhms') . '.xlsx');
    }

    public function CleanData()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('optimize:clear');
        Artisan::call('optimize');
        die('Done');
    }
}

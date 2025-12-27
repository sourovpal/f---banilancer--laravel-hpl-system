<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Costcenter;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use App\Models\InternalCompany;
use App\Models\ExternalCompany;

class CostcenterController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this->internalcompany = InternalCompany::all();
        $this->externalcompany = ExternalCompany::all();
    }

    public function costcenterList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "costcenter & Item"], ['name' => "Current costcenter List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $sql = "select cc.*, d.name department from costcenters cc left join departments d on d.id=cc.dep_id ORDER BY created_at DESC";
        $costcenters = DB::select($sql);

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-costcenter-list', compact('pageConfigs', 'breadcrumbs', 'costcenters', 'internalCompany', 'externalCompany'));
    }

    public function costcenterCreate()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Costcenter & Item"], ['name' => "New Costcenter"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $departments = Department::all();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-costcenter-create', compact('pageConfigs', 'breadcrumbs', 'departments', 'internalCompany', 'externalCompany'));
    }

    public function costcenterUpdate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "costcenter & Item"], ['name' => "Update costcenter Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $costcenter = Costcenter::find($id);

        $departments = Department::all();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-costcenter-update', compact('pageConfigs', 'breadcrumbs', 'costcenter', 'departments', 'internalCompany', 'externalCompany'));
    }

    public function costcenterRegister(Request $request)
    {
        $costcentername = $request->costcentername;
        $costcentercode = $request->costcentercode;
        $floor = $request->floor;
        $build = $request->build;
        $department = $request->department;

        $costcenter = new costcenter();
        $costcenter->name = $costcentername;
        $costcenter->code = $costcentercode;
        $costcenter->floor = $floor;
        $costcenter->build = $build;
        $costcenter->dep_id = $department;
        $costcenter->save();

        // $cc_id = $costcenter -> id;
        // $date = date('Ymd');
        // $code = '';
        // if ($cc_id / 10 < 1) $code = '000' . $cc_id;
        // else if ($cc_id / 100 < 1) $code = '00' . $cc_id;
        // else if ($cc_id / 1000 < 1) $code = '0' . $cc_id;
        // else $code = $cc_id;

        // $costcenter -> code = 'CS-CC-' . $date . $code;
        // $costcenter -> save();

        return redirect('/costcenter-list');
    }

    public function costcenterUpdateAction(Request $request)
    {
        $id = $request->id;
        $costcentername = $request->costcentername;
        $costcentercode = $request->costcentercode;
        $floor = $request->floor;
        $build = $request->build;
        $department = $request->department;

        $costcenter = Costcenter::find($id);
        $costcenter->name = $costcentername;
        $costcenter->code = $costcentercode;
        $costcenter->floor = $floor;
        $costcenter->build = $build;
        $costcenter->dep_id = $department;
        $costcenter->save();

        return redirect('/costcenter-list');
    }
}

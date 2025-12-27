<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\InternalCompany;
use App\Models\ExternalCompany;

class DepartmentController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this->internalcompany = InternalCompany::all();
        $this->externalcompany = ExternalCompany::all();
    }

    public function departmentList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Department & Item"], ['name' => "Current Department List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $departments = Department::orderBy('created_at', 'desc')->get();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-department-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'departments'));
    }

    public function departmentCreate()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Department & Item"], ['name' => "New Department"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $data = ['error' => 0];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-department-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'data'));
    }

    public function departmentUpdate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Department & Item"], ['name' => "Update Department Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $department = Department::find($id);

        $data = ['error' => 0];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-department-update', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'department', 'data'));
    }

    public function departmentRegister(Request $request)
    {
        $departmentcode = $request->departmentcode;
        $departmentname = $request->departmentname;
        $floor = $request->floor;
        $build = $request->build;

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        $department = Department::where('code', $departmentcode)->get();
        if (count($department)) {
            $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Department & Item"], ['name' => "Create Department Record"]];

            $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

            $data = ['error' => 1];
            return view('pages.page-department-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'data'));
        } else {
            $department = new Department();
            $department->code = $departmentcode;
            $department->name = $departmentname;
            $department->floor = $floor;
            $department->build = $build;
            $department->save();

            return redirect('/department-list');
        }
    }

    public function departmentUpdateAction(Request $request)
    {
        $id = $request->id;
        $departmentcode = $request->departmentcode;
        $departmentname = $request->departmentname;
        $floor = $request->floor;
        $build = $request->build;

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        $department = Department::where('code', $departmentcode)->get();
        if (count($department)) {
            $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Department & Item"], ['name' => "Create Department Record"]];

            $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

            $department = Department::find($id);

            $data = ['error' => 1];
            return view('pages.page-department-update', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'department', 'data'));
        } else {
            $department = Department::find($id);
            $department->code = $departmentcode;
            $department->name = $departmentname;
            $department->floor = $floor;
            $department->build = $build;
            $department->save();

            return redirect('/department-list');
        }
    }
}

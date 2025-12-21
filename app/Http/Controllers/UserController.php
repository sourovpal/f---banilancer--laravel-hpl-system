<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\InternalCompany;
use App\Models\ExternalCompany;

class UserController extends Controller
{
private $internalcompany;
private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
$this -> internalcompany = InternalCompany::all();
$this -> externalcompany = ExternalCompany::all();
    }

    public function masterusersList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Administration"], ['name' => "Master Users List"]];

$pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $users = DB::table('users')
                     ->where('role', 'master')
                     ->orderBy('created_at', 'desc')
                     ->get();

        $userrole = auth() -> user() -> role;

$internalCompany = $this -> internalcompany[0] -> name;
$externalCompany = $this -> externalcompany[0] -> name;

        if ($userrole == 'master') return view('pages.page-master-users-list',  compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'users'));
        else return back();
    }

public function internalusersList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Administration"], ['name' => "Internal Users List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $userrole = auth() -> user() -> role;

$internalCompany = $this -> internalcompany[0] -> name;
$externalCompany = $this -> externalcompany[0] -> name;

        if ($userrole == 'master') {
            $users = DB::table('users')
                     ->where('role', 'internal')
                     ->orderBy('created_at', 'desc')
                     ->get();
            return view('pages.page-internal-users-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'users'));
        }
        else if ($userrole == 'external') {
            return back();
        }
        else {
            if (auth() -> user() -> admin_role) {
                $users = DB::table('users')
                     ->where('role', 'internal')
                     ->get();
                return view('pages.page-internal-users-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'users'));
            }
            else {
                if (auth() -> user() -> role == 'internal') {
                    $users = DB::table('users')
                        ->where('id', auth() -> user() -> id)
                        ->get();
                    return view('pages.page-internal-users-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'users'));
                }
                else return back();
            }
        }
    }

    public function externalusersList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Administration"], ['name' => "External Users List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $userrole = auth() -> user() -> role;

$internalCompany = $this -> internalcompany[0] -> name;
$externalCompany = $this -> externalcompany[0] -> name;

        if ($userrole == 'master') {
            $users = DB::table('users')
                    ->leftJoin('departments', 'users.dep_id', '=', 'departments.id')
                    ->select('users.*', 'departments.name as department')
                    ->where('users.role', 'external')
                    ->orderBy('created_at', 'desc')
                    ->get();
            return view('pages.page-external-users-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'users'));
        }
        else if ($userrole == 'internal') {
            return back();
        }
        else {
            if (auth() -> user() -> admin_role) {
                $users = DB::table('users')
                    ->leftJoin('departments', 'users.dep_id', '=', 'departments.id')
                    ->select('users.*', 'departments.name as department')
                    ->where('users.role', 'external')
                    ->get();
                return view('pages.page-external-users-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'users'));
            }
            else {
                if (auth() -> user() -> role == 'external') {
                    $users = DB::table('users')
                        ->leftJoin('departments', 'users.dep_id', '=', 'departments.id')
                        ->select('users.*', 'departments.name as department')
                        ->where('users.id', auth() -> user() -> id)
                        ->get();
                    return view('pages.page-external-users-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'users'));
                }
                else return back();
            }
        }
    }
    public function usersView()
    {
        $breadcrumbs = [
            ['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Administration"], ['name' => "Users View"]];
        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        return view('pages.page-users-view', ['pageConfigs' => $pageConfigs], ['breadcrumbs' => $breadcrumbs]);
    }
    public function usersEdit($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Administration"], ['name' => "Users Edit"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $user = User::find($id);
        $departments = Department::all();

$internalCompany = $this -> internalcompany[0] -> name;
$externalCompany = $this -> externalcompany[0] -> name;

        return view('pages.page-users-edit', compact('pageConfigs', 'breadcrumbs', 'internalCompany', 'externalCompany', 'user', 'departments'));
    }

    public function usersCreate()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Administration"], ['name' => "Users Create"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $departments = Department::all();

        $userrole = auth() -> user() -> role;

$internalCompany = $this -> internalcompany[0] -> name;
$externalCompany = $this -> externalcompany[0] -> name;

        if ($userrole == 'master') return view('pages.page-users-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'departments'));
        else {
            if (auth() -> user() -> admin_role) return view('pages.page-users-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'departments'));
            else return back();
        }
    }

    public function userRegister(Request $request) {
        $role = $request -> role;
        $usercode = $request -> usercode;
        $userid = $request -> userid;
        $username = $request -> username;
        $password = $request -> password;

        if ($role == "master") {
            $systemname = $request -> systemname;
            $systemcode = $request -> systemcode;
            $email = $request -> useremail;
        }
        else if ($role == "internal") {
            $email = $request -> useremail;
        }
        else {
            $email = $request -> useremail;
            $telephone = $request -> telephone;
        }

        $user = new User();
        $user -> usercode = $usercode;
        $user -> username = $username;
        $user -> userid = $userid;
        $user -> email = $email;
        $user -> role = $role;

        if ($role == "master") {
            $user -> systemname = $systemname;
            $user -> systemcode = $systemcode;
        }
        else if ($role == "internal") {
            $user -> admin_role = $request -> admin_role;
            $user -> rcvemail = $request -> rcvemail == 'on' ? 1 : 0;
        }
        else {
            $user -> telephone = $telephone;
            $user -> admin_role = $request -> admin_role;
            $user -> appr_role = $request -> approver_role;
            $user -> dep_id = $request -> department;
        }

        $user -> password = bcrypt($password);
        $user -> status = 1;

        $user -> save();

        if ($role == "master") return redirect('/page-master-users-list');
        if ($role == "internal") return redirect('/page-internal-users-list');
        else return redirect('/page-external-users-list');
    }

    public function userUpdateAction(Request $request) {
        $role = $request -> role;
        $usercode = $request -> usercode;
        $userid = $request -> userid;
        $username = $request -> username;
        $password = $request -> password;
        $email = $request -> useremail;
        $status = $request -> status;

        if ($role == "master") {
            $id = $request -> mas_id;
            $systemname = $request -> systemname;
            $systemcode = $request -> systemcode;
        }
        else if ($role == "internal") {
            $id = $request -> int_id;
        }
        else {
            $id = $request -> ext_id;
            $telephone = $request -> telephone;
        }
        $user = User::find($id);
        $user -> usercode = $usercode;
        $user -> userid = $userid;
        $user -> username = $username;
        $user -> email = $email;
        $user -> role = $role;

        if ($role == "master") {
            $user -> systemname = $systemname;
            $user -> systemcode = $systemcode;
        }
        else if ($role == "internal") {
            $user -> admin_role = $request -> admin_role;
            $user -> rcvemail= $request -> rcvemail == 'on' ? 1 : 0;
        }
        else {
            $user -> telephone = $telephone;
            $user -> admin_role = $request -> admin_role;
            $user -> appr_role = $request -> approver_role;
            $user -> dep_id = $request -> department;
        }

        if ($request->filled('password')) {
            $user -> password = bcrypt($password);
        }
        $user -> status = $status;

        $user -> save();

        if ($role == "master") return redirect('/page-master-users-list');
        if ($role == "internal") return redirect('/page-internal-users-list');
        else return redirect('/page-external-users-list');
    }

    public function userDelete($id) {
        $user = User::find($id);
        $role = $user -> role;
        $user -> delete();
        if ($role == "master") return redirect('/page-master-users-list');
        if ($role == "internal") return redirect('/page-internal-users-list');
        else return redirect('/page-external-users-list');
    }
}

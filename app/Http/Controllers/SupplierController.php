<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\InternalCompany;
use App\Models\ExternalCompany;

class SupplierController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this->internalcompany = InternalCompany::all();
        $this->externalcompany = ExternalCompany::all();
    }

    public function supplierList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Supplier"], ['name' => "Current Supplier List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $suppliers = Supplier::orderBy('englishname', 'ASC')->get();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-supplier-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'suppliers'));
    }

    public function supplierCreate()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Supplier"], ['name' => "New Supplier"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-supplier-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs'));
    }

    public function supplierUpdate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Supplier"], ['name' => "Update Supplier Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $supplier = Supplier::find($id);

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-supplier-update', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'supplier'));
    }

    public function supplierRegister(Request $request)
    {
        $suppliercode = $request->suppliercode;
        $englishname = $request->englishname;
        $englishaddress = $request->englishaddress;
        $telephone1 = $request->telephone1;
        $suppliercontact = $request->suppliercontact;
        $suppliermobile = $request->suppliermobile;
        $supplieremail = $request->supplieremail;
        $chinaname = $request->chinaname;
        $chinaaddress = $request->chinaaddress;
        $telephone2 = $request->telephone2;
        $supplierfax = $request->supplierfax;
        $supplierremarks = $request->supplierremarks;

        $supplier = new Supplier();
        $supplier->code = $suppliercode;
        $supplier->englishname = $englishname;
        $supplier->englishaddress = $englishaddress;
        $supplier->telephone1 = $telephone1;
        $supplier->contact = $suppliercontact;
        $supplier->mobile = $suppliermobile;
        $supplier->email = $supplieremail;
        $supplier->chinaname = $chinaname;
        $supplier->chinaaddress = $chinaaddress;
        $supplier->telephone2 = $telephone2;
        $supplier->fax = $supplierfax;
        $supplier->remarks = $supplierremarks;
        $supplier->save();

        return redirect('/supplier-list');
    }

    public function supplierUpdateAction(Request $request)
    {
        $id = $request->id;
        $suppliercode = $request->suppliercode;
        $englishname = $request->englishname;
        $englishaddress = $request->englishaddress;
        $telephone1 = $request->telephone1;
        $suppliercontact = $request->suppliercontact;
        $suppliermobile = $request->suppliermobile;
        $supplieremail = $request->supplieremail;
        $chinaname = $request->chinaname;
        $chinaaddress = $request->chinaaddress;
        $telephone2 = $request->telephone2;
        $supplierfax = $request->supplierfax;
        $supplierremarks = $request->supplierremarks;

        $supplier = Supplier::find($id);
        $supplier->code = $suppliercode;
        $supplier->englishname = $englishname;
        $supplier->englishaddress = $englishaddress;
        $supplier->telephone1 = $telephone1;
        $supplier->contact = $suppliercontact;
        $supplier->mobile = $suppliermobile;
        $supplier->email = $supplieremail;
        $supplier->chinaname = $chinaname;
        $supplier->chinaaddress = $chinaaddress;
        $supplier->telephone2 = $telephone2;
        $supplier->fax = $supplierfax;
        $supplier->remarks = $supplierremarks;
        $supplier->save();

        return redirect('/supplier-list');
    }
}

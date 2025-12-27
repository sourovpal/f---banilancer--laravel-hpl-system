<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ExternalCompany;
use App\Models\InternalCompany;
use App\Models\Item;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this->internalcompany = InternalCompany::all();
        $this->externalcompany = ExternalCompany::all();
    }

    public function itemList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Category & Item"], ['name' => "Current Item List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        $items = DB::table('items')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->select('items.*', 'categories.name as category')
            ->orderBy('code', 'ASC')
            ->get();

        $po_info = DB::table('purchase_order_items')
            ->leftJoin('purchase_orders', 'purchase_order_items.po_id', '=', 'purchase_orders.id')
            ->select('purchase_order_items.*')
            ->where('purchase_orders.status', 0)
            ->get()
            ->groupBy('item_id');


        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-item-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'items', 'po_info'));
    }

    public function itemCreate()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Category & Item"], ['name' => "New Item"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        $categories = Category::orderBy('name', 'ASC')->get();
        $error = ['error' => 'no'];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-item-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'categories', 'error'));
    }

    public function itemUpdate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Category & Item"], ['name' => "Update Item Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
        $item = Item::find($id);
        $categories = Category::orderBy('name', 'ASC')->get();
        $image_name_array = explode('___', $item->image);
        $image_name = isset($image_name_array[1]) ? $image_name_array[1] : '';
        $error = ['error' => 'no', 'image_name' => $image_name];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-item-update', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'item', 'categories', 'error'));
    }

    public function itemtransactionReport()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Category & Item"], ['name' => "Item Transaction"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $sql = "select it.*, s.code supplier, i.name item from item_transactions it left join suppliers s on it.supplier=s.id left join items i on i.id=it.item_id ORDER BY it.created_at DESC";
        $itemtransactions = DB::select($sql);

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-transaction-report', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'itemtransactions'));
    }

    public function itemRegister(Request $request)
    {
        $category = $request->category;
        // $itemcode = $request -> itemcode;
        $itemunit = $request->itemunit;
        $itemprice = $request->itemprice;
        $itemmin = $request->itemmin;
        $itemname = $request->itemname;
        $itempack = $request->itempack;
        $itemgl = $request->itemgl;
        // $itemstack = $request -> itemstack;
        $itemlocation = $request->itemlocation;
        $itemspecification = $request->itemspecification;
        $itemimage = $request->itemimage;
        $itemRemark = $request->itemRemark;

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        if ($itemimage == null) {
            $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Category & Item"], ['name' => "Create Item Record"]];

            $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
            $categories = Category::all();

            $error = ['error' => 'image'];

            return view('pages.page-item-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'categories', 'error'));
        }

        if ($category == 0) {
            $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Category & Item"], ['name' => "Create Item Record"]];

            $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
            $categories = Category::all();

            $error = ['error' => 'category'];

            return view('pages.page-item-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'categories', 'error'));
        }

        if ($request->itemimage != null) {
            /*$itemimage = $request -> file('itemimage');
            $filename = date('Ymdhms') . '___' . $itemimage -> getClientOriginalName();
            Storage::disk('local')->putFileAs(
                'public/upload/item/',
                $itemimage,
                $filename
            );*/
            $image = $request->file('itemimage');
            $imageName = time() . '.' . $image->extension(); // Generate a unique name for the file
            $image->move(public_path('upload/item'), $imageName); // Move the uploaded file to a folder
            $filename = $imageName;
        }

        $item = new Item();
        $item->category_id = $category;
        $item->unit = $itemunit;
        $item->price = $itemprice;
        $item->min = $itemmin;
        $item->name = $itemname;
        $item->pack = $itempack;
        $item->gl = $itemgl;
        $item->location = $itemlocation;
        $item->specification = $itemspecification;
        $item->remark = $itemRemark;
        $item->status = 0;
        $item->image = $filename;
        $item->save();

        $item_id = $item->id;

        $itdate = date('Ymd');
        $code = '';
        if ($item_id / 10 < 1) $code = '000' . $item_id;
        else if ($item_id / 100 < 1) $code = '00' . $item_id;
        else if ($item_id / 1000 < 1) $code = '0' . $item_id;
        else $code = $item_id;

        $item->code = 'CS-IT-' . $itdate . $code;
        $item->save();

        return redirect('/item-list');
    }

    public function ItemUpdateAction(Request $request)
    {
        $id = $request->id;
        $category = $request->category;
        $itemunit = $request->itemunit;
        $itemprice = $request->itemprice;
        $itemmin = $request->itemmin;
        $itemname = $request->itemname;
        $itempack = $request->itempack;
        $itemgl = $request->itemgl;
        $itemlocation = $request->itemlocation;
        $itemspecification = $request->itemspecification;
        $itemRemark = $request->itemRemark;

        $item = Item::find($id);

        if ($category == 0) {
            $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Category & Item"], ['name' => "Create Item Record"]];

            $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];
            $categories = Category::all();

            $error = ['error' => 'category'];

            return view('pages.page-item-create', compact('pageConfigs', 'breadcrumbs', 'categories', 'error'));
        }

        if ($request->itemimage != '') {
            $old_image = $item->image;
            /*Storage::delete('public/upload/item/' . $old_image);
            $itemimage = $request -> file('itemimage');
            $filename = date('Ymdhms') . '___' . $itemimage -> getClientOriginalName();
            Storage::disk('local')->putFileAs(
                'public/upload/item/',
                $itemimage,
                $filename
            );*/

            File::delete(public_path('upload/item/' . $old_image));
            $image = $request->file('itemimage');
            $imageName = time() . '.' . $image->extension(); // Generate a unique name for the file
            $image->move(public_path('upload/item'), $imageName); // Move the uploaded file to a folder
            $item->image = $imageName;
        }

        $item->category_id = $category;
        $item->unit = $itemunit;
        $item->price = $itemprice;
        $item->min = $itemmin;
        $item->name = $itemname;
        $item->pack = $itempack;
        $item->gl = $itemgl;
        $item->location = $itemlocation;
        $item->specification = $itemspecification;
        $item->remark = $itemRemark;
        $item->status = 0;
        $item->save();

        return redirect('/item-list');
    }

    public function updateItemRemarks(Request $request)
    {
        $id = $request->id;
        $itemRemark = $request->itemRemark;
        $item = Item::find($id);
        $item->remark = $itemRemark;
        $item->save();
    }
    public function getItems(Request $request)
    {
        $category = $request->category;
        $itemCode = $request->item_code;
        $name = $request->name;
        $specification = $request->specification;

        $sql = 'select * from items';

        if ($category) $sql .= ' where category_id = ' . $category;
        else $sql .= ' where category_id != 0 ';

        if ($itemCode) $sql .= ' and code LIKE  "%' . $itemCode . '%"';

        if ($name != '') $sql .= ' and name LIKE "%' . $name . '%"';

        if ($specification != '') $sql .= ' and specification LIKE  "%' . $specification . '%"';

        $sql .= ' ORDER BY name ASC';

        // dd($sql);

        $items = DB::select($sql);

        return response()->json($items);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\InternalCompany;
use App\Models\ExternalCompany;

class CategoryController extends Controller
{
    private $internalcompany;
    private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
        $this->internalcompany = InternalCompany::all();
        $this->externalcompany = ExternalCompany::all();
    }

    public function categoryList()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Category & Item"], ['name' => "Current Category List"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $categories = Category::orderBy('created_at', 'desc')->get();

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-category-list', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'categories'));
    }

    public function categoryCreate()
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Category & Item"], ['name' => "Create Category Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-category-create', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs'));
    }

    public function categoryUpdate($id)
    {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Category & Item"], ['name' => "Update Category Record"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $category = Category::find($id);

        $internalCompany = $this->internalcompany[0]->name;
        $externalCompany = $this->externalcompany[0]->name;

        return view('pages.page-category-update', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'category'));
    }

    public function categoryRegister(Request $request)
    {
        echo "sdf";
        $categorycode = $request->categorycode;
        $categoryname = $request->categoryname;

        $category = new Category();
        $category->code = $categorycode;
        $category->name = $categoryname;
        $category->save();

        return redirect('/category-list');
    }

    public function categoryUpdateAction(Request $request)
    {
        $id = $request->id;
        $categorycode = $request->categorycode;
        $categoryname = $request->categoryname;

        $category = Category::find($id);
        $category->code = $categorycode;
        $category->name = $categoryname;
        $category->save();

        return redirect('/category-list');
    }
}

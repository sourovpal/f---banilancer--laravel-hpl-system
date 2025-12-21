<?php

namespace App\Http\Controllers;
use App\Models\InternalCompany;
use App\Models\ExternalCompany;

class DashboardController extends Controller
{
private $internalcompany;
private $externalcompany;
    public function __construct()
    {
        $this->middleware(['auth']);
$this -> internalcompany = InternalCompany::all();
$this -> externalcompany = ExternalCompany::all();
    }

    public function dashboardModern()
    {
        return view('/pages/dashboard-modern');
    }

    public function dashboardEcommerce()
    {
        // navbar large
        $pageConfigs = ['navbarLarge' => false];

        return view('/pages/dashboard-ecommerce', ['pageConfigs' => $pageConfigs]);
    }

    public function dashboardAnalytics()
    {
        // navbar large
        $pageConfigs = ['navbarLarge' => false];

$internalCompany = $this -> internalcompany[0] -> name;
$externalCompany = $this -> externalcompany[0] -> name;

return view('/pages/dashboard-analytics', compact('pageConfigs', 'internalCompany', 'externalCompany'));
    }
}

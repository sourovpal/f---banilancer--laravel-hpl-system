<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InternalCompany;
use App\Models\ExternalCompany;
use Illuminate\Support\Facades\Storage;

class CompanyInformationController extends Controller
{
private $internalcompany;
private $externalcompany;

    public function __construct()
    {
        $this->middleware('auth');
$this -> internalcompany = InternalCompany::all();
$this -> externalcompany = ExternalCompany::all();
    }

    public function showInternalCompany() {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Company Information"], ['name' => "Internal Company Information"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $company = InternalCompany::all();

$internalCompany = $this -> internalcompany[0] -> name;
$externalCompany = $this -> externalcompany[0] -> name;

        return view('pages.page-internal-company-information', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'company'));
    }

    public function showExternalCompany() {
        $breadcrumbs = [['link' => "modern", 'name' => "Home"], ['link' => "javascript:void(0)", 'name' => "Company Information"], ['name' => "Internal Company Information"]];

        $pageConfigs = ['pageHeader' => true, 'isFabButton' => true];

        $company = ExternalCompany::all();

$internalCompany = $this -> internalcompany[0] -> name;
$externalCompany = $this -> externalcompany[0] -> name;

        return view('pages.page-external-company-information', compact('pageConfigs', 'internalCompany', 'externalCompany', 'breadcrumbs', 'company'));
    }

    public function updateCompanyInformation(Request $request) {
        $name = $request -> name;
        $add1 = $request -> add1;
        $add2 = $request -> add2;
        $add3 = $request -> add3;
        $tel = $request -> tel;
        $fax = $request -> fax;
        $role = $request -> role;
        // $mailDriver = $request -> mail_driver;
        // $mailHost = $request -> mail_host;
        // $mailPort = $request -> mail_port;
        // $mailUsername = $request -> mail_username;
        // $mailPassword = $request -> mail_password;
        // $mailEncryption = $request -> mail_encryption;

        $company;
        if ($role == 'internal') {
            $company = InternalCompany::all();
            if (count($company)) $company = $company[0];
            else $company = new InternalCompany();
        }
        else {
            $company = ExternalCompany::all();
            if (count($company)) $company = $company[0];
            else $company = new ExternalCompany();
        }

        if ($request -> logo != '') {
            $logo = $request -> file('logo');
            if ($role == 'internal') $filename = 'Internal_Company_Logo' . '.' . $logo -> getClientOriginalExtension();
            else $filename = 'External_Company_Logo' . '.' . $logo -> getClientOriginalExtension();
            // Storage::disk('public')->putFileAs(
            //     'upload/company/logo/',
            //     $logo,
            //     $filename
            // );
            $logo->move('images/logo', $filename);

            $company -> logo = $logo -> getClientOriginalExtension();
        }

        $company -> name = $name;
        $company -> add1 = $add1;
        $company -> add2 = $add2;
        $company -> add3 = $add3;
        $company -> tel = $tel;
        $company -> fax = $fax;


        $this->setEnv('MAIL_DRIVER', $request -> mail_driver);
        $this->setEnv('MAIL_HOST', $request -> mail_host);
        $this->setEnv('MAIL_PORT', $request -> mail_port);
        $this->setEnv('MAIL_USERNAME', $request -> mail_username);
        $this->setEnv('MAIL_PASSWORD', $request -> mail_password);
        $this->setEnv('MAIL_ENCRYPTION', $request -> mail_encryption);

        // $company -> mail_driver = $mailDriver;
        // $company -> mail_host = $mailHost;
        // $company -> mail_port = $mailPort;
        // $company -> mail_username = $mailUsername;
        // $company -> mail_password = $mailPassword;
        // $company -> mail_encryption = $mailEncryption;

        $company -> save();

        if ($role == 'internal') return redirect('/internal-company-information');
        else return redirect('/external-company-information');
    }

    public function setEnv($name, $value)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                $name . '=' . env($name), $name . '=' . $value, file_get_contents($path)
            ));
        }
    }
}

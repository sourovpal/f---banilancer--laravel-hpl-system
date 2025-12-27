<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\QuotationController;

use App\Exports\ItemsExport;

use App\Http\Controllers\ReportController;;

use App\Exports\DeliveryReportsExport;
use App\Helpers\Helper;
use App\Http\Controllers\AdvanceUiController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BasicTableController;
use App\Http\Controllers\BasicUiController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CompanyInformationController;
use App\Http\Controllers\CostcenterController;
use App\Http\Controllers\CssController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataTableController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ExtraComponentsController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MiscController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Models\GoodReceive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/code', function () {
    return view('auth.login2');
});
Route::get('/clean-data', 'SalesController@CleanData');

// Dashboard Route
// Route::get('/', [DashboardController::class, 'dashboardModern']);
Route::get('/', [DashboardController::class, 'dashboardAnalytics']);
Route::get('/modern', [DashboardController::class, 'dashboardModern']);
Route::get('/ecommerce', [DashboardController::class, 'dashboardEcommerce']);
Route::get('/analytics', [DashboardController::class, 'dashboardAnalytics']);

Auth::routes(['verify' => true]);



// Route::get('/foo', function () {
//     Artisan::call('storage:link');
//     die('Done');
// });

// Application Route
Route::get('/app-email', [ApplicationController::class, 'emailApp']);
Route::get('/app-email/content', [ApplicationController::class, 'emailContentApp']);
Route::get('/app-chat', [ApplicationController::class, 'chatApp']);
Route::get('/app-todo', [ApplicationController::class, 'todoApp']);
Route::get('/app-kanban', [ApplicationController::class, 'kanbanApp']);
Route::get('/app-file-manager', [ApplicationController::class, 'fileManagerApp']);
Route::get('/app-contacts', [ApplicationController::class, 'contactApp']);
Route::get('/app-calendar', [ApplicationController::class, 'calendarApp']);
Route::get('/app-invoice-list', [ApplicationController::class, 'invoiceList']);
Route::get('/app-invoice-view', [ApplicationController::class, 'invoiceView']);
Route::get('/app-invoice-edit', [ApplicationController::class, 'invoiceEdit']);
Route::get('/app-invoice-add', [ApplicationController::class, 'invoiceAdd']);
Route::get('/eCommerce-products-page', [ApplicationController::class, 'ecommerceProduct']);
Route::get('/eCommerce-pricing', [ApplicationController::class, 'eCommercePricing']);

// User profile Route
Route::get('/user-profile-page', [UserProfileController::class, 'userProfile']);

// Page Route
Route::get('/page-contact', [PageController::class, 'contactPage']);
Route::get('/page-blog-list', [PageController::class, 'pageBlogList']);
Route::get('/page-search', [PageController::class, 'searchPage']);
Route::get('/page-knowledge', [PageController::class, 'knowledgePage']);
Route::get('/page-knowledge/licensing', [PageController::class, 'knowledgeLicensingPage']);
Route::get('/page-knowledge/licensing/detail', [PageController::class, 'knowledgeLicensingPageDetails']);
Route::get('/page-timeline', [PageController::class, 'timelinePage']);
Route::get('/page-faq', [PageController::class, 'faqPage']);
Route::get('/page-faq-detail', [PageController::class, 'faqDetailsPage']);
Route::get('/page-account-settings', [PageController::class, 'accountSetting']);
Route::get('/page-blank', [PageController::class, 'blankPage']);
Route::get('/page-collapse', [PageController::class, 'collapsePage']);

// Media Route
Route::get('/media-gallery-page', [MediaController::class, 'mediaGallery']);
Route::get('/media-hover-effects', [MediaController::class, 'hoverEffect']);

// User Route
Route::get('/page-master-users-list', [UserController::class, 'masterusersList']);
Route::get('/page-internal-users-list', [UserController::class, 'internalusersList']);
Route::get('/page-external-users-list', [UserController::class, 'externalusersList']);
Route::get('/page-users-view', [UserController::class, 'usersView']);
Route::get('/page-users-edit/{id}', [UserController::class, 'usersEdit']);
Route::get('/page-users-delete/{id}', [UserController::class, 'userDelete']);
Route::get('/new-user', [UserController::class, 'usersCreate']);
Route::post('/user_register', [UserController::class, 'userRegister']);
Route::post('/user_update', [UserController::class, 'userUpdateAction']);

// Authentication Route
Route::get('/user-login', [AuthenticationController::class, 'userLogin']);
Route::get('/user-logout', [LoginController::class, 'logout']);
Route::get('/user-register', [AuthenticationController::class, 'userRegister']);
Route::get('/user-forgot-password', [AuthenticationController::class, 'forgotPassword']);
Route::get('/user-lock-screen', [AuthenticationController::class, 'lockScreen']);

// Sale Order Route
Route::get('/my-order', [SalesController::class, 'salesorderList']);
Route::get('/new-request/{id?}', [SalesController::class, 'salesorderCreate']);
Route::get('/create-sales-order-record-overtotal', [SalesController::class, 'salesorderCreate']);
Route::get('/update-sales-order-record/{id}', [SalesController::class, 'salesorderUpdate']);
Route::get('/order-history/{status?}', [SalesController::class, 'salesorderReport']);
Route::get('/special-sales-order-handling/{id}', [SalesController::class, 'salesorderSpecialhandling']);
Route::post('/registerItems', [SalesController::class, 'registerItems']);
Route::post('/so-itemUpdate', [SalesController::class, 'salesorderItemUpdate']);
Route::post('/salesorder_register', [SalesController::class, 'salesorderRegister']);
Route::post('/salesorder_create', [SalesController::class, 'createSalesOrder']);
Route::get('/salesorder_create_process', [SalesController::class, 'createSalesOrder_process']); //E
Route::post('/saveItems', [SalesController::class, 'saveItems']);
Route::post('/so_items', [SalesController::class, 'soItems']);
Route::post('/approveSO', [SalesController::class, 'approveSO']);
Route::post('/rejectSO', [SalesController::class, 'rejectSO']);
Route::post('/getSalesOrderReports', [SalesController::class, 'getReports']);
Route::post('/salesorderreport_update', [SalesController::class, 'salesorderreportUpdate']);

Route::get('/printPdfSalesOrder/{id}', [SalesController::class, 'printPdf']);
Route::get('/printExcelSalesOrder', [SalesController::class, 'printExcel']);
Route::get('/update-sales-approve/{id}', [SalesController::class, 'approveSales'])->name('sales.approve');

// Delivery Note Route
Route::get('/current-dn', [DeliveryController::class, 'deliverynoteList']);
Route::get('/create-delivery-note-record', [DeliveryController::class, 'deliverynoteCreate']);
Route::get('/update-delivery-note-record/{id}', [DeliveryController::class, 'deliverynoteUpdate']);
Route::get('/dn-history/{status?}', [DeliveryController::class, 'deliverynoteReport']);
Route::get('/special-delivery-note-handling/{id}', [DeliveryController::class, 'deliverynoteSpecialhandling']);
Route::get('/create-deliver-note/{id}', [DeliveryController::class, 'createDelivernote']);
Route::get('/create-deliver-note-from-quotation/{id}', [DeliveryController::class, 'createDelivernoteFromQuotation']);
Route::post('/getDeliveryNoteReports', [DeliveryController::class, 'getReports']);
Route::get('/make-delivery/{id}', [DeliveryController::class, 'makeDelivery']);
Route::post('/deliverynotereport_update', [DeliveryController::class, 'deliverynotereportUpdate']);
Route::post('/bulkDeliverynotereportpdf', [DeliveryController::class, 'bulkDeliveryNoteReportPdf']);

Route::get('/printPdfDeliveryNote/{id}', [DeliveryController::class, 'printPdf']);
Route::get('/printExcelDeliveryNote', [DeliveryController::class, 'printExcel'])->name('delivery-report-excel.export');

// Purchase Order Route
Route::get('/current-po', [PurchaseController::class, 'purchaseorderList']);
Route::get('/new-po/{id?}', [PurchaseController::class, 'purchaseorderCreate']);
Route::get('/update-purchase-order-record/{id}', [PurchaseController::class, 'purchaseorderUpdate']);
Route::get('/po-history/{status?}', [PurchaseController::class, 'purchaseorderReport']);
Route::post('/purchaseorder_register', [PurchaseController::class, 'purchaseorderRegister']);
Route::post('/purchaseorder_itemUpdate', [PurchaseController::class, 'purchaseorderItemUpdate']);
Route::post('/purchaseorder_create', [PurchaseController::class, 'createPurchaseOrder']);
Route::post('/po_items', [PurchaseController::class, 'poItems']);
Route::post('/savePOItems', [PurchaseController::class, 'savePOItems']);
Route::post('/getPurchaseOrderReports', [PurchaseController::class, 'getReports']);

Route::get('/printPdfPurchaseOrder/{id}', [PurchaseController::class, 'printPdf']);
Route::get('/printExcelPurchaseOrder', [PurchaseController::class, 'printExcel']);


// Report Section
Route::prefix('report')->group(function () {
    Route::get('/sale-order', [ReportController::class, 'saleOrder']);
    Route::post('/sale-order', [ReportController::class, 'saleOrderExport']);
    Route::get('/purchase-order', [ReportController::class, 'purchaseOrder']);
    Route::post('/purchase-order', [ReportController::class, 'purchaseOrderExport']);
    Route::get('/delivery-note', [ReportController::class, 'deliveryNote']);
    Route::post('/deliveryNoteExport', [ReportController::class, 'deliveryNoteExport']);
    Route::get('/good-receive', [ReportController::class, 'goodReceive'])->name('report.goodReceive');
    Route::post('/good-receive', [ReportController::class, 'goodReceiveExport'])->name('report.goodReceiveExport');
    Route::get('/cost-center', [ReportController::class, 'costCenter']);
    Route::post('/cost-center', [ReportController::class, 'costCenterExport']);
    Route::get('/by-part', [ReportController::class, 'productCode']);
    Route::post('/by-part', [ReportController::class, 'productCodeExport']);
    Route::get('/by-month-year', [ReportController::class, 'byMonthYear']);
    Route::post('/by-month-year', [ReportController::class, 'byMonthYearExport']);
    Route::get('/gr_dn', [ReportController::class, 'GR_DN'])->name('report.GR_DN');
    Route::post('/gr_dn', [ReportController::class, 'GR_DN_Export'])->name('report.GR_DN_Export');
});

// Good Receive Route
Route::get('/current-gr', [GoodController::class, 'goodreceiverList']);
Route::get('/new-gr/{id?}', [GoodController::class, 'goodreceiverCreate']);
Route::get('/update-good-receive-record/{id}', [GoodController::class, 'goodreceiverUpdate']);
Route::get('/gr-history/{status?}', [GoodController::class, 'goodreceiverReport']);
Route::post('/new-gr', [GoodController::class, 'createGoodsReceive']);
Route::get('/special-good-receive-handling/{id}', [GoodController::class, 'goodreceiveSpecialhandling']);
Route::post('/saveGRItems', [GoodController::class, 'saveGRItems']);
Route::post('/goodreceive_register', [GoodController::class, 'goodreceiveRegister']);
Route::post('/getGoodReceiveReports', [GoodController::class, 'getReports']);
Route::post('/goodreceivereport_update', [GoodController::class, 'goodreceivereportUpdate']);

Route::get('/printPdfGoodReceive/{id}', [GoodController::class, 'printPdf']);
Route::get('/printExcelGoodReceive', [GoodController::class, 'printExcel']);

// Category Route
Route::get('/category-list', [CategoryController::class, 'categoryList']);
Route::get('/new-category', [CategoryController::class, 'categoryCreate']);
Route::get('/update-category-record/{id}', [CategoryController::class, 'categoryUpdate']);
Route::post('/category_register', [CategoryController::class, 'categoryRegister']);
Route::post('/category_update', [CategoryController::class, 'categoryUpdateAction']);

// Department Route
Route::get('/department-list', [DepartmentController::class, 'departmentList']);
Route::get('/new-department', [DepartmentController::class, 'departmentCreate']);
Route::get('/update-department-record/{id}', [DepartmentController::class, 'departmentUpdate']);
Route::post('/department_register', [DepartmentController::class, 'departmentRegister']);
Route::post('/department_update', [DepartmentController::class, 'departmentUpdateAction']);

// Costcenter Route
Route::get('/costcenter-list', [CostcenterController::class, 'costcenterList']);
Route::get('/new-costcenter', [CostcenterController::class, 'costcenterCreate']);
Route::get('/update-costcenter-record/{id}', [CostcenterController::class, 'costcenterUpdate']);
Route::post('/costcenter_register', [CostcenterController::class, 'costcenterRegister']);
Route::post('/costcenter_update', [CostcenterController::class, 'costcenterUpdateAction']);

// Item Route
Route::get('/item-list', [ItemController::class, 'itemList']);
Route::get('/new-item', [ItemController::class, 'itemCreate']);
Route::get('/update-item-record/{id}', [ItemController::class, 'itemUpdate']);
Route::get('/item-transaction', [ItemController::class, 'itemtransactionReport']);
Route::post('/item_register', [ItemController::class, 'itemRegister']);
Route::post('/item_update', [ItemController::class, 'itemUpdateAction']);
Route::post('/item_remark_update', [ItemController::class, 'updateItemRemarks']);
Route::get('/getItems', [ItemController::class, 'getItems']);

// Supplier Route
Route::get('/supplier-list', [SupplierController::class, 'supplierList']);
Route::get('/new-supplier', [SupplierController::class, 'supplierCreate']);
Route::get('/update-supplier-record/{id}', [SupplierController::class, 'supplierUpdate']);
Route::post('/supplier_register', [SupplierController::class, 'supplierRegister']);
Route::post('/supplier_update', [SupplierController::class, 'supplierUpdateAction']);

// Quotation Route
Route::get('/current-quotation', [QuotationController::class, 'quotationList']);
Route::get('/new-quotation/{id?}', [QuotationController::class, 'quotationCreate']);
Route::get('/update-quotation-record/{id}', [QuotationController::class, 'quotationUpdate']);
Route::get('/quotation-report/{status?}', [QuotationController::class, 'quotationReport']);
Route::post('/quotationitem_add', [QuotationController::class, 'quotationItemRegister']);
Route::get('/quotation_item_delete/{id}', [QuotationController::class, 'quotationItemDelete']);
Route::post('/quotation_register', [QuotationController::class, 'quotationRegister']);
Route::post('/quotationItemUpdate', [QuotationController::class, 'quotationIui3temUpdate']);
Route::post('/getQuotationReports', [QuotationController::class, 'getReports']);
Route::post('/saveItemsQuotation', [QuotationController::class, 'saveItemsQuotation']);
Route::get('/getQuotationItems', [QuotationController::class, 'getQuotationItems']);
Route::get('/update-quotation-approve/{id}', [QuotationController::class, 'approveQuotation'])->name('quotation.approve');

Route::get('/printPdfQuotation/{id}', [QuotationController::class, 'printPdf']);
Route::get('/printExcelQuotation', [QuotationController::class, 'printExcel']);
Route::post('/quotation-list-render', [QuotationController::class, 'quotationListRender']);

Route::get('/quotation_create_process', [QuotationController::class, 'createQuotationOrder_process']);

Route::get('/internal', [CompanyInformationController::class, 'showInternalCompany']);
Route::get('/external', [CompanyInformationController::class, 'showExternalCompany']);
Route::post('/company_information_update', [CompanyInformationController::class, 'updateCompanyInformation']);

// Misc Route
Route::get('/page-404', [MiscController::class, 'page404']);
Route::get('/page-maintenance', [MiscController::class, 'maintenancePage']);
Route::get('/page-500', [MiscController::class, 'page500']);

// Card Route
Route::get('/cards-basic', [CardController::class, 'cardBasic']);
Route::get('/cards-advance', [CardController::class, 'cardAdvance']);
Route::get('/cards-extended', [CardController::class, 'cardsExtended']);

// Css Route
Route::get('/css-typography', [CssController::class, 'typographyCss']);
Route::get('/css-color', [CssController::class, 'colorCss']);
Route::get('/css-grid', [CssController::class, 'gridCss']);
Route::get('/css-helpers', [CssController::class, 'helpersCss']);
Route::get('/css-media', [CssController::class, 'mediaCss']);
Route::get('/css-pulse', [CssController::class, 'pulseCss']);
Route::get('/css-sass', [CssController::class, 'sassCss']);
Route::get('/css-shadow', [CssController::class, 'shadowCss']);
Route::get('/css-animations', [CssController::class, 'animationCss']);
Route::get('/css-transitions', [CssController::class, 'transitionCss']);

// Basic Ui Route
Route::get('/ui-basic-buttons', [BasicUiController::class, 'basicButtons']);
Route::get('/ui-extended-buttons', [BasicUiController::class, 'extendedButtons']);
Route::get('/ui-icons', [BasicUiController::class, 'iconsUI']);
Route::get('/ui-alerts', [BasicUiController::class, 'alertsUI']);
Route::get('/ui-badges', [BasicUiController::class, 'badgesUI']);
Route::get('/ui-breadcrumbs', [BasicUiController::class, 'breadcrumbsUI']);
Route::get('/ui-chips', [BasicUiController::class, 'chipsUI']);
Route::get('/ui-collections', [BasicUiController::class, 'collectionsUI']);
Route::get('/ui-navbar', [BasicUiController::class, 'navbarUI']);
Route::get('/ui-pagination', [BasicUiController::class, 'paginationUI']);
Route::get('/ui-preloader', [BasicUiController::class, 'preloaderUI']);

// Advance UI Route
Route::get('/advance-ui-carousel', [AdvanceUiController::class, 'carouselUI']);
Route::get('/advance-ui-collapsibles', [AdvanceUiController::class, 'collapsibleUI']);
Route::get('/advance-ui-toasts', [AdvanceUiController::class, 'toastUI']);
Route::get('/advance-ui-tooltip', [AdvanceUiController::class, 'tooltipUI']);
Route::get('/advance-ui-dropdown', [AdvanceUiController::class, 'dropdownUI']);
Route::get('/advance-ui-feature-discovery', [AdvanceUiController::class, 'discoveryFeature']);
Route::get('/advance-ui-media', [AdvanceUiController::class, 'mediaUI']);
Route::get('/advance-ui-modals', [AdvanceUiController::class, 'modalUI']);
Route::get('/advance-ui-scrollspy', [AdvanceUiController::class, 'scrollspyUI']);
Route::get('/advance-ui-tabs', [AdvanceUiController::class, 'tabsUI']);
Route::get('/advance-ui-waves', [AdvanceUiController::class, 'wavesUI']);
Route::get('/fullscreen-slider-demo', [AdvanceUiController::class, 'fullscreenSlider']);

// Extra components Route
Route::get('/extra-components-range-slider', [ExtraComponentsController::class, 'rangeSlider']);
Route::get('/extra-components-sweetalert', [ExtraComponentsController::class, 'sweetAlert']);
Route::get('/extra-components-nestable', [ExtraComponentsController::class, 'nestAble']);
Route::get('/extra-components-treeview', [ExtraComponentsController::class, 'treeView']);
Route::get('/extra-components-ratings', [ExtraComponentsController::class, 'ratings']);
Route::get('/extra-components-tour', [ExtraComponentsController::class, 'tour']);
Route::get('/extra-components-i18n', [ExtraComponentsController::class, 'i18n']);
Route::get('/extra-components-highlight', [ExtraComponentsController::class, 'highlight']);

// Basic Tables Route
Route::get('/table-basic', [BasicTableController::class, 'tableBasic']);

// Data Table Route
Route::get('/table-data-table', [DataTableController::class, 'dataTable']);

// Form Route
Route::get('/form-elements', [FormController::class, 'formElement']);
Route::get('/form-select2', [FormController::class, 'formSelect2']);
Route::get('/form-validation', [FormController::class, 'formValidation']);
Route::get('/form-masks', [FormController::class, 'masksForm']);
Route::get('/form-editor', [FormController::class, 'formEditor']);
Route::get('/form-file-uploads', [FormController::class, 'fileUploads']);
Route::get('/form-layouts', [FormController::class, 'formLayouts']);
Route::get('/form-wizard', [FormController::class, 'formWizard']);

// Charts Route
Route::get('/charts-chartjs', [ChartController::class, 'chartJs']);
Route::get('/charts-chartist', [ChartController::class, 'chartist']);
Route::get('/charts-sparklines', [ChartController::class, 'sparklines']);


// locale route
Route::get('lang/{locale}', [LanguageController::class, 'swap']);

Route::post('/SOCupdate-approver', [SalesController::class, 'SOCupdateApprover'])->name('SOCupdateApprover');

Route::post('/QOCupdate-approver', [QuotationController::class, 'QOCupdateApprover'])->name('QOCupdateApprover');

Route::get('export-items', function () {
    return Excel::download(new ItemsExport, 'items-list.xlsx');
})->name('export.items');

Route::post('/report/deliveryNoteExport', function (\Illuminate\Http\Request $request) {
    $from_date = $request->input('from_date');
    $to_date = $request->input('to_date');
    $sq = $request->input('sq');
    return Excel::download(new DeliveryReportsExport($from_date, $to_date, $sq), 'delivery_note_report.xlsx');
});

Auth::routes();

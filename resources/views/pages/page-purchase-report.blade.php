{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Purchase Order History')

{{-- vendors styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css"
  href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
@endsection

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-sales.css')}}">
@endsection

{{-- page content --}}
@section('content')
<!-- users list start -->
<section class="users-list-wrapper section">
  <div class="users-list-table">    
    <div class="card">
      <div class="card-content">
        <span class="card-title">Purchase Order List</span>
        <!-- datatable start -->
        <div class="responsive-table">
          <table id="users-list-datatable" class="users-list-datatable table">
            <thead>
              <tr>
                <th>Order No</th>
                <th>Order Date</th>
                <th>Supplier</th>
                <th>User Name</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($purchaseorders_new as $purchaseorder)
                <tr>
                  <td>{{ $purchaseorder['po_no'] }}<a href="javascript:;" class="nested_table_show"><i class="material-icons">add_circle_outline</i></a><a href="javascript:;" class="nested_table_hide"><i class="material-icons">remove_circle_outline</i></a></td>
                  <td>{{ $purchaseorder['orderdate'] }}</td>
                  <td>{{ $purchaseorder['supplier'] }}</td>
                  <td>{{ $purchaseorder['intusername'] }}</td>  
                  <td>{{ $purchaseorder['remarks'] }}</td>
                  <td>
                    <?php
                      if ($purchaseorder['status'] == 0) echo 'Open';
                      else if ($purchaseorder['status'] == 1) echo 'Close';
                      else echo 'Cancel';
                    ?>
                  </td>
                  <td>
                    <a href="/printPdfPurchaseOrder/{{ $purchaseorder['id'] }}">PDF</a>
                  </td>
                </tr>
                <tr class="nested_table_wrap">
                  <td colspan="11">
                    <table id="nested_table" class="nested_table table">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Specification</th>
                          <th>Unit</th>
                          <th>Pack</th>
                          <th>Qty</th>
                          <th>Price</th>
                          <th>Total</th>
                          <th>Remarks</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($purchaseorder['purchaseorderitems'] as $purchaseorderitem)
                          <tr>
                            <td>{{ $purchaseorderitem['name'] }}</td>
                            <td>{{ $purchaseorderitem['specification'] }}</td>
                            <td>{{ $purchaseorderitem['unit'] }}</td>
                            <td>{{ $purchaseorderitem['pack'] }}</td>
                            <td>{{ $purchaseorderitem['qty'] }}</td>
                            <td>{{ $purchaseorderitem['price'] }}</td>
                            <td>{{ number_format((float)($purchaseorderitem['qty'] * $purchaseorderitem['price']), 2, '.', '') }}</td>
                            <td>{{ $purchaseorderitem['remarks'] }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- datatable ends -->
<a type="button" class="btn btn-light" style="margin-top: 40px;float:right;" href="/printExcelPurchaseOrder">Print to Excel</a>
      </div>
    </div>
  </div>
</section>
<!-- users list ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-sales.js')}}"></script>
<script src="{{asset('js/scripts/page-purchase.js')}}"></script>
<script>
  if ('{{ Auth::user() -> role }}' == 'internal') {
       $('.sales_order_create_menu').parent().hide()
       $('.company_menu').parent().hide()
       if ('{{ Auth::user() -> admin_role }}' == 0) {
           $('.administration_menu').parent().hide()
           $('.department_menu').parent().hide()
       }
   }
   if ('{{ Auth::user() -> role }}' == 'external') {
       $('.delivery_note_menu').parent().hide()
       $('.purchase_order_menu').parent().hide()
       $('.good_receive_menu').parent().hide()
       $('.category_item_menu').parent().hide()
       $('.supplier_menu').parent().hide()
       $('.quotation_menu').parent().hide()
       $('.company_menu').parent().hide()
       if ('{{ Auth::user() -> admin_role }}' == 0) {
           $('.administration_menu').parent().hide()
           $('.department_menu').parent().hide()
       }
   }
</script>
@endsection
{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Item Transaction Report')

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
        <!-- <span class="card-title">Master Admin</span> -->
        <!-- datatable start -->
        <div class="responsive-table">
          <table id="users-list-datatable-transection" class=" table">
            <thead>
              <tr>
                <th>Delivery Note</th>
                <th>Transaction Type</th>
                <th>Transaction Date</th>
                <th>Supplier</th>
                <th>Item</th>
                <th>Transaction In</th>
                <th>Transaction Out</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($itemtransactions as $itemtransaction)
                <tr>
                  <td>{{ $itemtransaction -> tx_doc }}</td>
                  <td>{{ $itemtransaction -> tx_type == 0 ? 'GR' : 'DN' }}</td>
                  <td>{{ $itemtransaction -> created_at }}</td>
                  <td>{{ $itemtransaction -> supplier }}</td>
                  <td>{{ $itemtransaction -> item }}</td>
                  <td>{{ $itemtransaction -> tx_in }}</td>
                  <td>{{ $itemtransaction -> tx_out }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <!-- datatable ends -->
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
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/page-sales.js')}}"></script>
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

   $(document).ready(function () {
       if ($("#users-list-datatable-transection").length > 0) {
           usersTable = $("#users-list-datatable-transection").DataTable({
               order: [[2, 'desc']],
           });
           $('.nested_table_wrap').hide();
       };
   });
</script>
@endsection
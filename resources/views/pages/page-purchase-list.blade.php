{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Current Purchase Order List')

{{-- vendors styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
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
        <div class="">
          <table id="my-table" class="table-ordering">
            <thead>
              <tr>
                <th>Order No</th>
                <th>Order Date</th>
                <th>Supplier</th>
                <th>User Name</th>
                <!--<th>Remarks</th>-->
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($purchaseorders as $purchaseorder)
              <tr>
                <td>{{ $purchaseorder['po_no'] }}<a href="javascript:;" class="nested_table_show"><i class="material-icons">add_circle_outline</i></a><a href="javascript:;" class="nested_table_hide"><i class="material-icons">remove_circle_outline</i></a></td>
                <td>{{ $purchaseorder['orderdate'] }}</td>
                <td>{{ $purchaseorder['supplier'] }}</td>
                <td>{{ $purchaseorder['intusername'] }}</td>
                <!--<td>{{ $purchaseorder['remarks'] }}</td>-->
                <td>
                  <?php
                  if ($purchaseorder['status'] == 0) echo 'Open';
                  else if ($purchaseorder['status'] == 1) echo 'Close';
                  else echo 'Cancel';
                  ?>
                </td>
                <td>
                  @if (Auth::user() -> role == 'master' || Auth::user() -> role == 'internal')
                  <a class="ml-5" href="{{asset('/update-purchase-order-record/' . $purchaseorder['id'])}}">Edit<!-- <i class="material-icons">edit</i>  --></a>
                  <a class="ml-5" href="{{asset('/new-gr' )}}?pid={{$purchaseorder['id']}}">Create GR</a>
                  @endif
                </td>
              </tr>
              <tr class="nested_table_wrap">
                <td colspan="11">
                  <table id="nested_table" class="nested_table table">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Specification</th>
                        <th>Unit</th>
                        <th>Pack</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Remarks</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($purchaseorder['purchaseorderitems'] as $purchaseorderitem)
                      <tr>
                        <td>{{ $purchaseorderitem['name'] }}</td>
                        <td>{{ $purchaseorderitem['code'] }}</td>
                        <td>{{ $purchaseorderitem['specification'] }}</td>
                        <td>{{ $purchaseorderitem['unit'] }}</td>
                        <td>{{ $purchaseorderitem['pack'] }}</td>
                        <td>{{ $purchaseorderitem['qty'] }}</td>
                        <td>{{ $purchaseorderitem['price'] }}</td>
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



  $(document).ready(function() {
    $('#my-table').DataTable({
      paging: true,
      searching: true,
      info: false,
      columnDefs: [{
        orderable: true,
        targets: '.table-ordering th:not(.no-ordering)',
      }]
    });

    $('#nested_table').DataTable({
      paging: false,
      searching: false,
      info: false,
      columnDefs: [{
        orderable: false,
        targets: [0, 1, 2, 3, 4, 5, 6, 7] // add the column indexes where you want to disable ordering
      }]
    });

    $('#my-table').DataTable().columns('.no-ordering').orderable(false);
  });
</script>
@endsection
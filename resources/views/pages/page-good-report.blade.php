{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Good Receive Report')

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
        <span class="card-title">Good Receive List</span>
        <!-- datatable start -->
        <div class="responsive-table">
          <table id="users-list-datatable" class="users-list-datatable table">
            <thead>
              <tr>
                <th>Receive No</th>
                <th>Receive Date</th>
                <th>Supplier</th>
                <th>User Name</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($goodreceives_new as $goodreceive)
                <tr>
                  <td>{{ $goodreceive['gr_no'] }}<a href="javascript:;" class="nested_table_show"><i class="material-icons">add_circle_outline</i></a><a href="javascript:;" class="nested_table_hide"><i class="material-icons">remove_circle_outline</i></a></td>
                  <td>{{ $goodreceive['gr_date'] }}</td>
                  <td>{{ $goodreceive['supplier'] }}</td>
                  <td>{{ $goodreceive['intusername'] }}</td>  
                  <td>{{ $goodreceive['remarks'] }}</td>
                  <td>
                    <?php
                      if ($goodreceive['status'] == 0) echo 'Open';
                      else if ($goodreceive['status'] == 1) echo 'Close';
                      else echo 'Cancel';
                    ?>
                  </td>
                  <td>
<a href="/printPdfGoodReceive/{{ $goodreceive['id'] }}">Report</a>
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
                          <th>Cost</th>
                          <th>Remarks</th>
<th>Special Handling</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($goodreceive['goodreceiveitems'] as $goodreceiveitem)
                          <tr>
                            <td>{{ $goodreceiveitem['name'] }}</td>
                            <td>{{ $goodreceiveitem['specification'] }}</td>
                            <td>{{ $goodreceiveitem['unit'] }}</td>
                            <td>{{ $goodreceiveitem['pack'] }}</td>
                            <td>{{ $goodreceiveitem['qty'] }}</td>
                            <td>{{ $goodreceiveitem['cost'] }}</td>
                            <td>{{ $goodreceiveitem['remarks'] }}</td>
<td><a href="{{asset('/special-good-receive-handling/' . $goodreceiveitem['gi_re'])}}"><i class="material-icons">edit</i></a></td>
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
<a type="button" class="btn btn-light" style="margin-top: 40px;float:right;" href="/printExcelGoodReceive">Print to Excel</a>
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
<script src="{{asset('js/scripts/page-good.js')}}"></script>
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
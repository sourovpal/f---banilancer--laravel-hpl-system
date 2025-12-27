{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Current DN')

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
          <table id="users-list-datatable" class="users-list-datatable table">
            <thead>
              <tr>
                <th>Note No</th>
                <th>Note Date</th>
                <th>Department</th>
                <th>Cost Center</th>
                <th>User Name</th>
                <th>Sign Date</th>
                <th>Remarks</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($deliverynotes as $deliverynote)
                <tr>
                  <td>{{ $deliverynote['no'] }}<a href="javascript:;" class="nested_table_show"><i class="material-icons">add_circle_outline</i></a><a href="javascript:;" class="nested_table_hide"><i class="material-icons">remove_circle_outline</i></a></td>
                  <td>{{ $deliverynote['notedate'] }}</td>
                  <td>{{ $deliverynote['department'] }}</td>
                  <td>{{ $deliverynote['costcentre'] }}</td>
                  <td>{{ $deliverynote['extusername'] }}</td>
                  <td>{{ $deliverynote['sign_date'] }}</td>
                  <td>{{ $deliverynote['remarks'] }}</td>
                  <td>
                    <?php
                      if ($deliverynote['status'] == 0) echo 'Ready for Delivery';
                      else if ($deliverynote['status'] == 1) echo 'Delivered';
                    ?>
                  </td>
                  <td>
                     
                @if ($deliverynote['status'] == 0)
    <a href="{{asset('/make-delivery/' . $deliverynote['id'])}}" class="make_deliver">Delivered</a> 
@endif 
                     
                    <!-- <a class="ml-5" href="{{asset('/update-delivery-note-record/' . $deliverynote['id'])}}">edit<i class="material-icons">edit</i></a> -->
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
                          <th>Remarks</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($deliverynote['deliverynoteitems'] as $deliverynoteitem)
                          <tr>
                            <td>{{ $deliverynoteitem['name'] }}</td>
                            <td>{{ $deliverynoteitem['specification'] }}</td>
                            <td>{{ $deliverynoteitem['unit'] }}</td>
                            <td>{{ $deliverynoteitem['pack'] }}</td>
                            <td>{{ $deliverynoteitem['qty'] }}</td>
                            <td>{{ $deliverynoteitem['price'] }}</td>
                            <td>{{ $deliverynoteitem['remarks'] }}</td>
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
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
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
</script>
@endsection
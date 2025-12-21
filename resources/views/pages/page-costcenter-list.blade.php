{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Current Costcenter List')

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
          <table id="myTable" class="users-list-datatable table">
            <thead>
              <tr>
                <th>Costcenter Code</th>
                <th>Costcenter Name</th>
                <th>Department</th>
                 <th>Department Floor</th>
                <th>Department Build</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($costcenters as $costcenter)
                <tr>
                  <td>{{ $costcenter -> code }}</td>
                  <td>{{ $costcenter -> name }}</td>
                  <td>{{ $costcenter -> department }}</td>
                   <td>{{ $costcenter -> floor }}</td> 
                  <td>{{$costcenter -> build }}</td>
                  <td><a href="{{asset('/update-costcenter-record/' . $costcenter -> id)}}">Edit</a></td>
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
<script src="{{asset('js/scripts/page-costcenter.js')}}"></script>
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
else{
$('.administration_menu').parent().find('.collapsible-sub li:first-child').hide();
$('.administration_menu').parent().find('.collapsible-sub li:nth-child(2)').hide();
}
   }


</script>
 <script type="text/javascript">
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>

@endsection
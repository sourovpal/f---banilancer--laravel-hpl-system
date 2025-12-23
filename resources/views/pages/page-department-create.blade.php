{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','New Department')

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
        <div class="row">
          <div class="col s12" id="account">
            <!-- users edit media object start -->
            <div class="media display-flex align-items-center">
              <h5 class="media-heading mt-0">Department Info</h5>
            </div>
            @if ($data['error'])
            <h5 class="error_span mb-2">The department code you inputed is existed already!</h5>
            @endif
            <!-- users edit media object ends -->
            <!-- users edit account form start -->
            <form id="departmentRegisterForm" action="/department_register" method="post">
              {{ csrf_field() }}
              
              <div class="row">
                <!-- <div class="col s12 input-field">
                  <input id="departmentcode" name="departmentcode" type="text" class="validate" value="" data-error=".errorTxt1">
                  <label for="departmentcode">Department Code</label>
                  <small class="errorTxt1"></small>
                </div> -->
                <div class="col s12 m6 input-field">
                  <input id="departmentname" name="departmentname" type="text" class="validate" value="" data-error=".errorTxt1">
                  <label for="departmentname">Department Name</label>
                  <small class="errorTxt1"></small>
                </div>
                <div class="col s12 m6 input-field">
                  <input id="departmentcode" name="departmentcode" type="text" class="validate" value="" data-error=".errorTxt2">
                  <label for="departmentcode">Department Code</label>
                  <small class="errorTxt2"></small>
                </div>
               <!--  <div class="col s12 m6 input-field">
                  <input id="floor" name="floor" type="text" class="validate" value="" data-error=".errorTxt2">
                  <label for="floor">Floor</label>
                  <small class="errorTxt2"></small>
                </div>
                <div class="col s12 m6 input-field">
                  <input id="build" name="build" type="text" class="validate" value="" data-error=".errorTxt2">
                  <label for="build">Build</label>
                  <small class="errorTxt2"></small> -->
                </div>
                <div class="col s12 display-flex justify-content-end mt-3">
                  <button type="submit" class="btn indigo mr-1">Create</button>
                  <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
                </div>
              </div>
            </form>
            <!-- users edit account form ends -->
          </div>
        </div>
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
<script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')
<script src="{{asset('js/scripts/page-department.js')}}"></script>
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
@endsection
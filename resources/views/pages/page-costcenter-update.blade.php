{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Update Costcenter Record')

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
            <div class="media display-flex align-items-center mb-2">
              <h5 class="media-heading mt-0">Costcenter Info</h5>
            </div>
            <!-- users edit media object ends -->
            <!-- users edit account form start -->
            <form id="costcenterUpdateForm" action="/costcenter_update" method="post">
              {{ csrf_field() }}

              <div class="row">
                <div class="col s12 m6 input-field">
                  <input type="hidden" id="id" name="id" value="{{ $costcenter -> id }}">
                  <input id="costcentername" name="costcentername" type="text" class="validate" value="{{ $costcenter -> name }}" data-error=".errorTxt1">
                  <label for="costcentername">Costcenter Name</label>
                  <small class="errorTxt1"></small>
                </div>
                <div class="col s12 m6 input-field">
                  <input id="costcentercode" name="costcentercode" type="text" class="validate" value="{{ $costcenter -> code }}" data-error=".errorTxt2">
                  <label for="none">Costcenter Code</label>
                  <small class="errorTxt2"></small>
                </div>
                 <div class="col s12 m6 input-field">
                                        <input id="floor" name="floor" type="text" class="validate"
                                            value="{{ $costcenter->floor }}" data-error=".errorTxt2">
                                        <label for="floor">Floor</label>
                                        <small class="errorTxt2"></small>
                                    </div>
                                    <div class="col s12 m6 input-field">
                                        <input id="build" name="build" type="text" class="validate"
                                            value="{{ $costcenter->build }}" data-error=".errorTxt2">
                                        <label for="build">Build</label>
                                        <small class="errorTxt2"></small>
                                    </div>
                <div class="col s12 m6 input-field">
                  <select name="department" id="department">
                    <option value="0">Select Department</option>
                    @foreach ($departments as $department) {
                      <option value="{{ $department -> id }}" {{ $costcenter -> dep_id == $department -> id ? 'selected' : '' }}>{{ $department -> name }}</option>
                    }
                    @endforeach
                  </select>
                  <label>Department</label>
                </div>
                <div class="col s12 display-flex justify-content-end mt-3">
                  <button type="submit" class="btn indigo mr-1">Update</button>
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
@endsection
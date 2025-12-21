{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Master Users List')

{{-- vendors styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">
@endsection

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
<style>
    .avatar-status{
        width: 105px !important;
        margin-bottom: 10px;
    }
    
    .avatar-status h6{
        color: lightseagreen !important;
    }
</style>
@endsection

{{-- page content --}}
@section('content')
<!-- users list start -->
<section class="users-list-wrapper section">
  <div class="users-list-table">
    <div class="card">
      <div class="card-content">
        <span class="card-title">Master Admin</span>
        <!-- datatable start -->
        <div class="responsive-table">
          <table id="users-list-datatable" class="users-list-datatable table">
            <thead>
              <tr>
                <th>User ID</th>
                <th>User Code</th>
                <th>User Email</th>
                <th>User Name</th>
                <th>Last Login</th>
                <th>Status</th>
                <th>System Name</th>
                <th>System Code</th>
                <th>Action</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <tr>
                  <td>{{ $user -> userid }}</td>
                  <td>{{ $user -> usercode }}</td>
                  <td>{{ $user -> email }}</td>
                  <td>{{ $user -> username }}</td>
                  <td>{{ $user -> last_login }}</td>
                  <td>{{ $user -> status ? 'Enable' : 'Disable' }}</td>
                  <td>{{ $user -> systemname }}</td>
                  <td>{{ $user -> systemcode }}</td>
                  <td><a href="{{ asset('page-users-edit/' . $user -> id) }}">Edit</a></td>
                  <td><a href="#confirm_modal" user_id="{{ $user -> id }}" class="delete_user modal-trigger">Delete</a></td>
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

<div id="confirm_modal" class="modal modal-fixed-footer">
  <div class="modal-content">
    <p style="color: black !important;">Are you sure that you will delete this user?</p>
    <div class="row mt-5">
        <input type="hidden" id="user_id_cancel">
        <div class="col s2"></div>
        <div class="col s3"><button class="btn btn-success" id="yes_btn">Yes</button></div>
        <div class="col s2"></div>
        <div class="col s3"><button class="btn btn-danger modal-action modal-close" id="cancel_btn" data-dismiss="modal">Cancel</button></div>
        <div class="col s2"></div>
    </div>
  </div>
  <!--<div class="modal-footer">-->
  <!--  <button type="button" class="btn btn-secondary modal-action modal-close" data-dismiss="modal">Cancel</button>-->
  <!--</div>-->
</div>
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
<script src="{{asset('js/scripts/page-users.js')}}"></script>
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
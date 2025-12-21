{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Users edit')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
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
<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <div class="row">
        <div class="col s12" id="account">
          <!-- users edit media object start -->
          <div class="media display-flex align-items-center mb-2">
            <h5 class="media-heading mt-0">User Info</h5>
          </div>
          <!-- users edit media object ends -->
          <!-- users edit account form start -->
          <div class="row">
            <div class="col s12 m6 input-field">
              <select name="role_select" id="role_select">
                <option value="master">Master User</option>
                <option value="internal">Internal User</option>
                <option value="external">External User</option>
              </select>
              <label style="margin-top: 33px;font-size: 16px;">Role</label>
              <input type="hidden" id="old_role" value="<?=$user->role?>">
          </div>
          </div>
          <form id="masterUserRegisterForm" class="userForm" action="/user_update" method="post">
            {{ csrf_field() }}

            <div class="row">
              <div class="col s12 m6 input-field" type="text" class="validate" value="" data-error=".errorTxt4">
                <input id="useremail_master" name="useremail" type="email" value="{{ $user -> email }}">
                <label for="useremail_int">User Email</label>
                <small class="errorTxt4"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input type="hidden" id="role" name="role" value="master">
                <input type="hidden" id="mas_id" name="mas_id" value="{{ $user -> id }}">
                <input id="userid_master" name="usercode" type="text" class="validate" value="" data-error=".errorTxt2">
                <label for="none">User Code</label>
                <small class="errorTxt2"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="userid" name="userid" type="text" class="validate" value="" data-error=".errorTxt3">
                <label for="userid">User ID</label>
                <small class="errorTxt3"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="username" name="username" type="text" class="validate" value="" data-error=".errorTxt3">
                <label for="username">User Name</label>
                <small class="errorTxt3"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="systemname" name="systemname" type="text" class="validate" value="" data-error=".errorTxt4">
                <label for="systemname">System Name</label>
                <small class="errorTxt4"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="systemcode" name="systemcode" type="text" class="validate" value="" data-error=".errorTxt5">
                <label for="systemcode">System Code</label>
                <small class="errorTxt5"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="password" name="password" type="password" class="validate" value="" data-error=".errorTxt6">
                <label for="password">Password</label>
                <small>(Leave it blank remain unchanged)</small>
                <small class="errorTxt6"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="confirm_password" name="confirm_password" type="password" class="validate" data-error=".errorTxt7">
                <label for="confirm_password">Confirm Password</label>
                <small class="errorTxt7"></small>
              </div>
            </div>
            <div class="row">
              <div class="col s12 m6 input-field">
                <select name="status" id="status_field">
                  <option {{ ($user -> status) ? 'selected' : '' }} value="1">Enable</option>
                  <option {{ (!$user -> status) ? 'selected' : '' }} value="0">Disable</option>
                </select>
                <label for="status_field" style="transform: none;">Status</label>
              </div>
            </div>
            <div class="row">
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="btn indigo" form_name="masterUserRegisterForm">
                  Update</button>
                <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
              </div>
            </div>
          </form>
          <form id="internalUserRegisterForm" class="userForm" action="/user_update" method="post">
            {{ csrf_field() }}

            <div class="row">
              <div class="col s12 m6 input-field">
                <input type="hidden" id="role" name="role" value="internal">
                <input type="hidden" id="int_id" name="int_id" value="{{ $user -> id }}">
                <input id="usercode_int" name="usercode" type="text" class="validate" value="" data-error=".errorTxt2">
                <input type="hidden" name="usercode" id="usercode_int_hidden" disabled>
                <label for="usercode_int">User Code</label>
                <small class="errorTxt2"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="userid_int" name="userid" type="text" class="validate" value="" data-error=".errorTxt3">
                <input type="hidden" name="userid" id="userid_int_hidden" disabled>
                <label for="userid_int">User ID</label>
                <small class="errorTxt3"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="username_int" name="username" type="text" class="validate" value="" data-error=".errorTxt3">
                <input type="hidden" name="username" id="username_int_hidden" disabled>
                <label for="username_int">User Name</label>
                <small class="errorTxt3"></small>
              </div>
              <div class="col s12 m6 input-field" type="text" class="validate" value="" data-error=".errorTxt4">
                <input id="useremail_int" name="useremail" type="email">
                <input type="hidden" name="useremail" id="useremail_int_hidden" disabled>
                <label for="useremail_int">User Email</label>
                <small class="errorTxt4"></small>
              </div>
              <div class="col s12 m6 input-field">
                <select name="admin_role" id="int_admin_role">
                  <option value="0">Internal User</option>
                  <option value="1">Internal Admin</option>
                </select>
                <input type="hidden" name="admin_role" id="int_admin_role_hidden" disabled>
                <label style="top: 1px !important; font-size: 1rem; !important">Admin Role</label>
              </div>
<div class="col s12 m6 input-field rcvemail_wrap">
            <label class="rcv_email_label">
                <input type="checkbox" id="rcvemail" name="rcvemail" {{ $user -> rcvemail ? 'checked' : '' }} />
                <span>Receive Email</span>
            </label>
          </div>
              <div class="col s12 m6 input-field">
                <input id="password_int" name="password" type="password" class="validate" value="" data-error=".errorTxt6">
                <label for="password_int">Password</label>
                <small>(Leave it blank remain unchanged)</small>
                <small class="errorTxt6"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="confirm_password_int" name="confirm_password" type="password" class="validate" data-error=".errorTxt7">
                <label for="confirm_password_int">Confirm Password</label>
                <small class="errorTxt7"></small>
              </div>
            </div>
            <div class="row">
              <div class="col s12 m6 input-field">
                <select name="status" id="status_field_int">
                  <option {{ ($user -> status) ? 'selected' : '' }} value="1">Active</option>
                  <option {{ (!$user -> status) ? 'selected' : '' }} value="0">Inactive</option>
                </select>
                <label style="margin-top:31px;font-size:15px;"  for="status_field_int" style="transform: none!important;">Status</label>
              </div>
            </div>
            <div class="row">
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="btn indigo" form_name="internalUserRegisterForm">
                  Update</button>
                  <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
              </div>
            </div>
          </form>
          <form id="externalUserRegisterForm" class="userForm" action="/user_update" method="post">
            {{ csrf_field() }}

            <div class="row">
              <div class="col s12 m6 input-field">
                <input type="hidden" id="role" name="role" value="external">
                <input type="hidden" id="ext_id" name="ext_id" value="{{ $user -> id }}">
                <input id="usercode_ext" name="usercode" type="text" class="validate" value="" data-error=".errorTxt2">
                <input type="hidden" name="usercode" id="usercode_ext_hidden" disabled>
                <label for="usercode_ext">User Code</label>
                <small class="errorTxt2"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="userid_ext" name="userid" type="text" class="validate" value="" data-error=".errorTxt3">
                <input type="hidden" name="userid" id="userid_ext_hidden" disabled>
                <label for="userid_ext">User ID</label>
                <small class="errorTxt3"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="username_ext" name="username" type="text" class="validate" value="" data-error=".errorTxt3">
                <input type="hidden" name="username" id="username_ext_hidden" disabled>
                <label for="username_ext">User Name</label>
                <small class="errorTxt3"></small>
              </div>
              <div class="col s12 m6 input-field" type="text" class="validate" value="" data-error=".errorTxt4">
                <input id="useremail_ext" name="useremail" type="email">
                <input type="hidden" name="useremail" id="useremail_ext_hidden" disabled>
                <label for="useremail_ext">User Email</label>
                <small class="errorTxt4"></small>
              </div>
              <div class="col s12 m6 input-field" type="text" class="validate" value="" data-error=".errorTxt5">
                <input id="telephone" name="telephone" type="text">
                <input type="hidden" name="telephone" id="telephone_hidden" disabled>
                <label for="telephone">User Telephone</label>
                <small class="errorTxt5"></small>
              </div>
              <div class="col s12 m6 input-field">
                <select name="admin_role" id="ext_admin_role">
                  <option value="0">External User</option>
                  <option value="1">External Admin</option>
                </select>
                <input type="hidden" name="admin_role" id="ext_admin_role_hidden" disabled>
                <label style="top: 1px !important; font-size: 1rem; !important">Admin Role</label>
              </div>
              <div class="col s12 m6 input-field">
                <select name="approver_role" id="approver_role">
                  <option value="0">External User</option>
                  <option value="1">External Approver</option>
                  <option value="2">External Manager</option>
                </select>
                <input type="hidden" name="approver_role" id="approver_role_hidden" disabled>
                <label style="top: 1px !important; font-size: 1rem; !important">Approver Role</label>
              </div>
              <div class="col s12 m6 input-field">
                <select name="department" id="department">
                  <option value="0">Select a Department</option>
                  @foreach($departments as $department)
                    <option value="{{ $department -> id }}">{{ $department -> name }}</option>
                  @endforeach
                </select>
                <input type="hidden" name="department" id="department_hidden" disabled>
                <label style="top: 1px !important; font-size: 1rem; !important">Department</label>
              </div>
            <!--   <div class="col s12 m6 input-field" type="text" style="opacity:0;">
                <input id="none" name="none" type="text">
                <label for="none">User Email</label>
              </div> -->
              <div class="col s12 m6 input-field">
                <input id="password_ext" name="password" type="password" class="validate" value="" data-error=".errorTxt6">
                <label for="password_ext">Password</label>
                <small>(Leave it blank remain unchanged)</small>
                <small class="errorTxt6"></small>
              </div>
              <div class="col s12 m6 input-field">
                <input id="confirm_password_ext" name="confirm_password" type="password" class="validate" data-error=".errorTxt7">
                <label for="confirm_password_ext">Confirm Password</label>
                <small class="errorTxt7"></small>
              </div>
            </div>
            <div class="row">
              <div class="col s12 m6 input-field">
                <select name="status" id="status_field_ext">
                  <option {{ ($user -> status) ? 'selected' : '' }} value="1">Active</option>
                  <option {{ (!$user -> status) ? 'selected' : '' }} value="0">Inactive</option>
                </select>
                <label for="status_field_ext" style=";">Status</label>
              </div>
            </div>
            <div class="row">
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="btn indigo" form_name="externalUserRegisterForm">
                  Update</button>
                <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
              </div>
            </div>
          </form>
          <!-- users edit account form ends -->
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- users edit ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>

<script>
  $(document).ready(function(){
    $('label').addClass('active');
    $('#role_select').val($('#old_role').val());
    $('.userForm').hide();
    if ($('#old_role').val() == 'master') {
      $("#masterUserRegisterForm").show();
      $('#usercode').val('{{ $user -> usercode }}');
      $('#userid_master').val('{{ $user -> usercode }}');
      $('#username').val('{{ $user -> username }}');
      $('#userid').val('{{ $user -> userid }}');
      $('#systemname').val('{{ $user -> systemname }}');
      $('#systemcode').val('{{ $user -> systemcode }}');
    }
    else if ($('#old_role').val() == 'internal') {
      $("#internalUserRegisterForm").show();
      $('#usercode_int').val('{{ $user -> usercode }}');
      $('#username_int').val('{{ $user -> username }}');
      $('#userid_int').val('{{ $user -> userid }}');
      $('#useremail_int').val('{{ $user -> email }}');
      $('#int_admin_role').val('{{ $user -> admin_role }}');

      if ($('#int_admin_role').val() == 0) {
        
      }
    }
    else {
      $("#externalUserRegisterForm").show();
      $('#usercode_ext').val('{{ $user -> usercode }}');
      $('#userid_ext').val('{{ $user -> userid }}');
      $('#username_ext').val('{{ $user -> username }}');
      $('#useremail_ext').val('{{ $user -> email }}');
      $('#telephone').val('{{ $user -> telephone }}');
      $('#ext_admin_role').val('{{ $user -> admin_role }}');
      $('#department').val('{{ $user -> dep_id }}');
      $('#approver_role').val('{{ $user -> appr_role }}');
    }

    if ('{{ Auth::user() -> role }}' == 'external' && '{{ Auth::user() -> admin_role }}' == 0) {
      $('#role_select').prop( "disabled", true );
      $('#usercode_ext').prop( "disabled", true );
      $('#userid_ext').prop( "disabled", true );
      $('#username_ext').prop( "disabled", true );
      $('#useremail_ext').prop( "disabled", true );
      $('#telephone').prop( "disabled", true );
      $('#ext_admin_role').prop( "disabled", true );
      $('#department').prop( "disabled", true );
      $('#approver_role').prop( "disabled", true );

      $('#usercode_ext_hidden').val('{{ $user -> usercode }}');
      $('#username_ext_hidden').val('{{ $user -> username }}');
      $('#userid_ext_hidden').val('{{ $user -> userid }}');
      $('#useremail_ext_hidden').val('{{ $user -> email }}');
      $('#telephone_hidden').val('{{ $user -> telephone }}');
      $('#ext_admin_role_hidden').val('{{ $user -> admin_role }}');
      $('#department_hidden').val('{{ $user -> dep_id }}');
      $('#approver_role_hidden').val('{{ $user -> appr_role }}');

      $('#usercode_ext_hidden').prop( "disabled", false );
      $('#username_ext_hidden').prop( "disabled", false );
      $('#userid_ext_hidden').prop( "disabled", false );
      $('#useremail_ext_hidden').prop( "disabled", false );
      $('#telephone_hidden').prop( "disabled", false );
      $('#ext_admin_role_hidden').prop( "disabled", false );
      $('#department_hidden').prop( "disabled", false );
      $('#approver_role_hidden').prop( "disabled", false );
    }

    if ('{{ Auth::user() -> role }}' == 'internal' && '{{ Auth::user() -> admin_role }}' == 0) {
      $('#role_select').prop( "disabled", true );
      $('#usercode_int').prop( "disabled", true );
      $('#userid_int').prop( "disabled", true );
      $('#username_int').prop( "disabled", true );
      $('#useremail_int').prop( "disabled", true );
      $('#int_admin_role').prop( "disabled", true );

      $('#usercode_int_hidden').val('{{ $user -> usercode }}');
      $('#username_int_hidden').val('{{ $user -> username }}');
      $('#userid_int_hidden').val('{{ $user -> userid }}');
      $('#useremail_int_hidden').val('{{ $user -> email }}');
      $('#int_admin_role_hidden').val('{{ $user -> telephone }}');

      $('#usercode_int_hidden').prop( "disabled", false );
      $('#username_int_hidden').prop( "disabled", false );
      $('#userid_int_hidden').prop( "disabled", false );
      $('#useremail_int_hidden').prop( "disabled", false );
      $('#int_admin_role_hidden').prop( "disabled", false );
    }
  });
</script>
@endsection

{{-- page scripts --}}
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
else{
$('.administration_menu').parent().find('.collapsible-sub li:first-child').hide();
$('.administration_menu').parent().find('.collapsible-sub li:nth-child(2)').hide();
}
   }
</script>
@endsection
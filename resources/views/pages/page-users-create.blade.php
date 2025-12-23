{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','New User')

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
      <div class="media display-flex align-items-center mb-2">
        <h5 class="media-heading mt-0">User Info</h5>
      </div>
      <div class="row">
        <div class="col s12 m6 input-field">
          <select name="role_select" id="role_select">
            @if (Auth::user() -> role == 'master')
            <option value="master">Master User</option>
            @endif
            @if (Auth::user() -> role == 'master' || Auth::user() -> role == 'internal')
            <option value="internal">Internal User</option>
            @endif
            @if (Auth::user() -> role == 'master' || Auth::user() -> role == 'external')
            <option value="external">External User</option>
            @endif
          </select>
          <label>Role</label>
        </div>
      </div>
      <form id="masterUserRegisterForm" class="userForm" action="/user_register" method="post">
        {{ csrf_field() }}

        <div class="row">
           <div class="col s12 m6 input-field">
            <input type="hidden" id="role" name="role" value="master">
            <input id="usercode" name="usercode" type="text" class="validate" value="" data-error=".errorTxt2">
			 <input id="userid" name="userid" type="hidden" class="validate" value="" data-error=".errorTxt2">
            <label for="usercode">User ID</label>
            <small class="errorTxt2"></small>
          </div>
		  
         <div class="col s12 m6 input-field" type="text" class="validate" value="" data-error=".errorTxt4">
            <input id="useremail_master" name="useremail" type="email">
            <label for="useremail_int">User Email</label>
            <small class="errorTxt4"></small>
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
            <small class="errorTxt6"></small>
          </div>
          <div class="col s12 m6 input-field">
            <input id="confirm_password" name="confirm_password" type="password" class="validate" data-error=".errorTxt7">
            <label for="confirm_password">Confirm Password</label>
            <small class="errorTxt7"></small>
          </div>
        </div>
        <div class="row">
          <div class="col s12 display-flex justify-content-end mt-3">
            <button type="submit" class="btn indigo" form_name="masterUserRegisterForm">
              Create</button>
            <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
          </div>
        </div>
		<script>
		// 获取输入元素
		var userCodeInput = document.getElementById('usercode');
		var userIdInput = document.getElementById('userid');

		// 添加事件监听器到 usercode 输入框
		userCodeInput.addEventListener('input', function() {
			// 将 usercode 输入框的值复制到 userid 输入框
			userIdInput.value = userCodeInput.value;
		});
		</script>
      </form>
      <form id="internalUserRegisterForm" class="userForm" action="/user_register" method="post">
        {{ csrf_field() }}

        <div class="row">
          <div class="col s12 m6 input-field">
            <input type="hidden" id="role" name="role" value="internal">
            <input id="usercode_int" name="usercode" type="text" class="validate" value="" data-error=".errorTxt2">
            <input id="userid" name="userid" type="hidden" class="validate" value="" data-error=".errorTxt2">
			<label for="usercode_int">User ID</label>
            <small class="errorTxt2"></small>
          </div>
          
          <div class="col s12 m6 input-field">
            <input id="username_int" name="username" type="text" class="validate" value="" data-error=".errorTxt3">
            <label for="username_int">User Name</label>
            <small class="errorTxt3"></small>
          </div>
          <div class="col s12 m6 input-field" type="text" class="validate" value="" data-error=".errorTxt4">
            <input id="useremail_int" name="useremail" type="email">
            <label for="useremail_int">User Email</label>
            <small class="errorTxt4"></small>
          </div>
          <div class="col s12 m6 input-field">
            <select name="admin_role" id="admin_role">
              <option value="0">Internal User</option>
              @if (Auth::user() -> role == 'master')
              <option value="1">Internal Admin</option>
              @endif
            </select>
            <label>Admin Role</label>
          </div>
<div class="col s12 m6 input-field rcvemail_wrap">
            <label>
                <input type="checkbox" id="rcvemail" name="rcvemail" />
                <span>Receive Email</span>
            </label>
          </div>
          <div class="col s12 m6 input-field">
            <input id="password_int" name="password" type="password" class="validate" value="" data-error=".errorTxt6">
            <label for="password_int">Password</label>
            <small class="errorTxt6"></small>
          </div>
          <div class="col s12 m6 input-field">
            <input id="confirm_password_int" name="confirm_password" type="password" class="validate" data-error=".errorTxt7">
            <label for="confirm_password_int">Confirm Password</label>
            <small class="errorTxt7"></small>
          </div>
        </div>
        <div class="row">
          <div class="col s12 display-flex justify-content-end mt-3">
            <button type="submit" class="btn indigo" form_name="internalUserRegisterForm">
              Create</button>
            <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
          </div>
        </div>
		<script>
		// 获取输入元素
		var userCodeInput = document.getElementById('usercode');
		var userIdInput = document.getElementById('userid');

		// 添加事件监听器到 usercode 输入框
		userCodeInput.addEventListener('input', function() {
			// 将 usercode 输入框的值复制到 userid 输入框
			userIdInput.value = userCodeInput.value;
		});
		</script>
      </form>
      <form id="externalUserRegisterForm" class="userForm" action="/user_register" method="post">
        {{ csrf_field() }}

        <div class="row">
          <div class="col s12 m6 input-field">
            <input type="hidden" id="role" name="role" value="external">
            <input id="usercode_ext" name="usercode" type="text" class="validate" value="" data-error=".errorTxt2">
            <input id="userid" name="userid" type="hidden" class="validate" value="" data-error=".errorTxt2">
			<label for="usercode_ext">User ID</label>
            <small class="errorTxt2"></small>
          </div>
          
          <div class="col s12 m6 input-field">
            <input id="username_ext" name="username" type="text" class="validate" value="" data-error=".errorTxt3">
            <label for="username_ext">User Name</label>
            <small class="errorTxt3"></small>
          </div>
          <div class="col s12 m6 input-field" type="text" class="validate" value="" data-error=".errorTxt4">
            <input id="useremail_ext" name="useremail" type="email">
            <label for="useremail_ext">User Email</label>
            <small class="errorTxt4"></small>
          </div>
          <div class="col s12 m6 input-field" type="text" class="validate" value="" data-error=".errorTxt5">
            <input id="telephone" name="telephone" type="text">
            <label for="telephone">User Telephone</label>
            <small class="errorTxt5"></small>
          </div>
          <div class="col s12 m6 input-field">
            <select name="admin_role" id="admin_role">
              <option value="0">External User</option>
              @if (Auth::user() -> role == 'master')
              <option value="1">External Admin</option>
              @endif
            </select>
            <label>Admin Role</label>
          </div>
          <div class="col s12 m6 input-field">
            <select name="approver_role" id="approver_role">
              <option value="0">External User</option>
              <option value="1">External Approver</option>
			  <option value="1">External Approver (User Group)</option>
              <option value="2">External Manager</option>
            </select>
            <label>Approver Role</label>
          </div>
          <div class="col s12 m6 input-field">
            <select name="department" id="department">
              <option value="0">Select a Department</option>
              @foreach($departments as $department)
                <option value="{{ $department -> id }}">{{ $department -> name }}</option>
              @endforeach
            </select>
            <label>Department</label>
          </div>
          <!--<div class="col s12 m6 input-field" type="text" style="opacity:0;">-->
          <!--  <input id="none" name="none" type="text">-->
          <!--  <label for="none">User Email</label>-->
          <!--</div>-->
          <div class="col s12 m6 input-field">
            <input id="password_ext" name="password" type="password" class="validate" value="" data-error=".errorTxt6">
            <label for="password_ext">Password</label>
            <small class="errorTxt6"></small>
          </div>
          <div class="col s12 m6 input-field">
            <input id="confirm_password_ext" name="confirm_password" type="password" class="validate" data-error=".errorTxt7">
            <label for="confirm_password_ext">Confirm Password</label>
            <small class="errorTxt7"></small>
          </div>
        </div>
        <div class="row">
          <div class="col s12 display-flex justify-content-end mt-3">
            <button type="submit" class="btn indigo" form_name="externalUserRegisterForm">
              Create</button>
            <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
          </div>
        </div>
		<script>
		// 获取输入元素
		var userCodeInput = document.getElementById('usercode');
		var userIdInput = document.getElementById('userid');

		// 添加事件监听器到 usercode 输入框
		userCodeInput.addEventListener('input', function() {
			// 将 usercode 输入框的值复制到 userid 输入框
			userIdInput.value = userCodeInput.value;
		});
		</script>
      </form>
    </div>
  </div>
</div>
<!-- users edit ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
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
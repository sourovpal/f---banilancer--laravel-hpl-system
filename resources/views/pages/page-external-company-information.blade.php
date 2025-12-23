{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','External')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-company-information.css')}}">
@endsection

{{-- page content --}}
@section('content')
<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <div class="media display-flex align-items-center mb-2">
        <h5 class="media-heading mt-0">External Company</h5>
      </div>
      <form id="externalCompanyInformationForm" class="userForm" action="/company_information_update" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="row">
          <div class="col s12 m6 input-field">
            <input type="hidden" id="role" name="role" value="external">
            <input id="name" name="name" type="text" class="validate" value="{{ count($company) ? $company[0] -> name : '' }}" data-error=".errorTxt2">
            <label for="name">Company Name</label>
            <small class="errorTxt2"></small>
          </div>
          <div class="col s12 m6 input-field">
            <input id="add1" name="add1" type="text" class="validate" value="{{ count($company) ? $company[0] -> add1 : '' }}" data-error=".errorTxt3">
            <label for="add1">Company Address1</label>
            <small class="errorTxt3"></small>
          </div>
          <div class="col s12 m6 input-field">
            <input id="add2" name="add2" type="text" class="validate" value="{{ count($company) ? $company[0] -> add2 : '' }}" data-error=".errorTxt4">
            <label for="add2">Company Address2</label>
            <small class="errorTxt4"></small>
          </div>
          <div class="col s12 m6 input-field">
            <input id="add3" name="add3" type="text" class="validate" value="{{ count($company) ? $company[0] -> add3 : '' }}" data-error=".errorTxt5">
            <label for="add3">Company Address3</label>
            <small class="errorTxt5"></small>
          </div>
          <div class="col s12 m6 input-field">
            <input id="tel" name="tel" type="text" class="validate" value="{{ count($company) ? $company[0] -> tel : '' }}" data-error=".errorTxt6">
            <label for="tel">Company Telephone</label>
            <small class="errorTxt6"></small>
          </div>
          <div class="col s12 m6 input-field">
            <input id="fax" name="fax" type="text" class="validate" value="{{ count($company) ? $company[0] -> fax : '' }}" data-error=".errorTxt7">
            <label for="fax">Company Fax</label>
            <small class="errorTxt7"></small>
          </div>
          <div class="col s12 input-field">
            <div class="file-field input-field">
              <div class="btn">
                <span>Company Logo</span>
                <input type="file" name="logo">
              </div>
              <div class="file-path-wrapper">
                <input class="file-path validate" type="text" value="{{ count($company) ? $company[0] -> logo : '' }}">
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col s12 display-flex justify-content-end mt-3">
            <button type="submit" class="btn indigo mr-1">
              {{ count($company) ? 'Update' : 'Create' }}</button>
            <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
          </div>
        </div>
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
<script src="{{asset('js/scripts/page-company-information.js')}}"></script>
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
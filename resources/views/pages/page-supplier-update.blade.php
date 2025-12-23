{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Update Supplier Record')

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
              <h5 class="media-heading mt-0">Supplier Info</h5>
            </div>
            <!-- users edit media object ends -->
            <!-- users edit account form start -->
            <form id="supplierUpdateForm" action="/supplier_update" method="post">
              {{ csrf_field() }}

              <div class="row">
                <div class="col s12 m6">
                  <div class="row">
                    <div class="col s12 input-field">
                      <input type="hidden" name="id" value="{{ $supplier -> id }}">
                      <input id="suppliercode" name="suppliercode" type="text" class="validate" value="{{ $supplier -> code }}"
                        data-error=".errorTxt1">
                      <label for="suppliercode">Supplier Code</label>
                      <small class="errorTxt1"></small>
                    </div>
                    <div class="col s12 input-field mt-2">
                      <input id="englishname" name="englishname" type="text" class="validate" value="{{ $supplier -> englishname }}"
                        data-error=".errorTxt3">
                      <label for="englishname">English Name</label>
                      <small class="errorTxt3"></small>
                    </div>
                    <div class="col s12 input-field">
                      <input id="englishaddress" name="englishaddress" type="text" class="validate" value="{{ $supplier -> englishaddress }}"
                        data-error=".errorTxt5">
                      <label for="englishaddress">English Address</label>
                      <small class="errorTxt5"></small>
                    </div>
                    <div class="col s12 input-field">
                      <input id="telephone1" name="telephone1" type="text" class="validate" value="{{ $supplier -> telephone1 }}"
                        data-error=".errorTxt7">
                      <label for="telephone1">Telephone 1</label>
                      <small class="errorTxt7"></small>
                    </div>
                    <div class="col s12 input-field">
                      <input id="suppliercontact" name="suppliercontact" type="text" class="validate" value="{{ $supplier -> contact }}"
                        data-error=".errorTxt9">
                      <label for="suppliercontact">Supplier Contact</label>
                      <small class="errorTxt9"></small>
                    </div>
                    <div class="col s12 input-field">
                      <input id="suppliermobile" name="suppliermobile" type="text" class="validate" value="{{ $supplier -> mobile }}"
                        data-error=".errorTxt11">
                      <label for="suppliermobile">Supplier Mobile</label>
                      <small class="errorTxt11"></small>
                    </div>
                  </div>
                </div>
                <div class="col s12 m6">
                  <div class="row">
                    <div class="col s12 input-field">
                      <input id="supplieremail" name="supplieremail" type="text" class="validate" value="{{ $supplier -> email }}"
                        data-error=".errorTxt2">
                      <label for="supplieremail">Supplier Email</label>
                      <small class="errorTxt2"></small>
                    </div>
                    <div class="col s12 input-field">
                      <input id="chinaname" name="chinaname" type="text" class="validate" value="{{ $supplier -> chinaname }}"
                        data-error=".errorTxt4">
                      <label for="chinaname">China Name</label>
                      <small class="errorTxt4"></small>
                    </div>
                    <div class="col s12 input-field">
                      <input id="chinaaddress" name="chinaaddress" type="text" class="validate" value="{{ $supplier -> chinaaddress }}"
                        data-error=".errorTxt6">
                      <label for="chinaaddress">China Address</label>
                      <small class="errorTxt6"></small>
                    </div>
                    <div class="col s12 input-field">
                      <input id="telephone2" name="telephone2" type="text" class="validate" value="{{ $supplier -> telephone2 }}"
                        data-error=".errorTxt8">
                      <label for="telephone2">Telephone 2</label>
                      <small class="errorTxt8"></small>
                    </div>
                    <div class="col s12 input-field">
                      <input id="supplierfax" name="supplierfax" type="text" class="validate" value="{{ $supplier -> fax }}"
                        data-error=".errorTxt10">
                      <label for="supplierfax">Supplier Fax</label>
                      <small class="errorTxt10"></small>
                    </div>
                  </div>
                </div>
                <div class="col s12">
                  <label for="supplierremarks">Supplier Remarks</label>
                  <small class="errorTxt2"></small>
                  <input id="supplierremarks" name="supplierremarks" class="materialize-textarea" value="{{ $supplier -> remarks }}" />
                </div>
                <div class="col s12 display-flex justify-content-end mt-3">
                  <button type="submit" class="btn mr-1">
                    Update</button>
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
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/page-sales.js')}}"></script>
<script src="{{asset('js/scripts/page-supplier.js')}}"></script>
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
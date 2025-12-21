{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Delivery Note Special Handling')

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
              <h5 class="media-heading mt-0">Delivery Note Report Info</h5>
            </div>
            <!-- users edit media object ends -->
            <!-- users edit account form start -->
            <form id="specialhandleForm" action="/deliverynotereport_update" method="post">
              {{ csrf_field() }}

              <div class="row">
                <div class="col s12 input-field">
                  <input type="hidden" name="rep_id" id="rep_id" value="{{ $deliverynotereport -> id }}">
                  <h6>Delivery Note NO: <span class="noteno">{{ $deliverynotereport -> note_no }}</span></h6>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="user" name="user" type="text" class="validate" value="{{ $deliverynotereport -> user }}" data-error=".errorTxt1">
                  <label for="user">User</label>
                  <small class="errorTxt1"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="costcentre" name="costcentre" type="text" class="validate" value="{{ $deliverynotereport -> costcentre }}" data-error=".errorTxt2">
                  <label for="costcentre">Cost Centre</label>
                  <small class="errorTxt2"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="notedate" name="notedate" type="text" class="validate" value="{{ $deliverynotereport -> dn_date }}" data-error=".errorTxt3">
                  <label for="notedate">Note Date</label>
                  <small class="errorTxt3"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="itemcode" name="itemcode" type="text" class="validate" value="{{ $deliverynotereport -> item_code }}" data-error=".errorTxt6">
                  <label for="itemcode">Item Code</label>
                  <small class="errorTxt6"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="description" name="description" type="text" class="validate" value="{{ $deliverynotereport -> specification }}" data-error=".errorTxt7">
                  <label for="description">Description</label>
                  <small class="errorTxt7"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="qty" name="qty" type="text" class="validate" value="{{ $deliverynotereport -> request_qty }}" data-error=".errorTxt8">
                  <label for="qty">Request Qty</label>
                  <small class="errorTxt8"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="unit" name="unit" type="text" class="validate" value="{{ $deliverynotereport -> unit }}" data-error=".errorTxt9">
                  <label for="unit">Unit</label>
                  <small class="errorTxt9"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="packing" name="packing" type="text" class="validate" value="{{ $deliverynotereport -> packing }}" data-error=".errorTxt10">
                  <label for="packing">Packing</label>
                  <small class="errorTxt10"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="unitprice" name="unitprice" type="text" class="validate" value="{{ $deliverynotereport -> unit_price }}" data-error=".errorTxt11">
                  <label for="unitprice">Unit Price</label>
                  <small class="errorTxt11"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input type="hidden" name="total_price" id="total_price" value="{{ $deliverynotereport -> total_price }}">
                  <h6>Total Price: <span class="totalprice">{{ $deliverynotereport -> total_price }}</span></h6>
                </div>
              </div>
              <div class="row">
                <div class="col s12 m4 input-field">
                  <select class="status" id="status" name="status">
                    <option value="1" {{ $deliverynote -> status < 2 ? 'selected' : '' }}>Active</option>
                    <option value="2" {{ $deliverynote -> status == 2 ? 'selected' : '' }}>Void</option>
                  </select>
                  <label>Status</label>
                </div>
                <div class="col s12 display-flex justify-content-end mt-3">
                  <button type="submit" class="btn  mr-1">Update</button>
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
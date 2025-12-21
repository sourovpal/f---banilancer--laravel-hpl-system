{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Sales Order Special Handling')

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
              <h5 class="media-heading mt-0">Sales Order Report Info</h5>
            </div>
            <!-- users edit media object ends -->
            <!-- users edit account form start -->
            <form id="specialhandleForm" action="/salesorderreport_update" method="post">
              {{ csrf_field() }}

              <div class="row">
                <div class="col s12 input-field">
                  <input type="hidden" name="rep_id" id="rep_id" value="{{ $salesorderreport -> id }}">
                  <h6>Sales Order NO: <span class="orderno">CS-SO-202004190000</span></h6>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="user" name="user" type="text" class="validate" data-error=".errorTxt1" value="{{ $salesorderreport -> user }}">
                  <label for="user">User</label>
                  <small class="errorTxt1"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="costcentre" name="costcentre" type="text" class="validate" value="{{ $salesorderreport -> costcentre }}" data-error=".errorTxt2">
                  <label for="costcentre">Cost Centre</label>
                  <small class="errorTxt2"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="order_date" name="order_date" type="text" class="validate" value="{{ $salesorderreport -> order_date }}" data-error=".errorTxt3">
                  <label for="order_date">Order Date</label>
                  <small class="errorTxt3"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="dn_no" name="dn_no" type="text" class="validate" value="{{ $salesorderreport -> dn_no }}" data-error=".errorTxt4">
                  <label for="dn_no">Delivery Note</label>
                  <small class="errorTxt4"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="dn_date" name="dn_date" type="text" class="validate" value="{{ $salesorderreport -> dn_date }}" data-error=".errorTxt5">
                  <label for="dn_date">Delivery Date</label>
                  <small class="errorTxt5"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="item_code" name="item_code" type="text" class="validate" value="{{ $salesorderreport -> item_code }}" data-error=".errorTxt6">
                  <label for="item_code">Item Code</label>
                  <small class="errorTxt6"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="specification" name="specification" type="text" class="validate" value="{{ $salesorderreport -> specification }}" data-error=".errorTxt7">
                  <label for="specification">Description</label>
                  <small class="errorTxt7"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="request_qty" name="request_qty" type="number" onchange="requestQty(this.value)" class="validate" value="{{ $salesorderreport -> request_qty }}" data-error=".errorTxt8">
                  <label for="request_qty">Request Qty</label>
                  <small class="errorTxt8"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="unit" name="unit" type="text" class="validate" value="{{ $salesorderreport -> unit }}" data-error=".errorTxt9">
                  <label for="unit">Unit</label>
                  <small class="errorTxt9"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="packing" name="packing" type="text" class="validate" value="{{ $salesorderreport -> packing }}" data-error=".errorTxt10">
                  <label for="packing">Packing</label>
                  <small class="errorTxt10"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="unit_price" name="unit_price" type="text" class="validate" value="{{ $salesorderreport -> unit_price }}" data-error=".errorTxt11">
                  <label for="unit_price">Unit Price</label>
                  <small class="errorTxt11"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input type="hidden" name="total_price" id="total_price" value="{{ $salesorderreport -> unit_price * $salesorderreport -> request_qty }}">
                  <h6>Total Price: <span class="totalprice" id="total_price_display">{{ $salesorderreport -> unit_price * $salesorderreport -> request_qty }}</span></h6>
                </div>
              </div>
              <div class="row">
                <div class="col s12 m4 input-field">
                  <input id="approver" name="approver" type="text" class="validate" value="{{ $salesorderreport -> approver }}" data-error=".errorTxt12">
                  <label for="approver">Approver</label>
                  <small class="errorTxt12"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <input id="approve_date" name="approve_date" type="text" class="validate" value="{{ $salesorderreport -> approve_date }}" data-error=".errorTxt13">
                  <label for="approve_date">Approved Date</label>
                  <small class="errorTxt13"></small>
                </div>
                <div class="col s12 m4 input-field">
                  <select class="status" id="status" name="status">
                    <option value="1" {{ $salesorder -> status < 5 ? 'selected' : '' }}>Active</option>
                    <option value="5" {{ $salesorder -> status == 5 ? 'selected' : '' }}>Cancel</option>
                  </select>
                  <label>Status</label>
                </div>
                <div class="col s12 display-flex justify-content-end mt-3">
                  <button type="submit" class="btn mr-1">Update</button>
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
<!-- <script src="{{asset('js/scripts/page-users.js')}}"></script> -->
<script src="{{asset('js/scripts/page-sales.js')}}"></script>
<script>
     function requestQty(e) {
        if(e <= 0){
            alert("Request Qty must Greater then Zero")
        }
    }
    $(document).ready(function() {
        $("#specialhandleForm").submit(function() {
            let val = $("#request_qty").val()
            if(val <= 0){
                alert("Request Qty must Greater then Zero")
            }
        })
    })
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
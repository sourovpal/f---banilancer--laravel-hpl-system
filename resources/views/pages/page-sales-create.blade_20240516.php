{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Create Sales Order')

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
<style>
  .loader {
    border: 8px solid #f3f3f3; /* Light grey */
    border-top: 8px solid #1976D2; /* Blue */
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
    position: fixed;
    top: 50%;
    left: 50%;
    margin-top: -25px; /* Half of width */
    margin-left: -25px; /* Half of height */
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }

  .model-loading {
    filter: blur(30px); /* Adjust the blur value for the loading state */
  }

</style>
{{-- page content --}}
@section('content')
<!-- users list start -->
<section class="users-list-wrapper section">
  <div class="users-list-table">
    <form id="salesorderRegisterForm" action="/salesorder_create_process" method="Get">
      {{ csrf_field() }}

      <div class="card">
        <div class="card-content">
          <div class="media display-flex align-items-center">
            <span class="card-title">Reference</span>
          </div>
          <div class="row">
            <div class="col s12 m6">
              <!--<h6>Request Date: <span class="requestdate">{{ $data['date'] }}</span></h6>-->
            </div>
            <div class="col s12 m6">
              <h6>Staff: <span class="staff">{{ Auth::user() -> username }}</span></h6>
            </div>
          </div>
        </div>
      </div>

      <div class="card staff_wrap">
        <div class="card-content">
          <div class="media display-flex align-items-center">
            <span class="card-title">Staff Info</span>
          </div>
          <div class="row">
            <div class="col s12 m4">
              <h6>Department: <span class="requestdate">{{ $data['department'] }}</span></h6>
            </div>
            <div class="col s12 m4">
              <select id="costcentre" name="costcentre">
                <option value="0">Select Cost Centre</option>
                @foreach($costcenters as $costcenter)
                  <option value="{{ $costcenter -> id }}">{{ $costcenter -> code }}</option>
                @endforeach
              </select>
              <label>Cost Centre:</label>
            </div>
            
			
          </div>
        </div>
      </div>

      <div class="card add_item">
        <div class="card-content">
          <div class="media display-flex align-items-center">
            <a class="waves-effect waves-light btn modal-trigger" href="#add_item_modal" id="item_modal_display"><i class="material-icons right">add_circle_outline</i>Add Item</a>

            <div id="add_item_modal" class="modal modal-fixed-footer">
              <div id="loader" class="loader"></div>
              <div class="modal-content" id="modal-content-loader">
                <h4 class="mb-3">Add Item</h4>
                <div class="row">
                  <div class="col s12 m4">
                    <select class="category" id="category">
                      <option value="0">All Categories</option>
                      @foreach($categories as $category)
                        <option value="{{ $category -> id }}">{{ $category -> name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="row mt-3 mb-5">
                  <div class="col s12 m4">
                    <h6>Search By ID </h6>
                    <input id="search_id" type="text">
                  </div>
                  <div class="col s12 m4">
                    <h6>Search By Name </h6>
                    <input id="search_name" type="text">
                  </div>
                  <div class="col s12 m4">
                    <h6>Search By Description </h6>
                    <input id="search_description" type="text">
                  </div>
                </div>
                <table id="modal_item" class="table table-hover" style="width:100%;">
                  <thead>
                    <tr>
                      <th>
                        <label>
                          <input type="checkbox" class="select-all" />
                          <span></span>
                        </label>
                      </th>
                      <th>Image</th>
                      <th>Code</th>
                      <th>Name</th>
                      <th>Specification</th>
                      <th>Pack</th>
                    </tr>
                  </thead>
                  <tbody>
                    
                  </tbody>
                </table>
              </div>
              <div class="modal-footer">
                <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat " id="add_item_btn">OK</a>
              </div>
            </div>
          </div>
          <!-- datatable start -->
          <div class="responsive-table mt-1">
            <table id="item_table" class="users-list-datatable table">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Name</th>
                  <th>Specification</th>
                  <th>Unit</th>
                  <th>Pack</th>
                  @if(auth() -> user() -> role != 'external')
                  <th>Price</th>
                  @endif
                  <th>Qty</th>
                  <th>Remove</th>
                </tr>
              </thead>
              <tbody>
                
              </tbody>
            </table>
          </div>
          <!-- datatable ends -->
        </div>
      </div>
      
	  
	  
	  
      <div class="card">
        <div class="card-content">
          <div class="media display-flex align-items-center">
            <span class="card-title">Sales Order Remarks:</span>
          </div>
          <textarea id="remarks" name="remarks" class="materialize-textarea"></textarea>
          <div class="action_wrap mt-2 text-right">
		  <!--
		  <div class="col s12 m4">
              <select id="approver" name="approver">
                <option value="0">Select Approver</option>
                @foreach($data['approvers'] as $approver)
                  <option value="{{ $approver -> id }}">{{ $approver -> username }}</option>
                @endforeach
              </select>
              <label>Approver:</label>
            </div>
			-->
            <button type="submit" class="btn indigo mr-2">
              Next</button>
            <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</section>

<div id="confirm_modal" class="modal modal-fixed-footer">
  <div class="modal-content">
    <p style="color: black !important;">Are you sure that you will delete this item?</p>
    <div class="row mt-5">
        <input type="hidden" id="item_id_cancel">
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
@endsection

{{-- page script --}}
@section('page-script')
<!-- <script src="{{asset('js/scripts/page-users.js')}}"></script> -->
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
else{
$('.administration_menu').parent().find('.collapsible-sub li:first-child').hide();
$('.administration_menu').parent().find('.collapsible-sub li:nth-child(2)').hide();
}
   }

function removePrice () {
  @if(auth() -> user() -> role == 'external')
  $("tr.item_row").each(function() {
    $(this).children("td:eq(5)").remove();
  });
  @endif
}


</script>
@endsection
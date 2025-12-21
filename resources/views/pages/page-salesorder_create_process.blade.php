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
<body>
<section class="users-list-wrapper section">
 <div class="card">
        <div class="card-content">
          <div class="media display-flex align-items-center">
            <span class="card-title">Reference</span>
          </div>
          <div class="row">
            <div class="col s12 m6">
              <h6>Staff: <span class="staff">{{ Auth::user() -> username }}</span></h6>
            </div>
          </div>
        </div>
      </div>
	  <!--
	  <table id="modal_item" class="table table-hover" style="width:100%;">
                  <thead>
                    <tr>
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
				-->
		<br />
		<div class="col s12 m6">
           </div>
		<br />
		
		<div class="card-content">
		  @if ($totalAmount > 100000)
		  <div class="media display-flex align-items-center">
            <span class="card-title" style="color:red;"><h3>Total amount cannot over $100,000.</h3></span>
          </div>
		  @else
			<form action="{{ route('SOCupdateApprover') }}" method="POST">
			@csrf
			<div class="col s12 m4">
				<select id="approver" name="approver">
					<option value="0">Select Approver</option>
					@foreach($approvers as $approver)
						<option value="{{ $approver->id }}">{{ $approver -> username }}</option>
					@endforeach
				</select>
				<label for="approver">Approver:</label>
			</div>
			@endif
			@if ($totalAmount <= 100000)
			<div class="action_wrap mt-2 text-right">
				<button type="submit" class="btn indigo mr-2" id="So_approver_confirm">
					Submit
				</button>
			</div>
			@endif
		</form>
</section>
</body>

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
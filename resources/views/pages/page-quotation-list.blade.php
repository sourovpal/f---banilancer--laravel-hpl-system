{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Current Small Order List')

{{-- vendors styles --}}
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{asset('vendors/data-tables/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" type="text/css"
          href="{{asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css')}}">

@endsection

{{-- page styles --}}
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">

@endsection

{{-- page content --}}
@section('content')
    <!-- users list start -->
    <section class="users-list-wrapper section">
        <div class="users-list-table">
            <div class="card">
                <div class="card-content">
                <!-- <span class="card-title">Master Admin</span> -->
                    <!-- datatable start -->
                    <div class="responsive-table">
                        <table id="hanif" class=" table">
                            <thead>
                            <tr>
                                <th>Small Order No</th>
                                <th>Small Order Date</th>
                                <th>Cost Center</th>
                                <th>User Name</th>
                                <th>Approved by</th>
                                <th>Approved Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($quotations as $quotation)
							<!-- 不需要再添加額外的條件 -->
							<tr>
									<td>
										{{ $quotation['code'] }}
										<a href="javascript:;" data-code="{{ $quotation['code'] }}" class="nested_table_show">
											<i class="material-icons">add_circle_outline</i>
										</a>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          
										 <a href="javascript:;" class="nested_table_hide">
											<i class="material-icons">remove_circle_outline</i>
										</a>
									</td>
									<td>{{ $quotation['date'] }}</td>
									<td>{{ $quotation['costcentre'] }}</td>
									<td>{{ $quotation['extuser'] }}</td>
									<td>{{ isset($quotation['apprusername']) ? $quotation['apprusername'] : '' }}</td>
									<td>{{ isset($quotation['appr_date']) ? $quotation['appr_date'] : '' }}</td>
									<td>{{ $quotation['status'] == 0 ? 'Open' : 'Approved' }}</td>
									<td>
										@if (Auth::user()->role == 'external')
											@if ($quotation['all_price_non_zero'])
												<a href="{{ asset('/update-quotation-record/' . $quotation['id']) }}">View</a>
												@if ($quotation['status'] == '0' && $quotation['appruser_id'] == Auth::user()->id)
													| <a href="{{ asset('/update-quotation-approve/' . $quotation['id']) }}">Approve</a>
												@endif
											@else
												<a href="{{ asset('/update-quotation-record/' . $quotation['id']) }}">View</a>
											@endif
										@elseif(Auth::user()->role == 'internal' || Auth::user()->role == 'master')
											@if ($quotation['status'] == 0)
											   <a href="{{ asset('/update-quotation-record/' . $quotation['id']) }}">Edit</a>
											@endif
											
											@if ($quotation['status'] == 1)
												 <a href="{{ asset('/create-deliver-note-from-quotation/' . $quotation['id']) }}" class="sales_order_deliver">Confirm</a>
											@endif
										@endif
									</td>    
							</tr>
							<tr class="nested_table_wrap">
								<td colspan="11">
									<table id="nested_table" class="nested_table table">
										<thead>
											<tr>
												<th>Name</th>
												<th>Specification</th>
												<th>Unit</th>
												<th>Pack</th>
												<th>Qty</th>
												{{-- <th>Price</th> --}}
												<th>Remarks</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($quotation['quotationitems'] as $quotationitem)
												<tr>
													<td>{{ $quotationitem['name'] }}</td>
													<td>{{ $quotationitem['specification'] }}</td>
													<td>{{ $quotationitem['unit'] }}</td>
													<td>{{ $quotationitem['pack'] }}</td>
													<td>{{ $quotationitem['qty'] }}</td>
													{{-- <td>{{ $quotationitem['price'] }}</td> --}}
													<td>{{ $quotationitem['remarks'] }}</td>
												</tr>
											@endforeach
										</tbody>
									</table>
								</td>
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

    <div id="approve_modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <p style="color: black !important;">Are you sure that you will approve this Sales Order?</p>
            <input type="hidden" id="user_id" value="{{ Auth::user() -> id }}">
            <input type="hidden" id="so_id">
            <input type="hidden" id="appruser_id">
            <input type="hidden" id="appr_role" value="{{ Auth::user() -> appr_role }}">
            <div class="row mt-5">
                <div class="col s2"></div>
                <div class="col s3"><button class="btn btn-success" id="approve_btn">Approve</button></div>
                <div class="col s2"></div>
                <div class="col s3"><button class="btn btn-danger" id="reject_btn">Reject</button></div>
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

    <script src="{{asset('js/scripts/advance-ui-modals.js')}}"></script>
@endsection

{{-- page script --}}
@section('page-script')

    <script src="{{ asset('js/scripts/page-users.js?v=1.0') }}"></script>

    <script src="{{ asset('js/scripts/page-sales.js') }}"></script>

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

        console.log('test')
        $('').DataTable({
            responsive: true,
            'columnDefs': [{
                "orderable": true,
                "targets": [0, 1, 2]
            }]
        });

        $('#department-list-datatable').DataTable({
            responsive: true,
            'columnDefs': [{
                "orderable": true,
                "targets": [0, 1, 2] // targets only the main table columns
            }],
            "order": [] // disable the initial ordering
        });

    </script>
@endsection
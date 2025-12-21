{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'Current Small Order List')

{{-- vendors styles --}}
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/data-tables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection

{{-- page styles --}}
@section('page-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/page-users.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/page-sales.css') }}">
@endsection

{{-- page content --}}
@section('content')
    <!-- users list start -->
    <section class="users-list-wrapper section">
    <div class="users-list-table">
        <div class="card">
            <div class="card-content">
                <!-- datatable start -->
                <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
                <div class="responsive-table">
                    <table id="" class="users-list-datatable table">
                        <thead>
                            <tr>
                                <th>Small Order No</th>
                                <th>Small Order Date</th>
                                <th>Department</th>
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
									<td>{{ $quotation['department'] }}</td>
									<td>{{ $quotation['costcentre'] }}</td>
									<td>{{ $quotation['extuser'] }}</td>
									<td>{{ isset($quotation['apprusername']) ? $quotation['apprusername'] : '' }}</td>
									<td>{{ isset($quotation['appr_date']) ? $quotation['appr_date'] : '' }}</td>
									<td>{{ $quotation['status'] == 0 ? 'Open' : 'Waiting for delivery' }}</td>
									<td>
										@if (Auth::user()->role == 'external')
											<a href="{{ asset('/update-quotation-record/' . $quotation['id']) }}">View</a>
										@elseif(Auth::user()->role == 'internal' || Auth::user()->role == 'master')
											<a href="{{ asset('/update-quotation-record/' . $quotation['id']) }}">Edit</a>
											@if (isset($quotation['appr_date']) && !is_null($quotation['appr_date']))
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
    <!-- users list ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
    <script src="{{ asset('vendors/data-tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
@endsection

{{-- page script --}}
@section('page-script')
    <script src="{{ asset('js/scripts/page-users.js') }}"></script>
    <script src="{{ asset('js/scripts/page-sales.js') }}"></script>
    <script>
        if ('{{ Auth::user()->role }}' == 'internal') {
            $('.sales_order_create_menu').parent().hide()
            $('.company_menu').parent().hide()
            if ('{{ Auth::user()->admin_role }}' == 0) {
                $('.administration_menu').parent().hide()
                $('.department_menu').parent().hide()
            }
        }
        if ('{{ Auth::user()->role }}' == 'external') {
            $('.delivery_note_menu').parent().hide()
            $('.purchase_order_menu').parent().hide()
            $('.good_receive_menu').parent().hide()
            $('.category_item_menu').parent().hide()
            $('.supplier_menu').parent().hide()
            $('.quotation_menu').parent().hide()
            $('.company_menu').parent().hide()
            if ('{{ Auth::user()->admin_role }}' == 0) {
                $('.administration_menu').parent().hide()
                $('.department_menu').parent().hide()
            }
        }



        $(document).ready(function() {
            // Add event listener for length change
            $('.users-list-datatable').on('length.dt', function(e, settings, len) {
                var user_id = $('#user_id').val();
                localStorage.setItem("setPaginationUser", user_id);
                if ('{{ Auth::user()->id }}' == localStorage.getItem("setPaginationUser")) {
                    localStorage.setItem("setPagination", len);
                } else {
                    localStorage.setItem("setPagination", "10");
                }
                // Perform your desired actions here
            });
        });
    </script>
@endsection

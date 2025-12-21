{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Current Sales Order List')

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
                @php
                    function shortenDescription($description, $maxLength = 10) {
                          // Check if the description length is already within the limit
                          if (strlen($description) <= $maxLength) {
                              return $description;
                          } else {
                              // Shorten the description and add ellipsis
                              return substr($description, 0, $maxLength - 3) . "...";
                          }
                      }
                @endphp
                <!-- <span class="card-title">Master Admin</span> -->
                    <!-- datatable start -->
                    <div class="responsive-table">
                        <table id="hanif" class=" table">
                            <thead>
                            <tr>
                                <th>Order No</th>
                                <th>Order Date</th>
                                <th>Cost Center Code</th>
                                {{-- <th>Cost Center Name</th> --}}
                                <th>User Name</th>
                                <th>Approval By</th>
                                <th>Approve Date</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($salesorders as $salesorder)
                                <tr>
                                    <td>{{ $salesorder['no'] }}<a href="javascript:;" class="nested_table_show"><i class="material-icons">add_circle_outline</i></a><a href="javascript:;" class="nested_table_hide"><i class="material-icons">remove_circle_outline</i></a></td>
                                    <td>{{ $salesorder['orderdate'] }}</td>
                                    <td>{{ $salesorder['costcentre'] }}</td>
                                    {{--<td>{{ shortenDescription($salesorder['name_costcentre']) }}</td> --}}
                                    <td>{{ $salesorder['extusername'] }}</td>
                                    <td>{{ $salesorder['approver'] }}</td>
                                    <td>{{ $salesorder['approverdate'] }}</td>
                                    <td>{{ $salesorder['remarks'] }}</td>
                                    <td>
                                        <?php
                                        if ($salesorder['status'] == 0) echo 'Waiting for Approval';
                                        else if ($salesorder['status'] == 1) echo 'Approved';
                                        else if ($salesorder['status'] == 2) echo 'Ready for Delivery';
                                        ?>
                                    </td>
                                    <td>
                                        @if ((Auth::user() -> role == 'master' || Auth::user() -> admin_role == 1 || (Auth::user() -> appr_role != 0 && Auth::user() -> dep_id == $salesorder['dep_id']) || (Auth::user() -> appr_role == 0 && Auth::user() -> id == $salesorder['extuser_id'])) && !$salesorder['status'])
                                            <a class="ml-5" href="{{asset('/update-sales-order-record/' . $salesorder['id'])}}">Edit</a>
                                        @endif
                                        @if (!$salesorder['status'])
                                            @if (Auth::user() -> role == 'external' && Auth::user() -> appr_role != 0 && $salesorder['appruser_id'] == Auth::user()->id)
                                                 <a href="{{ asset('/update-sales-approve/' . $salesorder['id']) }}">Approve</a>
                                            @endif
                                        @else
                                            @if (Auth::user() -> role == 'internal' && $salesorder['status'] == 1)
                                                <a href="{{asset('/create-deliver-note/' . $salesorder['id'])}}" class="sales_order_deliver">Ready To Deliver</a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                <tr class="nested_table_wrap">
                                    <td colspan="11">
                                        <table id="nested_table" class="nested_table table">
                                            <thead>
                                            <tr>
											    <th>Image</th>
                                                <th>Code</th>
                                                <th>Name</th>
                                                <th>Unit</th>
                                                <th>Qty</th>
												<th>Pack</th>
                                                <th>Remarks</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($salesorder['salesorderitems'] as $salesorderitem)
                                                <tr>
                                                    <td><img src="/public/upload/item/{{ $salesorderitem['image'] }}" width="100"></td>
													<td>{{ $salesorderitem['code'] }}</td>
                                                    <td>{{ $salesorderitem['name'] }}</td>
                                                    <td>{{ $salesorderitem['unit'] }}</td>
                                                    <td>{{ $salesorderitem['qty'] }}</td>
													<td>{{ $salesorderitem['pack'] }}</td>
                                                    <td>{{ $salesorderitem['remarks'] }}</td>
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
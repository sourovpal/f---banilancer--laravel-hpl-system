{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'Order History')

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
        <div class="users-list-tabl">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Order History</span>
                    <!-- datatable start -->
                    @php
                        function shortenDescription($description, $maxLength = 10)
                        {
                            // Check if the description length is already within the limit
                            if (strlen($description) <= $maxLength) {
                                return $description;
                            } else {
                                // Shorten the description and add ellipsis
                                return substr($description, 0, $maxLength - 3) . '...';
                            }
                        }

                    @endphp
                    <div class="responsive-table">
                        <table id="" class="">
                            <thead>
                                <tr>
                                    <th>Order No</th>
                                    <th>Delivery Date</th>
                                    <th>Department</th>
                                    <th>Cost Center</th>
                                    <th>User Name</th>
                                    <th>Approval By</th>
                                    <th>Approve Date</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($salesorders_new as $salesorder)
                                    <tr>
                                        <td>{{ $salesorder['no'] }}<a href="javascript:;" class="nested_table_show"><i
                                                    class="material-icons">add_circle_outline</i></a><a href="javascript:;"
                                                class="nested_table_hide"><i
                                                    class="material-icons">remove_circle_outline</i></a></td>
                                        <td>{{ $salesorder['orderdate'] }}</td>
                                        <td>{{ $salesorder['department'] }}</td>
                                        <td>{{ $salesorder['costcentre'] }}</td>
                                        <td>{{ $salesorder['extusername'] }}</td>
                                        <td>{{ $salesorder['approver'] }}</td>
                                        <td>{{ $salesorder['approverdate'] }}</td>
                                        <td>{{ $salesorder['remarks'] }}</td>
                                        <td>
                                            <?php
                                            if ($salesorder['status'] == 0) {
                                                echo 'Waiting for Approval';
                                            } elseif ($salesorder['status'] == 1) {
                                                echo 'Approved';
                                            } elseif ($salesorder['status'] == 2) {
                                                echo 'Delivered';
                                            } elseif ($salesorder['status'] == 3) {
                                                echo 'Delivered';
                                            } else {
                                                echo 'Canceled';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="/printPdfSalesOrder/{{ $salesorder['id'] }}">Report</a>
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
                                                        <th>Price</th>
                                                        <th>Remarks</th>
                                                        <th>Special Handling</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($salesorder['salesorderitems'] as $salesorderitem)
                                                        <tr>
                                                            <td>{{ $salesorderitem['name'] }}</td>
                                                            <td>{{ $salesorderitem['specification'] }}</td>
                                                            <td>{{ $salesorderitem['unit'] }}</td>
                                                            <td>{{ $salesorderitem['pack'] }}</td>
                                                            <td>{{ $salesorderitem['qty'] }}</td>
                                                            <td>{{ $salesorderitem['price'] }}</td>
                                                            <td>{{ $salesorderitem['remarks'] }}</td>
                                                            <td><a
                                                                    href="{{ asset('/special-sales-order-handling/' . $salesorderitem['so_re']) }}"><i
                                                                        class="material-icons">edit</i></a></td>
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
                    <!-- datatable ends
                    <a type="button" class="btn btn-light" style="margin-top: 40px;float:right;"
                        href="/printExcelSalesOrder">Export to Excel</a>
					 -->
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
            } else {
                $('.administration_menu').parent().find('.collapsible-sub li:first-child').hide();
                $('.administration_menu').parent().find('.collapsible-sub li:nth-child(2)').hide();
            }
        }

        $(document).ready(function() {
            $('#my-table').DataTable({
                paging: true,
                searching: true,
                info: false,
                columnDefs: [{
                    orderable: true,
                    targets: '.table-ordering th:not(.no-ordering)',
                }]
            });

            $('#nested_table').DataTable({
                paging: false,
                searching: false,
                info: false,
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, 3, 4, 5, 6,
                        7] // add the column indexes where you want to disable ordering
                }]
            });

            $('#my-table').DataTable().columns('.no-ordering').orderable(false);
        });
    </script>
@endsection

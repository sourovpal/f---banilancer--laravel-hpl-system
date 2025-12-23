{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'Quotation History')

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
                    <span class="card-title">Small Order List</span>
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
								<th>Small Order No</th>
								<th>Small Order Date</th>
								<th>Department</th>
								<th>Cost Center</th>
								<th>User Name</th>
								<!--<th>Remarks</th>-->
								<th>Status</th>
								<th>Action</th>
							  </tr>
                            </thead>
                            <tbody>
                               @foreach($quotations_new as $quotation)
                                    <tr>
                                        <td>{{ $quotation['code'] }}<a href="javascript:;" data-code="{{ $quotation['code'] }}" class="nested_table_show"><i class="material-icons">add_circle_outline</i></a><a href="javascript:;" class="nested_table_hide"><i class="material-icons">remove_circle_outline</i></a></td>
                  <td>{{ $quotation['date'] }}</td>
                  <td>{{ $quotation['department'] }}</td>
                  <td>{{ $quotation['costcentre'] }}</td>
				  <td>{{ $quotation['extuser'] }}</td>
                  <!--<td>{{ $quotation['remarks'] }}</td>-->
                  <td>
<?php
                      if ($quotation['status'] == 0) echo 'Open';
                      else if ($quotation['status'] == 1) echo 'Waiting for delivery';
                      else if ($quotation['status'] == 2) echo 'Cancel';
                      else if ($quotation['status'] == 3) echo 'Close';
                    ?>
</td>
                  <td>
                      <a href="{{ asset('/update-quotation-record/' . $quotation['id']) }}">Edit</a> 
                      <a href="/printPdfQuotation/{{ $quotation['id'] }}">Quotation</a> 
                      <a href="/printPdfQuotation/{{ $quotation['id'] }}?invoice=1">Invoice</a>
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
                            <td>{{ $quotationitem['price'] }}</td>
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
                    <a type="button" class="btn btn-light" style="margin-top: 40px;float:right;"
                        href="/printExcelSalesOrder">Export to Excel</a>
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

{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'Update Small Order Record')

{{-- vendors styles --}}
@section('vendor-style')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/data-tables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
@endsection

{{-- page styles --}}
@section('page-style')
  <style>
        #rightImage:hover img {
            width: 40% !important;
            height: auto !important;
            position: absolute;
            float: none;
            top: 0;
            left: 0;
             z-index: 99999999999999999;

        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/page-users.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/page-sales.css') }}">
@endsection

{{-- page content --}}
@section('content')
    <!-- users list start -->
    <form action="/quotation_register" method="post" id="quotationRegisterForm" data-q_id="{{ $quotation['id'] }}">
        {{ csrf_field() }}
        <section class="users-list-wrapper section">
            <div class="users-list-table">
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
                                <h6>Staff: <span class="staff">{{ $quotation['extuser'] }}</span></h6>
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
                            <div class="col s12 m6">
                                <h6>Department: <span class="requestdate">{{ $department }}</span></h6>
                            </div>
                            <div class="col s12 m6">
                                <select class="disable-on-external-role" id="old_costcenter" name="costcentre">
                                    <option value="0">Select Cost Centre</option>
                                    @foreach ($costcenters as $costcenter)
                                        <option value="{{ $costcenter->id }}"
                                            {{ $quotation['costcentre'] == $costcenter->name ? 'selected' : '' }}>
                                            {{ $costcenter->code }}</option>
                                    @endforeach
                                </select>
                                <label>Cost Centre:</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6 input-field">
                                <select class="disable-on-external-role" name="status" id="status">
                                    <option value="0" {{ $quotation['status'] == 0 ? 'selected' : '' }}>Open</option>
                                    <option value="1" {{ $quotation['status'] == 1 ? 'selected' : '' }}>Waiting for
                                        delivery</option>
                                    <option value="2" {{ $quotation['status'] == 2 ? 'selected' : '' }}>Cancel
                                    </option>
                                    <option value="3" {{ $quotation['status'] == 3 ? 'selected' : '' }}>Close</option>
                                </select>
                                <label style="margin-top: 0px !important;">Status</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card add_item">
                    <div class="card-content">
                        <div class="media display-flex align-items-center">
                            <a class="waves-effect waves-light btn modal-trigger clos-hide" href="#add_item_modal"><i
                                    class="material-icons right">add_circle_outline</i>Add Item</a>
                        </div>
                        <!-- datatable start -->
                        <div class="responsive-table mt-1">
                            <table id="item_table" class="users-list-datatable table">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Specification</th>
                                        <th>Unit</th>
                                        <th>Pack</th>
                                        <th>Qty</th>
                                        <th>Cost</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotation['quotationitems'] as $quotationitem)
                                        <tr>
                                            <input type="hidden"  name="itemId[]" value="{{ $quotationitem['id'] }}">
                                             <!--<td id="rightImage">
                                                <a><img
                                                        style="width: 80px;height:60px"
                                                        src="{{ asset('customImage/images/' . $quotationitem['image']) }}"></a>-->
                                            <td><a href="{{ asset('customImage/images/' . $quotationitem['image']) }}">View</a>
                                            <td>{{ $quotationitem['name'] }}</td>
                                            <td>{{ $quotationitem['specification'] }}</td>
                                            <td>{{ $quotationitem['unit'] }}</td>
                                            <td>{{ $quotationitem['pack'] }}</td>
                                            {{-- <td>{{ $quotationitem['price'] }}</td> --}}
                                            
                                            <td class="item_qty"><input type="text" name="itemqty[]"  item_qty="{{ $quotationitem['id'] }}" class="table_input" value="{{ $quotationitem['qty'] }}"></td>
                                            <td class="item_cost"><input type="text" name="itemcost[]"  item_id="{{ $quotationitem['id'] }}" class="table_input_cost disable-on-external-role" value="{{ $quotationitem['price'] }}"></td>
                                            <td class="quo_item_remark"><input type="text" name="itemremarks[]"  quo_item_remark="{{ $quotationitem['id'] }}" class="table_input" value="{{ $quotationitem['remarks'] }}"></td>
{{--                                                 <td>{{ $quotationitem['remarks'] }}</td>--}}
                                            <td class="disable-on-external-role"><a class="clos-hide"
                                                    href="{{ asset('quotation_item_delete/' . $quotationitem['id']) }}"><i
                                                        class="material-icons disable-on-external-role">cancel</i></a></td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- datatable ends -->
                    </div>
                </div>

                <div class="card">
                    <div class="card-content">
                        <div class="media display-flex align-items-center">
                            <span class="card-title">Custom supplier information</span>
                        </div>
                        <input class="" type="hidden" name="qn_id" value="{{ $data['quotation'] }}">
                        {{-- <input type="hidden" name="costcentre" id="costcentre" value="{{ $quotation['costcentre'] }}"> --}}
                        <input class="disable-on-external-role" id="remarks" name="remarks" class="materialize-textarea" vlaue="{{ $quotation['remarks'] }}" />
                        <div class="action_wrap mt-2 text-right">
                            @if (Auth::user() && Auth::user()->role != 'external')
                                <button type="submit" class="btn mr-2" id="save_all">Update</button>
                            @endif
                            <button type="button" onclick='window.location.href="{{ url()->previous() }}"'
                                class="btn btn-light">Back</button>
                        </div>

                    </div>
                </div>
            </div>
    </form>

    <div id="add_item_modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4 class="mb-3">Add Item</h4>
            <form id="addItemForm" action="/quotationitem_add" method="post">
                {{ csrf_field() }}

                <div class="row">
                    <div class="col s12 m6 input-field">
                        <input type="hidden" name="quotation" id="quotation" value="{{ $data['quotation'] }}">
                        <input id="itemname" name="itemname" type="text" class="validate" value=""
                            data-error=".errorTxt1">
                        <label for="itemname">Item Name</label>
                        <small class="errorTxt1"></small>
                    </div>
                    <div class="col s12 input-field">
                        <input id="itemspecification" name="itemspecification" class="validate materialize-textarea"
                            data-error=".errorTxt2" />
                        <label for="itemspecification">Item Specification</label>
                        <small class="errorTxt2"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 input-field">
                        <input id="itemunit" name="itemunit" type="text" class="validate" value=""
                            data-error=".errorTxt3">
                        <label for="itemunit">Item Unit</label>
                        <small class="errorTxt3"></small>
                    </div>
                    <div class="col s12 m6 input-field">
                        <input id="itempack" name="itempack" type="text" class="validate" value=""
                            data-error=".errorTxt4">
                        <label for="itempack">Item Pack</label>
                        <small class="errorTxt4"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 m6 input-field">
                        <input id="itemqty" name="itemqty" type="text" class="validate" value=""
                            data-error=".errorTxt5">
                        <label for="itemqty">Item Qty</label>
                        <small class="errorTxt5"></small>
                    </div>
                    {{-- <div class="col s12 m6 input-field">
                  <input id="itemprice" name="itemprice" type="text" class="validate" value="" data-error=".errorTxt6">
                  <label for="itemprice">Item Price</label>
                  <small class="errorTxt6"></small>
                </div> --}}
                    <input id="itemprice" name="itemprice" type="hidden" class="validate" value="0"
                        data-error=".errorTxt6">
                </div>
                <div class="row ">
                    <div class="col s12 input-field ">
                        <input id="remarks" name="remarks" class=" validate materialize-textarea " data-error=".errorTxt7" />
                        <label for="remarks">Remarks</label>
                        <small class="errorTxt7"></small>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12 display-flex justify-content-end mt-3">
                        <button type="submit" class="btn indigo mr-2">Add</button>
                        <button type="button" class="btn btn-light">Back</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- <div class="modal-footer">
                                                                                                                                                                  <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">OK</a>
                                                                                                                                                                </div> -->
    </div>
    </section>
    <!-- users list ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
    <script src="{{ asset('vendors/data-tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('js/scripts/advance-ui-modals.js') }}"></script>
    <script src="{{ asset('vendors/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

{{-- page script --}}
@section('page-script')
    <script src="{{ asset('js/scripts/page-users.js') }}"></script>
    <script src="{{ asset('js/scripts/page-sales.js') }}"></script>
    <script src="{{ asset('js/scripts/page-quotation.js') }}"></script>
    <script src="{{ asset('js/scripts/page-quotation-update.js') }}"></script>
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
            $('.disable-on-external-role').prop('disabled', true);
            $('.clos-hide').parent().hide()
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
    </script>


@endsection

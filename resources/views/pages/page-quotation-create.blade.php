{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title', 'New Quotation')

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
                    <div class="media display-flex align-items-center">
                        <span class="card-title">Reference</span>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <!--<h6>Request Date: <span class="requestdate">{{ $data['date'] }}</span></h6>-->
                        </div>
                        <div class="col s12 m6">
                            <h6>Staff: <span class="staff">{{ Auth::user()->username }}</span></h6>
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
                            <select id="old_costcenter" name="costcenter">
                                <option value="0">Select Cost Centre</option>
                                @foreach ($costcenters as $costcenter)
                                    <option value="{{ $costcenter->id }}"
                                        {{ isset($data['cc_id']) && $data['cc_id'] == $costcenter->id ? 'selected' : '' }}>
                                        {{ $costcenter->code }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>



            <div class="card add_item">
                <div class="card-content">
                    <div class="media display-flex align-items-center">
                        <a class="waves-effect waves-light btn modal-trigger" href="#add_item_modal"><i
                                class="material-icons right">add_circle_outline</i>Add Item</a>

                        <div id="add_item_modal" class="modal modal-fixed-footer">
                            <div class="modal-content">
                                <h4 class="mb-3">Add Item</h4>
                                <form id="addItemForm" action="/quotationitem_add" method="post"
                                    enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="row">
                                        <div class="col s12 m12 input-field">
                                            <input id="itemname" name="itemname" type="text" class="validate"
                                                value="" data-error=".errorTxt1">
                                            <label for="itemname">Item Name</label>
                                            <small class="errorTxt1"></small>
                                            <input type="hidden" name="quotation" id="quotation"
                                                value="{{ $data['quotation'] }}">
                                            <input type="hidden" name="costcentre" id="modal-costcentre">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12 m12 input-field">
                                            <input id="itemspecification" name="itemspecification" type="text"
                                                class="validate" data-error=".errorTxt2">
                                            <label for="itemspecification">Item Specification</label>
                                            <small class="errorTxt2"></small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12 m6 input-field">
                                            <input id="itemunit" name="itemunit" type="text" class="validate"
                                                value="" data-error=".errorTxt3">
                                            <label for="itemunit">Item Unit</label>
                                            <small class="errorTxt3"></small>
                                        </div>
                                        <div class="col s12 m6 input-field">
                                            <input id="itempack" name="itempack" type="text" class="validate"
                                                value="" data-error=".errorTxt4">
                                            <label for="itempack">Item Pack</label>
                                            <small class="errorTxt4"></small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12 m6 input-field">
                                            <input id="itemqty" name="itemqty" type="text" class="validate"
                                                value="" data-error=".errorTxt5">
                                            <label for="itemqty">Item Qty</label>
                                            <small class="errorTxt5"></small>
                                        </div>

                                        {{-- <div class="col s12 m6 input-field">
                          <input id="itemprice" name="itemprice" type="text" class="validate" value="" data-error=".errorTxt6">
                          <label for="itemprice">Item Price</label>
                          <small class="errorTxt6"></small>
                        </div> --}}
                                        <input id="itemprice" name="itemprice" type="hidden" class="validate"
                                            value="0" data-error=".errorTxt6">
                                    </div>
                                    <div class="row">
                                        <div class="col s12 m6 input-field">
                                            <input id="remarks" name="remarks" class="validate -materialize-textarea"
                                                data-error=".errorTxt7" />
                                            <label for="remarks">Remarks</label>
                                            <small class="errorTxt7"></small>
                                        </div>
                                        <div class="col s12 m6 input-field">
                                            <div class="file-field input-field">
                                                <div class="btn">
                                                    <span>Item Image</span>
                                                    <input type="file" name="itemimage">
                                                </div>
                                                <div class="file-path-wrapper">
                                                    <input class="file-path validate" type="text" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col s12 display-flex justify-content-end mt-3">
                                            <button type="submit" class="btn mr-2">Add</button>
                                            <!--<button type="button" class="btn btn-light">Back</button>-->
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- <div class="modal-footer">
                  <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">OK</a>
                </div> -->
                        </div>
                    </div>
                    <!-- datatable start -->
                    <div class="responsive-table mt-1">
                        <table id="item_table" class="users-list-datatable table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Specification</th>
                                    <th>Unit</th>
                                    <th>Pack</th>
                                    {{-- <th>Price</th> --}}
                                    <th>Qty</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quotationitems as $quotationitem)
                                    <tr>
                                        <td>{{ $quotationitem->name }}</td>
                                        <td>{{ $quotationitem->specification }}</td>
                                        <td>{{ $quotationitem->unit }}</td>
                                        <td>{{ $quotationitem->pack }}</td>
                                        {{-- <td>{{ $quotationitem -> price }}</td> --}}
                                        <td>{{ $quotationitem->qty }}</td>
                                        <td>{{ $quotationitem->remarks }}</td>
                                        <td><a
                                                href="{{ asset('quotation_item_delete/' . $quotationitem->id) }}">Cancel</a>
                                        </td>
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
                    <form action="/quotation_register" method="post" id="quotationRegisterForm">
                        {{ csrf_field() }}

                        <div class="media display-flex align-items-center">
                            <span class="card-title">Custom supplier information</span>
                        </div>
                        <input type="hidden" name="qn_id" value="{{ $data['quotation'] }}">
                        <input type="hidden" name="costcentre" id="costcentre" value="0">
                        <input id="remarks" name="remarks" class="materialize-textarea" />
                        <div class="action_wrap mt-2 text-right">
                            <button type="button" id="quotation_create_btn" class="btn mr-2">
                                Next</button>
                            <button type="button" onclick='window.location.href="{{ url()->previous() }}"'
                                class="btn btn-light">Back</button>
                        </div>
                    </form>
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
    <script src="{{ asset('js/scripts/advance-ui-modals.js') }}"></script>
    <script src="{{ asset('vendors/jquery-validation/jquery.validate.min.js') }}"></script>
@endsection

{{-- page script --}}
@section('page-script')
    <script src="{{ asset('js/scripts/page-users.js') }}"></script>
    <script src="{{ asset('js/scripts/page-sales.js') }}"></script>
    <script src="{{ asset('js/scripts/page-quotation.js') }}"></script>
    <script>
        @if (isset($data['cc_id']) && $data['cc_id'])
            $(function() {
                var selected_cc_id = "{{ $data['cc_id'] }}";
                $('#costcentre').val(selected_cc_id);
                $('#modal-costcentre').val(selected_cc_id);
            });
        @endif

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
    </script>

@endsection

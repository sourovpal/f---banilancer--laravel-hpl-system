{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Update Delivery Note Record')

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
<section class="sales-create-wrapper section">
  <div class="card">
    <div class="card-content">
      <div class="media display-flex align-items-center">
        <span class="card-title">Reference</span>
      </div>
      <div class="row">
        <div class="col s12 m6">
          <h6>Request Date: <span class="requestdate">2020-04-19</span></h6>
        </div>
        <div class="col s12 m6">
          <h6>Staff: <span class="staff">Banny Wan</span></h6>
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
          <h6>Department: <span class="requestdate">Customer & Planning</span></h6>
        </div>
        <div class="col s12 m4">
          <select>
            <option>Select Cost Centre</option>
            <option>first</option>
            <option>second</option>
          </select>
          <label>Cost Centre:</label>
        </div>
      </div>
    </div>
  </div>

  <div class="card add_item">
    <div class="card-content">
      <div class="media display-flex align-items-center">
        <a class="waves-effect waves-light btn modal-trigger" href="#add_item_modal"><i class="material-icons right">add_circle_outline</i>Add Item</a>

        <div id="add_item_modal" class="modal modal-fixed-footer">
          <div class="modal-content">
            <h4 class="mb-3">Add Item</h4>
            <div class="col s12 m4">
              <select class="category">
                <option>All Categories</option>
                <option>first</option>
                <option>second</option>
              </select>
            </div>
            <table id="modal_item" class="modal_item table">
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
                <tr>
                  <td class="text-center"><img class="item_image" src="{{asset('images/item/alcohol.png')}}" alt=""></td>
                  <td>WA-3814</td>
                  <td>HIROTA ALCOHOL SWAB</td>
                  <td>14 * 16 cm</td>
                  <td>100EA</td>
                </tr>
                <tr>
                  <td class="text-center"><img class="item_image" src="{{asset('images/item/alcohol.png')}}" alt=""></td>
                  <td>WA-3814</td>
                  <td>HIROTA ALCOHOL SWAB</td>
                  <td>14 * 16 cm</td>
                  <td>100EA</td>
                </tr>
                <tr>
                  <td class="text-center"><img class="item_image" src="{{asset('images/item/alcohol.png')}}" alt=""></td>
                  <td>WA-3814</td>
                  <td>HIROTA ALCOHOL SWAB</td>
                  <td>14 * 16 cm</td>
                  <td>100EA</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Agree</a>
          </div>
        </div>
      </div>
      <!-- datatable start -->
      <div class="responsive-table mt-1">
        <table id="item_table" class="item_table table">
          <thead>
            <tr>
              <th>Code</th>
              <th>Name</th>
              <th>Specification</th>
              <th>Unit</th>
              <th>Pack</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Min</th>
              <th>Location</th>
              <th>Qty</th>
              <th>Remarks</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>WA-3814</td>
              <td>HIROTA ALCOHOL SWAB</td>
              <td>14 * 16 cm</td>
              <td>Pack</td>
              <td>100EA</td>
              <td>100$</td>
              <td></td>
              <td></td>
              <td>London</td>
              <td>3</td>
              <td></td>
            </tr>
            <tr>
              <td>WA-3814</td>
              <td>HIROTA ALCOHOL SWAB</td>
              <td>14 * 16 cm</td>
              <td>Pack</td>
              <td>100EA</td>
              <td>100$</td>
              <td></td>
              <td></td>
              <td>London</td>
              <td>3</td>
              <td></td>
            </tr>
            <tr>
              <td>WA-3814</td>
              <td>HIROTA ALCOHOL SWAB</td>
              <td>14 * 16 cm</td>
              <td>Pack</td>
              <td>100EA</td>
              <td>100$</td>
              <td></td>
              <td></td>
              <td>London</td>
              <td>3</td>
              <td></td>
            </tr>
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
      <input id="textarea2" class="materialize-textarea" />
      <div class="row mt-2">
        <div class="col s12 m6">
          <h6>Sign Image: </h6>
          <form action="#">
            <div class="file-field input-field">
              <div class="btn">
                <span>File</span>
                <input type="file">
              </div>
              <div class="file-path-wrapper">
                <input class="file-path validate" type="text">
              </div>
            </div>
          </form>
        </div>
        <div class="col s12 m6" style="padding-top: 8px;">
          <h6>Sign Date: </h6>
          <input type="text" class="datepicker" id="signdate">
        </div>
      </div>
      <div class="action_wrap mt-2 text-right">
        <button type="submit" class="btn indigo mr-2">
          Create</button>
        <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
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
<script src="{{asset('js/scripts/page-users.js')}}"></script>
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
   }
</script>
@endsection
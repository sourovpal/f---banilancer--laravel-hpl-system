{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Update Item Record')

{{-- vendor styles --}}
@section('vendor-style')
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('vendors/select2/select2-materialize.css')}}">
@endsection

{{-- page style --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/page-users.css')}}">
@endsection

{{-- page content --}}
@section('content')
<!-- users edit start -->
<div class="section users-edit">
  <div class="card">
    <div class="card-content">
      <div class="row">
        <div class="col s12" id="account">
          <!-- users edit media object start -->
          <div class="media display-flex align-items-center mb-2">
            <h5 class="media-heading mt-0">Item Info</h5>
          </div>
          <!-- users edit media object ends -->
          <!-- users edit account form start -->
          <form id="itemUpdateForm" action="/item_update" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="row">
              <div class="col s12 m6">
                <div class="row">
                  <div class="col s12 input-field">
                    <select name="category" id="category">
                      <option value="0">Select Category</option>
                      @foreach ($categories as $category)
                        <option value="{{ $category -> id }}" {{ $category -> id == $item -> category_id ? 'selected' : '' }}>{{ $category -> name }}</option>
                      @endforeach
                    </select>
                    <label>Category</label>
                  </div>
                  <!-- <div class="col s12 input-field mt-2">
                    <input type="hidden" name="id" value="{{ $item -> id }}">
                    <input id="itemcode" name="itemcode" type="text" class="validate" value="{{ $item -> code }}" data-error=".errorTxt1">
                    <label for="itemcode">Item Code</label>
                    <small class="errorTxt1"></small>
                  </div> -->
                  <div class="col s12 input-field">
                    <input type="hidden" name="id" value="{{ $item -> id }}">
                    <input id="itemunit" name="itemunit" type="text" class="validate" value="{{ $item -> unit }}" data-error=".errorTxt3">
                    <label for="itemunit">Item Unit</label>
                    <small class="errorTxt3"></small>
                  </div>
                  <div class="col s12 input-field">
                    <input id="itemprice" name="itemprice" type="text" class="validate" value="{{ $item -> price }}" data-error=".errorTxt5">
                    <label for="itemprice">Item Price</label>
                    <small class="errorTxt5"></small>
                  </div>
				  <!--
                  <div class="col s12 input-field">
                    <input id="itemmin" name="itemmin" type="text" class="validate" value="{{ $item -> min }}" data-error=".errorTxt2">
                    <label for="itemmin">Item Min</label>
                    <small class="errorTxt2"></small>
                  </div>
				  -->
                  <div class="col s12 input-field">
                    <input id="itemgl" name="itemgl" type="text" class="validate" value="{{ $item -> gl}}" data-error=".errorTxt2">
                    <label for="itemmin">GL</label>
                    <small class="errorTxt2"></small>
                  </div>
                </div>
              </div>
              <div class="col s12 m6">
                <div class="row">
                  <div class="col s12 input-field" style="margin-top: 0px;">
                    <div class="file-field input-field">
                      <div class="btn">
                        <span>Item Image</span>
                        <input type="file" id="imageInput" name="itemimage">
                      </div>
                      <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" value="{{  $item -> image }}">
                        <img id="imagePreview" src="../upload/item/{{$item -> image}}" alt="Preview Image" style="{{ !$item -> image ? 'display: none;':'' }} width: 41%">
                      </div>
                    </div>
                  </div>
                  <div class="col s12 input-field" style="margin-top: 0px;">
                    <input id="itemname" name="itemname" type="text" class="validate" value="{{ $item -> name }}" data-error=".errorTxt4">
                    <label for="itemname">Item Name</label>
                    <small class="errorTxt4"></small>
                  </div>
                  <div class="col s12 input-field">
                    <input id="itempack" name="itempack" type="text" class="validate" value="{{ $item -> pack }}" data-error=".errorTxt6">
                    <label for="itempack">Item Pack</label>
                    <small class="errorTxt6"></small>
                  </div>
                  <div class="col s12 input-field">
                    <input id="itemlocation" name="itemlocation" type="text" class="validate" value="{{ $item -> location }}" data-error=".errorTxt8">
                    <label for="itemlocation">Item Location</label>
                    <small class="errorTxt8"></small>
                  </div>
                  <!-- <div class="col s12 input-field">
                    <input id="itemstack" name="itemstack" type="text" class="validate" value="{{ $item -> stock }}" data-error=".errorTxt7">
                    <label for="itemstack">Item Stock</label>
                    <small class="errorTxt7"></small>
                  </div> -->
                </div>
              </div>
              <div class="col s12">
                <label for="itemspecification">Item Specification</label>
                <small class="errorTxt9"></small>
                <input id="itemspecification" name="itemspecification" class="materialize-textarea validate" value="{{ $item -> specification }}" />
              </div>
              <div class="col s12 display-flex justify-content-end mt-3">
                <button type="submit" class="btn">
                  Update</button>
                <button type="button" onclick='window.location.href="{{ url()->previous() }}"' class="btn btn-light">Back</button>
              </div>
            </div>
          </form>
          <!-- users edit account form ends -->
        </div>
      </div>
      <!-- </div> -->
    </div>
  </div>
</div>
<!-- users edit ends -->
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/select2/select2.full.min.js')}}"></script>
<script src="{{asset('vendors/jquery-validation/jquery.validate.min.js')}}"></script>
@endsection

{{-- page scripts --}}
@section('page-script')
<script src="{{asset('js/scripts/page-users.js')}}"></script>
<script src="{{asset('js/scripts/page-item1.js')}}"></script>
<script>

    $(document).ready(function(){
        $('#imageInput').change(function(event) {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var dataURL = reader.result;
                $('#imagePreview').attr('src', dataURL);
                $('#imagePreview').show();
            };
            reader.readAsDataURL(input.files[0]);
        });
    });
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
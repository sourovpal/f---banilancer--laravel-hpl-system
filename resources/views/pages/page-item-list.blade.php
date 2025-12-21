{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Current Item List')

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

<style>
  .item-list-section {
      width: 100% !important;
  }
  body {
      background-color: #eceff1 !important;
  }
</style>
@endsection

{{-- page content --}}
@section('content')
<!-- users list start -->
<section class="users-list-wrapper section">
  <div class="users-list-table">
    <div class="card">
	 <a href="{{ route('export.items') }}" class="btn btn-primary">Export items table to Excel</a>
      <div class="card-content">
        <!-- <span class="card-title">Master Admin</span> -->
        <!-- datatable start -->
        <div class="responsive-table">
          <table id="users-list-datatable" class="table">
            <thead>
              <tr>
              <th>Item Image</th>
                <th>Item Code</th>
                <th>Item Name</th>
                <th>Specification</th>
                <th>Unit</th>
                <th>Pack</th>
                <th>Category</th>
                <th>Price</th>
				<th>Cost</th>
                <th>Stock</th>
                <th>Min</th>
                <!-- <th>Ordered</th> -->
                <!-- <th>PO</th> -->
                <th>GL</th>
                <th>Location</th>
                <!--<th>Image</th>-->
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($items as $item)
                <tr>
                <td>
                    @if($item -> image)
                        <img src="../upload/item/{{$item -> image}}" alt="" srcset=""
                              style="border: 1px solid #ddd;border-radius: 4px;padding: 5px;width: 100%">
                    @endif
                </td>
                  <td>{{ $item -> code }}</td>
                  <td>{{ $item -> name }}</td>
                  <td>{{ $item -> specification }}</td>
                  <td>{{ $item -> unit }}</td>
                  <td>{{ $item -> pack }}</td>
                  <td>{{ $item -> category }}</td>
                  <td>{{ $item -> price }}</td>
				  <td>{{ $item -> cost }}</td>
                  <td>{{ $item -> stock }}</td>
                  <td>{{ $item -> min }}</td>
                  <!-- <td>{{ $item -> ordered }}</td> -->
                  <!-- <td>
                    @if (isset($po_info[$item->id]))
                      {{ $po_info[$item->id]->sum('item_qty') }}
                    @else
                      0
                    @endif
                  </td> -->
<td>{{ $item -> gl}}</td>
                  <td>{{ $item -> location }}</td>
                  <!--<td class="text-center"><img class="item_image" src="{{ asset('storage/upload/item/' . $item -> image) }}" alt=""></td>-->
                  <td>{{ $item -> status ? 'available' : 'disable' }}</td>
                  <td><a href="{{asset('/update-item-record/' . $item -> id)}}">Edit</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
		  <br />
		  <a href="{{ route('export.items') }}" class="btn btn-primary">Export items table to Excel</a>
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
<script src="{{asset('vendors/data-tables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js')}}"></script>
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
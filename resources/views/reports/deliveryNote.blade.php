{{-- layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Delivery Note Report')

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
    <section class="users-list-wrapper section">
        <div class="users-list-table">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <form class="login-form" method="post" action="/report/deliveryNoteExport">
							@csrf
							<div class="col s12 m3">
								<h6>From Date </h6>
								<input id="from_date" name="from_date" style="height: 2.5rem!important;" type="date">
							</div>
							<div class="col s12 m3">
								<h6>To Date </h6>
								<input id="to_date" name="to_date" style="height: 2.5rem!important;" type="date">
							</div>
							<div class="col s12 m2">
								<!-- 如果不需要 'Type' 字段，可以移除 -->
							</div>
							<div class="col s12 m2">
								<h6 style="color: white"> &nbsp; </h6>
								<button type="submit" class="btn btn-light">Export Excel</button>
							</div>
						</form>
                    </div>
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
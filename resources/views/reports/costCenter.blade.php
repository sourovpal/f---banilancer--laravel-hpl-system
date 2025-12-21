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
    <section class="users-list-wrapper section" style=" z-index: 1000;">
        <div class="users-list-table">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <form class="login-form" method="post" action="/report/cost-center">
                            @csrf
                            <div class="col s12 m3 select-container">
                                <p class="mb-0 mt-3">Cost Center</p>
                                <select name="cc_id" style=" z-index: 1010;">
                                    @foreach($costCenter as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['code'] }}</option>
                                    @endforeach
                                </select>
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

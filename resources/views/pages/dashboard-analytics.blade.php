{{-- extend Layout --}}
@extends('layouts.contentLayoutMaster')

{{-- page title --}}
@section('title','Dashboard Analytics')

{{-- page styles --}}
@section('page-style')
<link rel="stylesheet" type="text/css" href="{{asset('css/pages/dashboard.css')}}">
@endsection

{{-- page content --}}
@section('content')
<div class="section">
   <div class="pt-3 pb-1" id="breadcrumbs-wrapper">
      <div class="container">
         <div class="row">
            <div class="col s12 m6 l6">
               <h5 class="breadcrumbs-title mt-0 mb-0"><span>Hello! {{ auth()->user()->username }}.</span></h5>
            </div>
         </div>
      </div>
   </div>

   {{-- <!-- card stats start -->
   <div id="card-stats" class="pt-0">
      <div class="row">
         <div class="col s12 m6 l3">
            <div class="card animate fadeLeft">
               <div class="card-content cyan white-text">
                  <p class="card-stats-title"><i class="material-icons">person_outline</i> Sales Order</p>
                  <h4 class="card-stats-number white-text">566</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_up</i> 15%
                     <span class="cyan text text-lighten-5">from yesterday</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="col s12 m6 l3">
            <div class="card animate fadeLeft">
               <div class="card-content red accent-2 white-text">
                  <p class="card-stats-title"><i class="material-icons">attach_money</i>Delivery Note</p>
                  <h4 class="card-stats-number white-text">$8990.63</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_up</i> 70% <span class="red-text text-lighten-5">last
                        month</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="col s12 m6 l3">
            <div class="card animate fadeRight">
               <div class="card-content orange lighten-1 white-text">
                  <p class="card-stats-title"><i class="material-icons">trending_up</i> Good Receive</p>
                  <h4 class="card-stats-number white-text">$806.52</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_up</i> 80%
                     <span class="orange-text text-lighten-5">from yesterday</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="col s12 m6 l3">
            <div class="card animate fadeRight">
               <div class="card-content green lighten-1 white-text">
                  <p class="card-stats-title"><i class="material-icons">content_copy</i> Current Quotation</p>
                  <h4 class="card-stats-number white-text">1806</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_down</i> 3%
                     <span class="green-text text-lighten-5">from last month</span>
                  </p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--card stats end-->

   <div class="pt-3 pb-1" id="breadcrumbs-wrapper">
      <div class="container">
         <div class="row">
            <div class="col s12 m6 l6">
               <h5 class="breadcrumbs-title mt-0 mb-0"><span>Internal User</span></h5>
            </div>
         </div>
      </div>
   </div>

   <!-- card stats start -->
   <div id="card-stats" class="pt-0">
      <div class="row">
         <div class="col s12 m6 l3">
            <div class="card animate fadeLeft">
               <div class="card-content cyan white-text">
                  <p class="card-stats-title"><i class="material-icons">person_outline</i> Sales Order</p>
                  <h4 class="card-stats-number white-text">566</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_up</i> 15%
                     <span class="cyan text text-lighten-5">from yesterday</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="col s12 m6 l3">
            <div class="card animate fadeLeft">
               <div class="card-content red accent-2 white-text">
                  <p class="card-stats-title"><i class="material-icons">attach_money</i>Delivery Note</p>
                  <h4 class="card-stats-number white-text">$8990.63</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_up</i> 70% <span class="red-text text-lighten-5">last
                        month</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="col s12 m6 l3">
            <div class="card animate fadeRight">
               <div class="card-content orange lighten-1 white-text">
                  <p class="card-stats-title"><i class="material-icons">trending_up</i> Good Receive</p>
                  <h4 class="card-stats-number white-text">$806.52</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_up</i> 80%
                     <span class="orange-text text-lighten-5">from yesterday</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="col s12 m6 l3">
            <div class="card animate fadeRight">
               <div class="card-content green lighten-1 white-text">
                  <p class="card-stats-title"><i class="material-icons">content_copy</i> Current Quotation</p>
                  <h4 class="card-stats-number white-text">1806</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_down</i> 3%
                     <span class="green-text text-lighten-5">from last month</span>
                  </p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--card stats end-->

   <div class="pt-3 pb-1" id="breadcrumbs-wrapper">
      <div class="container">
         <div class="row">
            <div class="col s12 m6 l6">
               <h5 class="breadcrumbs-title mt-0 mb-0"><span>External Admin</span></h5>
            </div>
         </div>
      </div>
   </div>

   <!-- card stats start -->
   <div id="card-stats" class="pt-0">
      <div class="row">
         <div class="col s12 m6 l3">
            <div class="card animate fadeLeft">
               <div class="card-content cyan white-text">
                  <p class="card-stats-title"><i class="material-icons">person_outline</i> Sales Order</p>
                  <h4 class="card-stats-number white-text">566</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_up</i> 15%
                     <span class="cyan text text-lighten-5">from yesterday</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="col s12 m6 l3">
            <div class="card animate fadeRight">
               <div class="card-content green lighten-1 white-text">
                  <p class="card-stats-title"><i class="material-icons">content_copy</i> Current Quotation</p>
                  <h4 class="card-stats-number white-text">1806</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_down</i> 3%
                     <span class="green-text text-lighten-5">from last month</span>
                  </p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--card stats end-->

   <div class="pt-3 pb-1" id="breadcrumbs-wrapper">
      <div class="container">
         <div class="row">
            <div class="col s12 m6 l6">
               <h5 class="breadcrumbs-title mt-0 mb-0"><span>External Approver</span></h5>
            </div>
         </div>
      </div>
   </div>

   <!-- card stats start -->
   <div id="card-stats" class="pt-0">
      <div class="row">
         <div class="col s12 m6 l3">
            <div class="card animate fadeLeft">
               <div class="card-content cyan white-text">
                  <p class="card-stats-title"><i class="material-icons">person_outline</i> Sales Order</p>
                  <h4 class="card-stats-number white-text">566</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_up</i> 15%
                     <span class="cyan text text-lighten-5">from yesterday</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="col s12 m6 l3">
            <div class="card animate fadeRight">
               <div class="card-content green lighten-1 white-text">
                  <p class="card-stats-title"><i class="material-icons">content_copy</i> Current Quotation</p>
                  <h4 class="card-stats-number white-text">1806</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_down</i> 3%
                     <span class="green-text text-lighten-5">from last month</span>
                  </p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--card stats end-->

   <div class="pt-3 pb-1" id="breadcrumbs-wrapper">
      <div class="container">
         <div class="row">
            <div class="col s12 m6 l6">
               <h5 class="breadcrumbs-title mt-0 mb-0"><span>External User</span></h5>
            </div>
         </div>
      </div>
   </div>

   <!-- card stats start -->
   <div id="card-stats" class="pt-0">
      <div class="row">
         <div class="col s12 m6 l3">
            <div class="card animate fadeLeft">
               <div class="card-content cyan white-text">
                  <p class="card-stats-title"><i class="material-icons">person_outline</i> Sales Order</p>
                  <h4 class="card-stats-number white-text">566</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_up</i> 15%
                     <span class="cyan text text-lighten-5">from yesterday</span>
                  </p>
               </div>
            </div>
         </div>
         <div class="col s12 m6 l3">
            <div class="card animate fadeRight">
               <div class="card-content green lighten-1 white-text">
                  <p class="card-stats-title"><i class="material-icons">content_copy</i> Current Quotation</p>
                  <h4 class="card-stats-number white-text">1806</h4>
                  <p class="card-stats-compare">
                     <i class="material-icons">keyboard_arrow_down</i> 3%
                     <span class="green-text text-lighten-5">from last month</span>
                  </p>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--card stats end--> --}}

</div>
@endsection

{{-- vendor scripts --}}
@section('vendor-script')
<script src="{{asset('vendors/sparkline/jquery.sparkline.min.js')}}"></script>
<!-- <script src="{{asset('vendors/chartjs/chart.min.js')}}"></script> -->
@endsection

{{-- page scripts --}}
@section('page-script')
<script>
   setTimeout(function () {
      M.toast({ html: "Hi! {{ Auth::user() -> username }}, Welcome to the system." });
   }, 2000);
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
</script>
<script src="{{asset('js/scripts/dashboard-analytics.js')}}"></script>

@endsection
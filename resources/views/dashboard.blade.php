@extends('layouts.master')
@section('title')
الرئيسيه
@stop
@section('css')
<!--  Owl-carousel css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />
<!-- Maps css -->
<link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="left-content">
						<div>
							<h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">أهلا وسهلا {{Auth::user()->name}}</h2>
						</div>
					</div>
					<div class="main-dashboard-header-right">
						<div>
							<label class="tx-13">تقييمات المستخدمين</label>
							<div class="main-star">
								<i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star active"></i> <i class="typcn typcn-star"></i> <span>(14,873)</span>
							</div>
						</div>
					</div>
				</div>
				<!-- /breadcrumb -->
@endsection
@section('content')
				<!-- row -->
				<div class="row row-sm">
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-primary-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h6 class="mb-3 tx-12 text-white">إجمالي الفواتير</h6>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h4 class="tx-20 font-weight-bold mb-1 text-white">
												{{number_format(\App\Models\Invoice::sum('total'),2)}} ألف جنيه</h4>
											<p class="mb-0 tx-12 text-white op-7">{{\App\Models\Invoice::count()}} فواتير</p>
										</div>
										<span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-up text-white"></i>
											<span class="text-white op-7">100%</span>
										</span>
									</div>
								</div>
							</div>
							<span id="compositeline" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
						</div>
					</div>
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-danger-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h6 class="mb-3 tx-12 text-white">الفواتير الغير مدفوعه</h6>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h4 class="tx-20 font-weight-bold mb-1 text-white">{{number_format(\App\Models\Invoice::where('value_status' , 2)->sum('total'),2)}} ألف جنيه</h4>
											<p class="mb-0 tx-12 text-white op-7">{{\App\Models\Invoice::where('value_status' , 2)->count()}} فواتير</p>
										</div>
										<span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-down text-white"></i>
											<span class="text-white op-7">
												{{round(\App\Models\Invoice::where('value_status' , 2)->count()/\App\Models\Invoice::count()*100,2)}}%</span>
										</span>
									</div>
								</div>
							</div>
							<span id="compositeline2" class="pt-1">3,2,4,6,12,14,8,7,14,16,12,7,8,4,3,2,2,5,6,7</span>
						</div>
					</div>
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-success-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h6 class="mb-3 tx-12 text-white">الفواتير المدفوعه</h6>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h4 class="tx-20 font-weight-bold mb-1 text-white">{{number_format(\App\Models\Invoice::where('value_status' , 1)->sum('total'),2)}} ألف جنيه</h4>
											<p class="mb-0 tx-12 text-white op-7">{{\App\Models\Invoice::where('value_status' , 1)->count()}} فواتير</p>
										</div>
										<span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-up text-white"></i>
											<span class="text-white op-7">{{round(\App\Models\Invoice::where('value_status' , 1)->count()/\App\Models\Invoice::count()*100,2)}}%</span>
										</span>
									</div>
								</div>
							</div>
							<span id="compositeline3" class="pt-1">5,10,5,20,22,12,15,18,20,15,8,12,22,5,10,12,22,15,16,10</span>
						</div>
					</div>
					<div class="col-xl-3 col-lg-6 col-md-6 col-xm-12">
						<div class="card overflow-hidden sales-card bg-warning-gradient">
							<div class="pl-3 pt-3 pr-3 pb-2 pt-0">
								<div class="">
									<h6 class="mb-3 tx-12 text-white">الفواتير المدفوعه جزئيا</h6>
								</div>
								<div class="pb-0 mt-0">
									<div class="d-flex">
										<div class="">
											<h4 class="tx-20 font-weight-bold mb-1 text-white">{{number_format(\App\Models\Invoice::where('value_status' , 3)->sum('total'),2)}} ألف جنيه</h4>
											<p class="mb-0 tx-12 text-white op-7">{{\App\Models\Invoice::where('value_status' , 3)->count()}} فواتير</p>
										</div>
										<span class="float-right my-auto mr-auto">
											<i class="fas fa-arrow-circle-down text-white"></i>
											<span class="text-white op-7">{{round(\App\Models\Invoice::where('value_status' , 3)->count()/\App\Models\Invoice::count()*100,2)}}%</span>
										</span>
									</div>
								</div>
							</div>
							<span id="compositeline4" class="pt-1">5,9,5,6,4,12,18,14,10,15,12,5,8,5,12,5,12,10,16,12</span>
						</div>
					</div>
				</div>
				<!-- row closed -->

				<!-- row opened -->
				<div class="row row-sm">
					<div class="col-md-12 col-lg-12 col-xl-7">
						<div class="card">
							<div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
								<div class="d-flex justify-content-between">
									<h4 class="card-title mb-0">بيان احصائي لإجمالي الفواتير</h4>
								</div>
							</div>
							<div class="card-body" style="width:75">
								<canvas id="barChartTest" width="400" height="200"></canvas>
				<script>
					document.addEventListener('DOMContentLoaded', function () {
						const ctx = document.getElementById('barChartTest').getContext('2d');
						const myChart = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: ['الفواتير الغير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'],
								datasets: [
									{
										label: 'الفواتير الغير المدفوعة',
										data: [{{ $nspainvoices2 }}],
										backgroundColor: '#ec5858',
									},
									{
										label: 'الفواتير المدفوعة',
										data: [{{ $nspainvoices1 }}],
										backgroundColor: '#81b214',
									},
									{
										label: 'الفواتير المدفوعة جزئيا',
										data: [{{ $nspainvoices3 }}],
										backgroundColor: '#ff9642',
									},
								],
							},
							options: {
								responsive: true,
							},
						});
					});
				</script>
							</div>
						</div>
					</div>
					<div class="col-lg-12 col-xl-5">
						<style>
							#pieChartTest {
								background-color: #ffffff; /* الخلفية البيضاء */
							}
						</style>
						<canvas id="pieChartTest" width="600" height="400"></canvas>
						<script>
							document.addEventListener('DOMContentLoaded', function () {
								const ctx = document.getElementById('pieChartTest').getContext('2d');
								const myChart = new Chart(ctx, {
									type: 'pie',
									data: {
										labels: ['الفواتير الغير المدفوعة', 'الفواتير المدفوعة', 'الفواتير المدفوعة جزئيا'],
										datasets: [
											{
												label: 'حالة الفواتير',
												data: [{{ $nspainvoices2 }}, {{ $nspainvoices1 }}, {{ $nspainvoices3 }}],
												backgroundColor: ['#ec5858', '#81b214', '#ff9642'],
											},
										],
									},
									options: {
										responsive: true,
									},
								});
							});
						</script>
					</div>
					
				</div>
				<!-- row closed -->
			</div>
		</div>
		<!-- Container closed -->
@endsection
@section('js')
<!--Internal  Chart.bundle js -->
<script src="{{URL::asset('assets/plugins/chart.js/Chart.bundle.min.js')}}"></script>
<!-- Moment js -->
<script src="{{URL::asset('assets/plugins/raphael/raphael.min.js')}}"></script>
<!--Internal  Flot js-->
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.pie.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.resize.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jquery.flot/jquery.flot.categories.js')}}"></script>
<script src="{{URL::asset('assets/js/dashboard.sampledata.js')}}"></script>
<script src="{{URL::asset('assets/js/chart.flot.sampledata.js')}}"></script>
<!--Internal Apexchart js-->
<script src="{{URL::asset('assets/js/apexcharts.js')}}"></script>
<!-- Internal Map -->
<script src="{{URL::asset('assets/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<script src="{{URL::asset('assets/js/modal-popup.js')}}"></script>
<!--Internal  index js -->
<script src="{{URL::asset('assets/js/index.js')}}"></script>
<script src="{{URL::asset('assets/js/jquery.vmap.sampledata.js')}}"></script>	
@endsection
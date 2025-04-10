@extends('layouts.app')
@section('title')
@if (auth()->user()->type->value == 'admin')
{{ __('messages_303.doctors') }}
@else
{{ __('messages_301.My account') }}
@endif
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
	@if (auth()->user()->type->value == 'admin')
	@include('inc.breadcrumb', [
	'breadcrumb_items' => [
	__('messages.Home') => route('home'),
	__('messages_303.doctors') => route('admin.doctors.index'),
	$doctor->name => null,
	__('messages_301.Sales') => 'active',
	],
	])
	@else
	@include('inc.breadcrumb', [
	'breadcrumb_items' => [
	__('messages.Home') => route('home'),
	auth()->user()->name => null,
	__('messages_301.My account') => 'active',
	],
	])
	@endif



	<!-- Basic Bootstrap Table -->
	<div class="card">
		<div class="p-3  justify-content-between">
			<h5 class="card-header">{{ __('messages_301.Sales') }}</h5>

			<div>
				<form>
					<div class="row g-3">

						<div class="col-md-3 col-12">
							<div class="card mb-4">
								<ul class="list-group list-group-flush">
									<li class="list-group-item text-center h5">{{ __('messages.Doctors points') }}
									</li>
									<li class="list-group-item text-center">
										{{ $data->sum('doctor_commission_value') }} %
									</li>
								</ul>
							</div>
						</div>

						<div class=" col-3 mt-4">
							<label class="mb-1">{{ __('messages_301.Years') }}</label>
							<select name="year" class="form-control select2 select_year">

								@foreach ($years as $year)
								<option @if ($search_year==$year) selected @endif value="{{ $year }}">
									{{ $year }}</option>
								@endforeach
							</select>
						</div>

						<div class=" col-3 mt-4">
							<label class="mb-1">{{ __('messages_301.Months') }}</label>
							<select name="month" class="form-control select2 select_month">
								@foreach ($months as $month)
								<option @if ($search_month==$month) selected @endif value="{{ $month }}">
									{{ $month }}</option>
								@endforeach
							</select>
						</div>
						<div class=" col-2 mt-4 p-4">

							<button type="submit" class="btn btn-outline-primary waves-effect" type="button" id="button-addon1">{{
								__('messages.Search') }}<i class="fa-solid fa-magnifying-glass"></i></button>
						</div>
					</div>
				</form>
			</div>
			<hr class="my-3" />
		</div>
		<div class="d-flex justify-content-end p-3">
			<div class="btn-group" role="group" aria-label="Basic example">
				<a href="{{ route('admin.doctors.export' , $doctor->id) }}?{{ request()->getQueryString() }}"
					class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{
						__("messages.EXPORT XLS") }}</span><i class="fa-solid fa-file-export"></i></a>
			</div>
		</div>
		<div class="table-responsive text-nowrap">

			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>{{ __('messages.Reference') }}</th>
						<th>{{ __('messages.Date created') }}</th>
						<th>{{ __('messages.Total') }}</th>
						<th>{{ __('messages.Doctors commission ( % )') }}</th>
						<th>{{ __('messages.Points') }}</th>
					</tr>
				</thead>
				<tbody class="table-border-bottom-0">
					@php
					$i = 1;
					$skipCount = $data->perPage() * $data->currentPage() - $data->perPage();
					@endphp
					@include('inc.is_empty_data', [
					'var_check_empty' => $data,
					'var_check_empty_rows' => 7,
					])

					@foreach ($data as $item)
					<tr>
						<th>{{ $skipCount + $i }}</th>
						@php $i++ @endphp


						<td><a href="{{ route('admin.invoices.show' , $item->id) }}">#{{ $item->invoice_num }}</a></td>
						<td>{{ Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}</td>
						<td>{{ $item->total }}</td>
						<td>{{ $item->doctor_commission }}</td>
						<td>{{ $item->doctor_commission_value }}</td>





					</tr>
					@endforeach
				</tbody>
			</table>
			<div class="m-3">
				{{ $data->appends(Request::except(['_token']))->links() }}
			</div>

		</div>
	</div>
	<!--/ Basic Bootstrap Table -->

</div>
@endsection
@use('Carbon\Carbon')

@extends('layouts.app')
@section('title')
@if (auth()->user()->type->value == 'admin')
{{ __('messages.Invoices') }}
@else
{{ __('messages_301.Recipes') }}
@endif
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
	@if (auth()->user()->type->value == 'admin')
	@include('inc.breadcrumb', [
	'breadcrumb_items' => [
	__('messages.Home') => route('home'),
	__('messages.Settings') => '/',
	__('messages.Invoices') => 'active',
	],
	])
	@else
	@include('inc.breadcrumb', [
	'breadcrumb_items' => [
	__('messages.Home') => route('home'),
	__('messages_301.Recipes') => 'active',
	],
	])
	@endif

	<!-- Basic Bootstrap Table -->
	<div class="card">

		<div>
			<div class="row">
				<div class="col-12">
					<div class=" mb-4">
						<div class="card-body">
							<div class="d-flex">


								@if ($review_sel)
								<span class="badge bg-label-success mx-2"><i data-input_name="select_review_id"
										class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages.Reviewer') }}
									: {{ $review_sel }}</span>
								@endif

								@if (request('invoice_num'))
								<span class="badge bg-label-success mx-2"><i data-input_name="select_invoice_num"
										class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages.Reference') }}
									: {{ request('invoice_num') }}</span>
								@endif

								@if (request('status'))
								<span class="badge bg-label-success mx-2"><i data-input_name="select_status"
										class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages.Status') }}
									: {{ __('messages.' . request('status')) }}</span>
								@endif
								@if (request('from_date'))
								<span class="badge bg-label-success mx-2"><i data-input_name="select_from_date"
										class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages.From date') }}
									: {{ request('from_date') }}</span>
								@endif

								@if (request('to_date'))
								<span class="badge bg-label-success mx-2"><i data-input_name="select_to_date"
										class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages.To date') }}
									: {{ request('to_date') }}</span>
								@endif


								@if (request('client'))
								<span class="badge bg-label-success mx-2"><i data-input_name="select_client"
										class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages.Client') }}
									: {{ request('client') }}</span>
								@endif

							</div>
							<p class="demo-inline-spacing">
							<div class="p-3 d-flex justify-content-between">
								<a class="btn btn-primary me-1" data-bs-toggle="collapse" href="#collapseExample" role="button"
									aria-expanded="false" aria-controls="collapseExample">
									<i class="ti ti-adjustments-horizontal"></i>
								</a>

								<div class="btn-group" role="group" aria-label="Basic example">
									<a href="{{ route('admin.invoices.export') }}"
										class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{
											__("messages.EXPORT XLS") }}</span><i class="fa-solid fa-file-export"></i></a>
									@can('create_invoice')
									<a href="{{ route('admin.invoices.create') }}"
										class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{
											__("messages.ADD NEW ") }}</span><i class="fa-solid fa-circle-plus "></i></a>
									@endcan
								</div>


							</div>

							</p>
							<div class="collapse border border-dark rounded p-4" id="collapseExample">

								<form id="form_filter">
									<div class="row">
										<div class=" col-4 mt-4">
											<label class="mb-1">{{ __('messages.Customer name, phone number') }}</label>
											<input name="client" placeholder="" value="{{ request('client') }}"
												class="form-control  select_client" name="client">
										</div>

										<div class=" col-4 mt-4">
											<label class="mb-1">{{ __('messages.Reviewer') }}</label>
											<select name="review_id" class="form-control select2 select_review_id">
												<option value="">--------</option>
												@foreach ($reviewers as $item_reviewer)
												<option @selected(request('review_id')==$item_reviewer->id)
													value="{{ $item_reviewer->id }}">{{ $item_reviewer->name }} -
													{{ $item_reviewer->email }}</option>
												@endforeach
											</select>
										</div>


										<div class=" col-6 mt-4">
											<label class="mb-1">{{ __('messages.Reference') }}</label>
											<input name="invoice_num" value="{{ request('invoice_num') }}"
												class="form-control  select_invoice_num" name="invoice_num">
										</div>

										<div class=" col-6 mt-4">
											<label class="mb-1">{{ __('messages.Status') }}</label>
											<select name="status" class="form-control select2 select_status">
												<option value="">--------</option>
												@foreach (\App\Enums\OrderStatusEnum::values() as $item_status)
												<option @selected(request('status')==$item_status) value="{{ $item_status }}">
													{{ __('messages.' . $item_status) }}</option>
												@endforeach
											</select>
										</div>
										<div class=" col-6 mt-4">
											<label class="mb-1">{{ __('messages.From date') }}</label>
											<input type="date" value="{{ request('from_date') }}" class="form-control  select_from_date"
												name="from_date">
										</div>
										<div class=" col-6 mt-4">
											<label class="mb-1">{{ __('messages.To date') }}</label>
											<input type="date" value="{{ request('to_date') }}" class="form-control  select_to_date"
												name="to_date">
										</div>

									</div>
									<div class="row mt-4">
										<div class="col-12">
											<button type="submit" class="btn btn-primary me-1 w-100">
												{{ __('messages.Filter') }}<i class="ti ti-adjustments-horizontal"></i>
											</button>
										</div>
									</div>

								</form>


							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		<div class="table-responsive text-nowrap">
			<table class="table  " style="margin-bottom: 120px">
				<thead>
					<tr>
						<th>#</th>
						<th>{{ __('messages.Reference') }}</th>
						<th>{{ __('messages.Client') }}</th>
						<th>{{ __('messages.Phone Number') }}</th>
						<th>{{ __('messages.Comission') }}</th>
						<th>{{ __('messages.Doctor') }}</th>
						<th>{{ __('messages.Reviewer') }}</th>
						<th>{{ __('messages.Status') }}</th>
						<th>Payment Type</th>
						<th>{{ __('messages.Total') }}</th>
						<th>{{ __('messages.Date created') }}</th>
						<th>{{ __('messages.Actions') }}</th>
					</tr>
				</thead>
				<tbody class="table-border-bottom-0">
					@php
					$i = 1;
					$skipCount = $data->perPage() * $data->currentPage() - $data->perPage();
					@endphp
					@include('inc.is_empty_data', [
					'var_check_empty' => $data,
					'var_check_empty_rows' => 10,
					])

					@foreach ($data as $item)
					<tr>
						<th>{{ $skipCount + $i }}</th>
						@php $i++ @endphp


						<td>#{{ $item->invoice_num }}</td>
						<td class="text-capitalize">{{ $item->client_name }}</td>
						<td class="text-capitalize">{{ $item->client_mobile }}</td>
						<td class="text-capitalize">{{ $item->doctor_commission }}</td>
						<td class="text-capitalize">{{ $item->doctor->name }}</td>
						<td class="text-capitalize">{{ $item->reviewer ? $item->reviewer->name : '----' }}</td>

						<td style="min-width: 200px">
							@if (auth()->user()->type == App\Enums\UserRoleEnum::Doctor)
							<span class="badge bg-label-{{ App\Http\Helpers\HelperApp::get_color_status($item->status) }} me-1">{{
								__('messages.' . $item->status) }}</span>
							@else
							@can('review_invoice')
							<div class="col-12">
								@include('admin.invoices.status_selected', ['invoice_item_status' => $item->status ,
								'invoice_item_id' => $item->id])
							</div>
							@else
							<span class="badge bg-label-{{ App\Http\Helpers\HelperApp::get_color_status($item->status) }} me-1">{{
								__('messages.' . $item->status) }}</span>
							@endcan

							@endif

						</td>

						<td style="min-width: 200px">
							@if (auth()->user()->type == App\Enums\UserRoleEnum::Doctor)
							<span class="badge bg-label-{{ App\Http\Helpers\HelperApp::get_color_status($item->status) }} me-1">
								{{$item->payment_type ? $item->payment_type : 'Unpaid' }}
							</span>
							@else
							@can('review_invoice')
							<div class="col-12">
								@include('admin.invoices.payment_selected',
								[
								'invoice_item_payment_type' => $item->payment_type,
								'invoice_item_id'=>$item->id
								])
							</div>
							@else
							<span class="badge bg-label-{{ App\Http\Helpers\HelperApp::get_color_status($item->status) }} me-1">{{
								__('messages.' . $item->status) }}</span>
							@endcan


							@endif

						</td>
						<td>{{ $item->total }}</td>
						<td>{{ Carbon::parse($item->created_at_format)->format('d/m/Y') }}</td>

						<td>
							<div class="dropdown">
								<button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
									<i class="ti ti-dots-vertical"></i>
								</button>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="{{ route('admin.invoices.pdf', $item->id) }}"><i
											class="ti ti-file "></i>PDF</a>
									@can('show_invoice')
									<a class="dropdown-item" href="{{ route('admin.invoices.show', $item->id) }}"><i
											class="ti ti-eye "></i>{{ __('messages.Show') }}</a>

									@endcan

									@can('edit_invoice')
									<a class="dropdown-item" href="{{ route('admin.invoices.edit', $item->id) }}"><i
											class="ti ti-pencil "></i>{{ __('messages.Edit') }}</a>
									@endcan


									@if ($item->status != 'cancel' && $item->status != 'send' && auth()->user()->type->value == 'admin')
									@can('send_invoice')
									<a data-url="{{ route('admin.invoices.send_status', $item->id) }}"
										data-text_btn_confirm="{{ __('messages.Confirm') }}"
										data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
										data-message="{{ __('messages_301.Are you sure to send the invoice ?') }}"
										class="dropdown-item btn-action" href="javascript:void(0);"><i class="fa-solid fa-circle-check"></i>
										{{ __('messages_301.Send') }}</a>
									@endcan

									@can('cancel_invoice')
									<a data-url="{{ route('admin.invoices.cancel_status', $item->id) }}"
										data-text_btn_confirm="{{ __('messages.Confirm') }}"
										data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
										data-message="{{ __('messages_301.Are you sure to cancel the invoice ?') }}"
										class="dropdown-item btn-action" href="javascript:void(0);"><i class="fa-solid fa-circle-xmark"></i>
										{{ __('messages_301.Cancel') }}</a>
									@endcan
									@endif

									@if (auth()->user()->type->value == 'admin')
									@can('review_invoice')
									@if ($item->reviewer && $item->status == \App\Enums\OrderStatusEnum::Draft->value)
									<a data-url="{{ route('admin.invoices.review', $item->id) }}" href="javascript:void(0);"
										data-text_btn_confirm="{{ __('messages.Confirm') }}"
										data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
										data-message="{{ __('messages_301.Reviewed') }}" class="dropdown-item btn-action"><i
											class="ti ti-eye-check"></i>
										{{ __('messages_301.Reviewed') }}</a>
									@endif
									@endcan
									@endif


								</div>
							</div>
						</td>

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

@section('script')
<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@include('admin.invoices.scripts.change_status')
@include('admin.invoices.scripts.change_payment')
<script>
	$(function() {
		$('.select2').select2();
		$(".btn_remove_filter").click(function() {
				var input_name = $(this).data('input_name')
				$(`.${input_name}`).html(null)
				$(`.${input_name}`).val(null)
				$('#form_filter').submit()
		})
	})
</script>
@endsection
@extends('layouts.app')
@section('title')
    {{ __('messages_301.Coupons') }}
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages_301.Coupons') => 'active',
            ],
        ])



        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="p-3 d-flex justify-content-between">
                <div class="mx-2">
                    <form>
                        <div class="input-group">
                            <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control"
                                placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                            <button type="submit" class="btn btn-outline-primary waves-effect" type="button"
                                id="button-addon1"><span class="d-none d-sm-block">{{ __("messages.Search") }}</span><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ route('admin.coupons.export') }}"
                            class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{ __('messages.EXPORT XLS') }}</span> <i
                                class="fa-solid fa-file-export"></i></a>
                        @can('create_coupon')
                            <a href="{{ route('admin.coupons.create') }}"
                                class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{ __("messages.ADD NEW ") }}</span><i class="fa-solid fa-circle-plus "></i></a>
                        @endcan
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages_301.From date') }}</th>
                            <th>{{ __('messages_301.To date') }}</th>
                            <th>{{ __('messages_301.Count use') }}</th>
                            <th>{{ __('messages_301.Percentage') }}</th>
                            <th>{{ __('messages_301.Code') }}</th>
                            <th data-priority="1">{{ __('messages.Status') }}</th>
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
                            'var_check_empty_rows' => 8,
                        ])

                        @foreach ($data as $item)
                            <tr>
                                <th>{{ $skipCount + $i }}</th>
                                @php $i++  @endphp


                                <td>{{ $item->from_date }}</td>
                                <td>{{ $item->to_date }}</td>
                                <td>{{ $item->count_use }}</td>
                                <td>{{ $item->percentage }} %</td>
                                <td>{{ $item->code }}</td>
                                <td>
                                    @if ($item->is_active)
                                        <span class="badge bg-label-primary me-1">{{ __('messages.Active') }}<i
                                                style="min-width: 30px !important"
                                                class="fa-solid fa-circle-check"></i></span>
                                    @else
                                        <span class="badge bg-label-danger me-1">{{ __('messages_301.Inactive') }}<i
                                                class="fa-solid fa-ban"></i></span>
                                    @endif
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @can('edit_coupon')
                                                <a class="dropdown-item" href="{{ route('admin.coupons.edit', $item) }}"><i
                                                        class="ti ti-pencil "></i>{{ __('messages.Edit') }}</a>
                                            @endcan

                                            @can('active_coupon')
                                                @if ($item->is_active)
                                                    <a data-url="{{ route('admin.coupons.inactive', $item->id) }}"
                                                        data-text_btn_confirm="{{ __('messages.Confirm') }}"
                                                        data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
                                                        data-message="{{ __('messages_301.Are you sure the coupon is blocked ?') }}"
                                                        class="dropdown-item btn-action" href="javascript:void(0);"><i
                                                            class="fa-solid fa-ban"></i>
                                                        {{ __('messages_301.Coupon ban') }}</a>
                                                @else
                                                    <a data-url="{{ route('admin.coupons.active', $item->id) }}"
                                                        data-text_btn_confirm="{{ __('messages.Confirm') }}"
                                                        data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
                                                        data-message="{{ __('messages_301.Are you sure to activate the coupon ?') }}"
                                                        class="dropdown-item btn-action" href="javascript:void(0);"><i
                                                            class="fa-solid fa-circle-check"></i>
                                                        {{ __('messages_301.Activate the coupon') }}</a>
                                                @endif
                                            @endcan

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

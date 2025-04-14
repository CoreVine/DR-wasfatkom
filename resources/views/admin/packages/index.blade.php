@extends('layouts.app')
@section('title')
    {{ __('messages_303.packages') }}
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages_303.packages') => 'active',
            ],
        ])


        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="p-3 d-flex justify-content-between">
                <div>
                    <form>
                        <div class="input-group">
                            <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control"
                                placeholder="{{ __('messages_303.name') }}" aria-label="Example text with button addon"
                                aria-describedby="button-addon1">
                            <button type="submit" class="btn btn-outline-primary waves-effect" type="button"
                                id="button-addon1"><span class="d-none d-sm-block">{{ __("messages.Search") }}</span><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        {{-- <a href="{{ route('admin.products.export') }}" class="btn btn-outline-primary waves-effect text-primary">{{ __("messages.EXPORT XLS") }}<i class="fa-solid fa-file-export"></i></a> --}}
                        @can('create_package')
                            <a href="{{ route('admin.packages.create') }}"
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
                            <th>{{ __('messages_303.name') }}</th>
                            <th>{{ __('messages_301.Doctor name') }}</th>
                            <th>{{ __('messages.Price') }}</th>
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
                            'var_check_empty_rows' => 5,
                        ])

                        @foreach ($data as $item)
                            <tr>
                                <th>{{ $skipCount + $i }}</th>
                                @php $i++  @endphp
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->doctor->name }}</td>
                                <td>{{ $item->price }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @can('edit_package')
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.packages.edit', $item->id) }}"><i
                                                        class="ti ti-pencil "></i>{{ __('messages.Edit') }}</a>
                                            @endcan

                                            @can('delete_package')
                                                <a data-url="{{ route('admin.packages.destroy', $item->id) }}"
                                                    data-text_btn_confirm="{{ __('messages.Confirm') }}"
                                                    data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="delete"
                                                    data-message="{{ __('messages.Are you sure you want to delete? You cannot undo it') }}"
                                                    class="dropdown-item btn-action" href="javascript:void(0);"><i
                                                        class="ti ti-trash "></i> {{ __('messages.Delete') }}</a>
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

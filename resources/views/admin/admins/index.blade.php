@extends('layouts.app')
@section('title')
{{ __('messages.Technical support') }}
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages.Technical support') => 'active',
            ],
        ])


        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="p-3 d-flex justify-content-between">
                <div class="mx-2">
                    <form>
                        <div class="input-group">
                            <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control" placeholder="{{ __('messages.Name or email') }}" aria-label="Example text with button addon" aria-describedby="button-addon1">
                            <button type="submit" class="btn btn-outline-primary waves-effect" type="button" id="button-addon1"><span class="d-none d-sm-block">{{ __("messages.Search") }}</span><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ route('admin.admins.export') }}" class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{ __("messages.EXPORT XLS") }}</span><i class="fa-solid fa-file-export"></i></a>
                        <a href="{{ route('admin.admins.create') }}" class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{ __("messages.ADD NEW ") }}</span><i class="fa-solid fa-circle-plus "></i></a>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.Name') }}</th>
                            <th>{{ __('messages.Email') }}</th>
                            <th>{{ __('messages.Status') }}</th>
                            <th>{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php
                            $i = 1;
                            $skipCount = $data->perPage() * $data->currentPage() - $data->perPage();
                        @endphp
                        @include('inc.is_empty_data' , ['var_check_empty'=>$data , 'var_check_empty_rows'=>5])

                        @foreach ($data as $item)
                            <tr>
                                <th>{{ $skipCount + $i }}</th>
                                @php $i++  @endphp


                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>

                                <td>
                                    @if ($item->is_block)
                                        <span class="badge bg-label-danger me-1">{{ __('messages.Blocked') }}<i
                                                class="fa-solid fa-ban"></i></span>
                                    @else
                                        <span class="badge bg-label-primary me-1">{{ __('messages.Active') }}<i
                                                style="" class="fa-solid fa-circle-check"></i></span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('admin.admins.edit' , $item->id) }}"><i
                                                    class="ti ti-pencil "></i>{{ __("messages.Edit") }}</a>


                                                @if ($item->is_block)
                                                <a data-url="{{ route('admin.admins.unblock' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="post" data-message="{{ __('messages.Are you sure to activate the account ?') }}" class="dropdown-item btn-action" href="javascript:void(0);"><i
                                                    class="fa-solid fa-circle-check"></i> {{ __('messages.Activate the account') }}</a>
                                                @else
                                                <a data-url="{{ route('admin.admins.block' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="post" data-message="{{ __('messages.Are you sure the account is blocked ?') }}" class="dropdown-item btn-action" href="javascript:void(0);"><i
                                                    class="fa-solid fa-ban "></i> {{ __("messages.Account ban") }}</a>
                                                @endif




                                            <a data-url="{{ route('admin.admins.destroy' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="delete" data-message="{{ __('messages.Are you sure you want to delete? You cannot undo it') }}" class="dropdown-item btn-action" href="javascript:void(0);"><i
                                                    class="ti ti-trash "></i> {{ __("messages.Delete") }}</a>
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


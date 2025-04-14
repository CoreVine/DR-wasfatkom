@extends('layouts.app')
@section('title')
{{ __('messages.Articles') }}
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages.Articles') => 'active',
            ],
        ])


        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="p-3 d-flex justify-content-between">
                <div>
                    <form>
                        <div class="input-group">
                            <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control" placeholder="{{ __('messages.Name or email') }}" aria-label="Example text with button addon" aria-describedby="button-addon1">
                            <button type="submit" class="btn btn-outline-primary waves-effect" type="button" id="button-addon1">{{ __("messages.Search") }}<i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ route('admin.admins.export') }}" class="btn btn-outline-primary waves-effect text-primary">{{ __("messages.EXPORT XLS") }}<i class="fa-solid fa-file-export"></i></a>
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-outline-primary waves-effect text-primary">{{ __("messages.ADD NEW ") }}<i class="fa-solid fa-circle-plus"></i></a>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.Title Article') }}</th>
                            <th>{{ __('messages.Date created') }}</th>
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


                                <td>{{ $item->title }}</td>
                                <td>{{ $item->created_at_format }}</td>

                                <td>
                                    @if (!$item->is_active)
                                        <span class="badge bg-label-danger me-1">{{ __('messages.In Active') }}<i
                                                class="fa-solid fa-circle-xmark"></i></span>
                                    @else
                                        <span style="min-width: 100px" class="badge bg-label-primary me-1">{{ __('messages.Active') }}<i
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
                                            <a class="dropdown-item" href="{{ route('admin.posts.edit' , $item->id) }}"><i
                                                    class="ti ti-pencil "></i>{{ __("messages.Edit") }}</a>


                                                @if (!$item->is_active)
                                                <a  data-url="{{ route('admin.posts.active_inactive' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="post" data-message="{{ __('messages.Are you sure to activate the article ?') }}" class="dropdown-item btn-action" href="javascript:void(0);"><i
                                                    class="fa-solid fa-circle-check"></i> {{ __('messages.Activation') }}</a>
                                                @else
                                                <a data-url="{{ route('admin.posts.active_inactive' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="post" data-message="{{ __('messages.Are you sure you want to deactivate the article ?') }}" class="dropdown-item btn-action" href="javascript:void(0);">
                                                    <i class="fa-solid fa-circle-xmark"></i>  {{ __('messages.Deactivate') }}</a>
                                                @endif




                                            <a data-url="{{ route('admin.posts.destroy' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="delete" data-message="{{ __('messages.Are you sure to delete ?') }}" class="dropdown-item btn-action" href="javascript:void(0);"><i
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


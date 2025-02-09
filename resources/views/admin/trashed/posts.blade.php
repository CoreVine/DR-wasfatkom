@extends('layouts.app')
@section('title')
{{ __('messages.Recycle bin') ."-" . __("messages.Articles")}}
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages.Recycle bin') => null,
                __('messages.Articles') => 'active',
            ],
        ])


        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="p-3 d-flex justify-content-between">
                <div>
                    <form>
                        <div class="input-group">
                            <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control" placeholder="{{ __('messages.Title Article') }}" aria-label="Example text with button addon" aria-describedby="button-addon1">
                            <button type="submit" class="btn btn-outline-primary waves-effect" type="button" id="button-addon1">{{ __("messages.Search") }}<i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <div>
                    {{-- <div class="btn-group" role="group" aria-label="Basic example">
                        <a href="{{ route('admin.admins.export') }}" class="btn btn-outline-primary waves-effect text-primary">{{ __("messages.EXPORT XLS") }}<i class="fa-solid fa-file-export"></i></a>
                        <a href="{{ route('admin.admins.create') }}" class="btn btn-outline-primary waves-effect text-primary">{{ __("messages.ADD NEW ") }}<i class="fa-solid fa-circle-plus"></i></a>
                    </div> --}}
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.Title Article') }}</th>
                            <th>{{ __('messages.Deletion date') }}</th>
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
                                <td>{{ $item->deleted_at_format }}</td>


                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">

                                            @can('restore_post')
                                            <a data-url="{{ route('admin.posts.restore' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="post" data-message="{{ __('messages.Are you sure to restore the data ?') }}" class="dropdown-item btn-action" href="javascript:void(0);">
                                                <i class="ti ti-refresh"></i>{{ __("messages.Restore") }}</a>
                                            @endcan
                                            @can('force_delete_post')
                                            <a data-url="{{ route('admin.posts.force_delete' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="post" data-message="{{ __('messages.Are you sure you have deleted it permanently ? You cannot undo it') }}" class="dropdown-item btn-action" href="javascript:void(0);"><i
                                                class="ti ti-trash "></i> {{ __("messages.Delete") }}</a>
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


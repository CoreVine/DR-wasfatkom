@extends('layouts.app')
@section('title')
{{ __('messages.Logs') ."-" . __("messages.Software errors")}}
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages.Logs') => null,
                __('messages.Software errors') => 'active',
            ],
        ])


        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="p-3 d-flex justify-content-between">
                <div>
                    <form>
                        <div class="input-group">
                            <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control" placeholder="{{ __('messages.Task') }}" aria-label="Example text with button addon" aria-describedby="button-addon1">
                            <button type="submit" class="btn btn-outline-primary waves-effect" type="button" id="button-addon1">{{ __("messages.Search") }}<i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <div>
                    <div class="btn-group" role="group" aria-label="Basic example">
                        <a data-url="{{ route("admin.logs.errors.clear") }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="post" data-message="{{ __('messages.Are you sure you have deleted it permanently ? You cannot undo it') }}" class="btn btn-outline-danger waves-effect text-danger btn-action" href="javascript:void(0);"> {{ __("messages.Clear log") }}<i class="fa-solid fa-broom"></i></a>
                    </div>
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.Task') }}</th>
                            <th>{{ __('messages.Error message') }}</th>
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


                                <td>{{ $item->function_name }}</td>
                                <td>{{ $item->message }}</td>


                               

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


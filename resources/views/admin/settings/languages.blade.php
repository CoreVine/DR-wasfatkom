@extends('layouts.app')
@section('title')
{{ __('messages.Administration') }}
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages.Settings') => null,
                __('messages.Languages') => 'active',
            ],
        ])


        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="p-3 d-flex justify-content-between">
                <div>
                    <form>
                        <div class="input-group">
                            <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control" placeholder="{{ __('messages.Name') }}" aria-label="Example text with button addon" aria-describedby="button-addon1">
                            <button type="submit" class="btn btn-outline-primary waves-effect" type="button" id="button-addon1">{{ __("messages.Search") }}<i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
                <div>
                    @foreach ($languages_active as $lang)
                    <span class="badge bg-label-success">
                        @if ($lang->is_default)
                        <i class="fa-solid fa-circle-check  text-success"></i>
                        @endif

                        {{ $lang->name }} - {{ $lang->native_name }}</span>

                    @endforeach
                </div>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages.Name') }}</th>
                            <th>{{ __('messages.Status') }}</th>
                            <th>{{ __('messages.Default') }}</th>
                            <th>{{ __('messages.Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @php
                            $i = 1;
                            $skipCount = $data->perPage() * $data->currentPage() - $data->perPage();
                        @endphp
                        @include('inc.is_empty_data' , ['var_check_empty'=>$data , 'var_check_empty_rows'=>4])

                        @foreach ($data as $item)
                            <tr>
                                <th>{{ $skipCount + $i }}</th>
                                @php $i++  @endphp


                                <td>{{ $item->name }} - {{ $item->native_name }}</td>
                                <td>
                                    @if (!$item->is_active)
                                        <span class="badge bg-label-danger me-1">{{ __('messages.In Active') }}
                                            <i class="fa-solid fa-circle-xmark"></i></span>
                                    @else
                                        <span style="min-width: 100px" class="badge bg-label-success me-1">{{ __('messages.Active') }}<i
                                                style="" class="fa-solid fa-circle-check"></i></span>
                                    @endif
                                </td>

                                <td>
                                    @if (!$item->is_default)
                                            <i class="fa-solid fa-circle-xmark"></i>
                                    @else

                                            <i class="fa-solid fa-circle-check  text-success"></i>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">

                                                @if ($item->is_active)
                                                <a data-url="{{ route('admin.settings.languages.active_in_active' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="post" data-message="{{ __('messages.Are you sure to deactivate ?') }}" class="dropdown-item btn-action" href="javascript:void(0);">
                                                    <i class="fa-solid fa-circle-xmark"></i>  {{ __('messages.Deactivate') }}</a>
                                                @else
                                                <a data-url="{{ route('admin.settings.languages.active_in_active' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="post" data-message="{{ __('messages.Are you sure to activate ?') }}" class="dropdown-item btn-action" href="javascript:void(0);">
                                                    <i class="fa-solid fa-circle-check"></i>  {{ __('messages.Activation') }}</a>
                                                @endif


                                                @if (!$item->is_default)
                                                <a data-url="{{ route('admin.settings.languages.set_default' , $item->id) }}" data-text_btn_confirm="{{ __('messages.Confirm') }}"  data-text_btn_cancel="{{ __('messages.Cancel') }}"  data-method="post" data-message="{{ __('messages.Set as default') }}" class="dropdown-item btn-action" href="javascript:void(0);">
                                                    <i class="fa-solid fa-language"></i> {{ __('messages.Set as default') }}</a>
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


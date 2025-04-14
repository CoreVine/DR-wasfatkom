@extends('layouts.app')
@section('title')
{{ __('messages.Doctor sales') }}
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('inc.breadcrumb', [
    'breadcrumb_items' => [
    __('messages.Home') => route('home'),
    __('messages.Doctor sales') . "/" . $doctor->name => 'active',
    ],
    ])


    <!-- Basic Bootstrap Table -->
    <div class="card">
        <div class="p-3 d-flex justify-content-between">
            <div class="mx-2">
                {{-- <form>
                    <div class="input-group">
                        <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control"
                            placeholder="{{ __('messages.Name or email') }}" aria-label="Example text with button addon"
                            aria-describedby="button-addon1">
                        <button type="submit" class="btn btn-outline-primary waves-effect" type="button"
                            id="button-addon1"><span class="d-none d-sm-block">{{ __("messages.Search") }}</span><i
                                class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form> --}}
            </div>
            <div>
                <div class="btn-group" role="group" aria-label="Basic example">
                    {{-- <a href="{{ route('admin.doctors.export') }}"
                        class="btn btn-outline-primary waves-effect text-primary">{{ __("messages.EXPORT XLS") }}<i
                            class="fa-solid fa-file-export"></i></a> --}}
                    @can('create_doctor')
                    <a href="{{ route('admin.doctors.export_sales'  ,$doctor->id) }}"
                        class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{
                            __("messages.EXPORT XLS") }}</span><i class="fa-solid fa-file-export"></i></a>

                    @endcan
                </div>
            </div>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('messages_301.Product name') }}</th>
                        <th>{{ __('messages_301.Code') }}</th>
                        <th>{{ __('messages_303.barcode') }}</th>
                        <th>{{ __('messages.Qty') }}</th>
                        <th>{{ __('messages.Price') }}</th>
                        <th>{{ __('messages.Discount') }}</th>
                        <th>{{ __('messages.Total before discount') }}</th>
                        <th>{{ __('messages.Total') }}</th>
                        <th>{{ __('messages.Date created') }}</th>

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
                        @php $i++ @endphp


                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->product->code }}</td>
                        <td>{{ $item->product->barcode }}</td>
                        <td>{{ $item->qty}}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->discount }} % </td>
                        <td>{{ $item->total_befor_discount }}</td>
                        <td>{{ $item->total }}</td>
                        @php
                          $format = App\Http\Helpers\HelperSetting::get_value('time_format');
                          $formatted_date = Carbon\Carbon::parse($item->created_at)->format($format);
                        @endphp
                        <td>{{ $formatted_date }}</td>




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

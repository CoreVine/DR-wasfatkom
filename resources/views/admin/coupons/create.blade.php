@extends('layouts.app')
@section('title')
    {{ __('messages_301.Coupons') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />

    <style>
        .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
            width: 100% !important;
        }

        .animate-icon {
         transition: 0.4s;
        }
        .rotate {
            rotate: 720deg;
        }

    </style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages_301.Coupons') => route('admin.coupons.index'),
                __('messages.Create') => 'active',
            ],
        ])



        <div class="row justify-content-center">
            <!-- Form controls -->
            <div class="col-md-12">
                <!-- Multi Column with Form Separator -->
                <div class="card mb-4">
                    <h5 class="card-header pt-2">{{ __('messages_301.Coupon data') }}</h5>

                    <form class="card-body" action="{{ route('admin.coupons.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="row g-3">



                                <div class="col-md-6">
                                    <label class="form-label"
                                        for="flatpickr-date">{{ __('messages_301.From date') }}</label>
                                    <input type="date" id="flatpickr-date"
                                        class="form-control flatpickr-input active  @error('from_date') is-invalid @enderror"
                                        name="from_date" value="{{ old('from_date') }}" />
                                    @error('from_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('messages_301.To date') }}</label>
                                    <input type="date"
                                        class="form-control flatpickr-input active  @error('to_date') is-invalid @enderror"
                                        name="to_date" value="{{ old('to_date') }}" />
                                    @error('to_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"
                                        for="html5-number-input">{{ __('messages_301.Count use') }}</label>
                                    <input type="number" id="html5-number-input"
                                        class="form-control @error('count_use') is-invalid @enderror" name="count_use"
                                        value="{{ old('count_use') }}" />
                                    @error('count_use')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label" for="html5-number-input">{{ __('messages_301.Percentage') }}
                                        %</label>
                                    <input type="number" id="html5-number-input"
                                        class="form-control @error('percentage') is-invalid @enderror" name="percentage"
                                        value="{{ old('percentage') }}" />
                                    @error('percentage')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- <div class="row g-3 input-group justify-contet-between"> --}}
                                <div class="col-md-4">
                                    <label class="form-label" for="input-code">{{ __('messages_301.Code') }}</label>
                                    <input type="text" id="input-code"
                                        class="form-control @error('code') is-invalid @enderror" name="code"
                                        value="{{ old('code') }}" />
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-1">
                                        <div class="input-group-append pt-4">
                                            <button class="btn btn-outline-secondary" id="generate_code" type="button">
                                                <i class="ti ti-refresh animate-icon"></i>
                                            </button>
                                        </div>
                                </div>




                                <div class="col-md-6 mb-4">
                                    <label for="TagifyUserList"
                                        class="form-label">{{ __('messages.Identify doctors') }}</label>
                                    <input id="TagifyUserList" name="doctors"
                                        class="form-control @error('doctors') is-invalid
                                        @enderror"
                                        value="demo@admin.com" />
                                    @error('doctors')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <div class="text-light small fw-medium mb-3">
                                        {{ __('messages_301.Activation') }}
                                    </div>
                                    <label class="switch">
                                        <input type="checkbox" name="is_active" checked class="switch-input" />
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on"></span>
                                            <span class="switch-off"></span>
                                        </span>
                                        <span class="switch-label">{{ __('messages_301.Activation') }}</span>
                                    </label>
                                </div>

                                {{-- </div> --}}


                            </div>











                            <!-- Form with Tabs -->

                            <div class="pt-4">
                                <button type="submit"
                                    class="btn btn-primary me-sm-3 me-1">{{ __('messages.Save') }}</button>
                                <button type="reset" class="btn btn-label-secondary">{{ __('messages.Cancel') }}</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    @include('admin.coupons.script')
    <script>
        $(function(){

            function make_code(length) {
            let result = '';
            const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            const charactersLength = characters.length;
            let counter = 0;
            while (counter < length) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength));
            counter += 1;
            }
            return result;
        }


        $("#input-code").val(make_code(10))

            $("#generate_code").click(function(){
                $("#input-code").val(make_code(10))
                $(".ti-refresh").toggleClass("rotate")

            })
        })
    </script>
@endsection

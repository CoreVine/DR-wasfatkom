@extends('layouts.app')
@section('title')
    {{ __('messages_301.Formulations') }}
@endsection
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages_301.Formulations') => 'active',
            ],
        ])


        <!-- Basic Bootstrap Table -->
        <div class="card">
            <div class="container-xxl container-p-y">
                <div class="misc-wrapper">
                    <h2 class="mb-1 mx-2">{{ __('messages_301.Coming soon') }} :)</h2>
                    <div class="mt-4">
                        <img src="{{ asset('assets/img/illustrations/page-misc-launching-soon.png') }}"
                            alt="page-misc-launching-soon" width="263" class="img-fluid" />
                    </div>
                    
                </div>
            </div>
            <div class="container-fluid misc-bg-wrapper">
                <img src="{{ asset('assets/img/illustrations/bg-shape-image-light.png') }}" alt="page-misc-coming-soon"
                    data-app-light-img="illustrations/bg-shape-image-light.png"
                    data-app-dark-img="illustrations/bg-shape-image-dark.png" />
            </div>
        </div>
        <!--/ Basic Bootstrap Table -->

    </div>
@endsection

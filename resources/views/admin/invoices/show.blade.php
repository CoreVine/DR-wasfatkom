@extends('layouts.app')
@section('title')
@if (auth()->user()->type->value == 'admin')
{{ __('messages.Invoices') }}
@else
{{ __('messages_301.Recipes') }}
@endif
@endsection
@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @if (auth()->user()->type->value == 'admin')
    @include('inc.breadcrumb', [
    'breadcrumb_items' => [
    __('messages.Home') => route('home'),
    __('messages.Invoices') => route('admin.invoices.index'),
    '#' . $item->invoice_num => 'active',
    ],
    ])
    @else
    @include('inc.breadcrumb', [
    'breadcrumb_items' => [
    __('messages.Home') => route('home'),
    __('messages_301.Recipes') => route('admin.invoices.index'),
    '#' . $item->invoice_num => 'active',
    ],
    ])
    @endif
</div>

@include('admin.invoices.invoice_details_show', ['is_admin' => true])
@endsection
@section('script')
<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
@include('admin.invoices.scripts.change_status')

<script>
    function myFunction() {
            // Get the text field
            var copyText = document.getElementById("input_link");

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            navigator.clipboard.writeText(copyText.value);

            toastr["success"]("Copy completed successfully : " + copyText.value)
            // Alert the copied text
            //   alert("Copied the text: " );
        }
</script>
@endsection
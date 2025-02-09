@extends('layouts.app')
@section('title')
{{ __("messages.Settings") }}
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('inc.breadcrumb' , ['breadcrumb_items'=>[
         __("messages.Home")=>route('home'),
         __("messages.Settings")=>null,
         __("messages.".$page) =>"active"
    ]])



    <div class="row justify-content-center">


        @foreach ($settings as  $key=>$setting)
            <!-- Form controls -->
            <div class="col-md-12">
                <!-- Multi Column with Form Separator -->
                <div class="card mb-4">
                <h5 class="card-header">{{ $key }}</h5>
                <form class="card-body" action="{{ route('admin.settings.pages.update' , $key) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        @foreach ($setting as $item )
                            @if (in_array($item->type , ['text' , 'number' , 'email' , 'url']))
                                    @include('admin.settings.inc_types.input')
                            @elseif($item->type == "textarea")
                            @include('admin.settings.inc_types.textarea')

                            @elseif($item->type == "radio")
                            @include('admin.settings.inc_types.radio')
                            @endif
                        @endforeach
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('messages.Save') }}</button>
                        <button type="reset" class="btn btn-label-secondary">{{ __('messages.Cancel') }}</button>
                    </div>
                </form>
                </div>
            </div>
        @endforeach




    </div>

 </div>
@endsection

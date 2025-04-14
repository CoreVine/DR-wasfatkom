@extends('layouts.app')
@section('title')
    {{ __('messages_301.Suppliers') }}
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages_301.Suppliers') => route('admin.suppliers.index'),
                __('messages.Edit') => 'active',
            ],
        ])


        <div class="row justify-content-center">
            <!-- Form controls -->

            <div class="col-md-12">
                <!-- Multi Column with Form Separator -->
                <div class="card mb-4">
                    <h5 class="card-header">{{ __('messages_301.Supplier data') }}</h5>
                    <form class="card-body" action="{{ route('admin.suppliers.update', $item->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="row">
                                <div class="col-md-6 mt-3">
                                    <label class="form-label mb-1" for="name">{{ __('messages.Name') }}
                                    </label>
                                    <input type="text" id="name"
                                        class="form-control   @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name', $item->name) }}" />
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>
                        </div>
                        <div class="pt-4">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('messages.Save') }}</button>
                            <button type="reset" class="btn btn-label-secondary">{{ __('messages.Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

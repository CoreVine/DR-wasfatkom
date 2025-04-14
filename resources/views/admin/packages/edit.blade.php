@extends('layouts.app')
@section('title')
{{ __('messages_303.packages') }}
@endsection
@section('style')
<style>
    .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
        width: 100% !important;
    }
</style>
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('inc.breadcrumb', [
    'breadcrumb_items' => [
    __('messages.Home') => route('home'),
    __('messages_303.packages') => route('admin.packages.index'),
    __('messages.Edit') => 'active',
    ],
    ])
    <div class="row justify-content-center">
        <!-- Form controls -->
        <div class="col-md-12">
            <!-- Multi Column with Form Separator -->
            <div class="card mb-4">
                <h5 class="card-header">{{ __('messages_303.packages') }}</h5>
                <form class="card-body" action="{{ route('admin.packages.update', $item->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- Method spoofing for PUT request -->
                    <div class="row g-3">
                        <div class="nav-align-top mb-4">
                            <ul class="nav nav-tabs" role="tablist">
                                @foreach ($languages as $lang)
                                <li class="nav-item" role="presentation">
                                    <button type="button" class="nav-link {{ $loop->index == 0 ? 'active' : '' }}"
                                        role="tab" data-bs-toggle="tab" data-bs-target="#navs-{{ $lang->code }}"
                                        aria-controls="navs-{{ $lang->id }}" aria-selected="true">
                                        {{ $lang->native_name }}
                                    </button>
                                </li>
                                @endforeach
                            </ul>
                            <div class="tab-content p-0">

                                <div class="tab-pane fade active show" id="navs-ar" role="tabpanel">

                                    <div class="row">
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label mb-1" for="name_ar">
                                                {{ __('messages_303.name') }} (ar)
                                            </label>
                                            <input type="text" id="name_ar" data-slug_name="slug_ar"
                                                class="form-control input_name  @error('name_ar') is-invalid @enderror"
                                                name="name_ar" value="{{ old('name_ar', $ar?->name ?? '') }}" />

                                            @error('name_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label mb-1" for="description_ar">
                                                {{ __('messages_303.description') }} (ar )
                                            </label>
                                            <textarea id="description_ar"
                                                class="form-control editor_style_ar  @error('description_ar') is-invalid @enderror"
                                                name="description_ar">{{ old('description_ar', $ar?->description ?? '') }}</textarea>
                                            @error('description_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="navs-en" role="tabpanel">

                                    <div class="row">
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label mb-1" for="name_en">
                                                {{ __('messages_303.name') }} (en)
                                            </label>
                                            <input type="text" id="name_en" data-slug_name="slug_en"
                                                class="form-control input_name  @error('name_en') is-invalid @enderror"
                                                name="name_en" value="{{ old('name_en', $en?->name ?? '') }}" />
                                            <!-- Fetch existing package name -->
                                            @error('name_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label mb-1" for="description_en">
                                                {{ __('messages_303.description') }} (en )
                                            </label>
                                            <textarea id="description_en"
                                                class="form-control editor_style_en  @error('description_en') is-invalid @enderror"
                                                name="description_en">{{ old('description_en', $en?->description ?? '') }}</textarea>
                                            <!-- Fetch existing package description -->
                                            @error('description_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row g-3">

                            @if (auth()->user()->type->value != 'doctor')
                            <div class="col-md-6 p-2">
                                <label class="form-label d-block">{{ __('messages_303.doctors') }}</label>
                                <select name="doctor_id"
                                    class=" form-control select2 show-tick @error('doctor_id') is-invalid @enderror"
                                    id="select_supplier_id" data-icon-base="ti" data-tick-icon="ti-check"
                                    data-style="btn-default" data-live-search="true">
                                    <option selected disabled>{{ __('messages_301.Doctor name') }}</option>
                                    @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" @selected(old('doctor_id', $item->doctor_id) ==
                                        $doctor->id)>
                                        {{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @else
                            <input type="hidden" name="doctor_id" value="{{ auth()->id() }}">
                            @endif
                            <div class="col-md-12 mb-4">
                                <label for="select2Multiple" class="form-label">{{ __('messages_303.products')
                                    }}</label>
                                <select id="select2Multiple" name="products[]" class="select2 form-select @error('products')
                                        is-invalid
                                    @enderror" multiple>
                                    <option disabled>{{ __('messages_301.Select products') }}</option>
                                    @foreach ($products as $product)
                                    <option value="{{ $product->id }}" {{ in_array($product->id, old('products',
                                        $item->products->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('products')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                        <div class="pt-4">
                            <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ __('messages.Save')
                                }}</button>
                            <button type="reset" class="btn btn-label-secondary">{{ __('messages.Cancel') }}</button>
                        </div>
                </form>


            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/tagify/tagify.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/js/forms-selects.js') }}"></script>
<script src="{{ asset('assets/js/forms-tagify.js') }}"></script>

@include('admin.packages.script')
@endsection
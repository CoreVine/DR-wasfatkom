@extends('layouts.app')
@section('title')
    {{ __('messages_301.Sub categories') }}
@endsection
@section('style')
    <style>


    </style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        @include('inc.breadcrumb', [
            'breadcrumb_items' => [
                __('messages.Home') => route('home'),
                __('messages_301.Sub categories') => route('admin.sub_categories.index'),
                __('messages.Create') => 'active',
            ],
        ])



        <div class="row justify-content-center">
            <!-- Form controls -->
            <div class="col-md-12">
                <!-- Multi Column with Form Separator -->
                <div class="card mb-4">
                    <h5 class="card-header">{{ __('messages_301.Category data') }}</h5>
                    <form class="card-body" action="{{ route('admin.sub_categories.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            <div class="d-flex align-items-start align-items-sm-center gap-4">
                                <img src="{{ asset('default-images/category.png') }}" alt="user-avatar"
                                    class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-3" tabindex="0">
                                        <span class="d-none d-sm-block">{{ __('messages_303.Upload new photo') }}</span>
                                        <i class="ti ti-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" name="image"
                                            class="account-file-input @error('image')
                                is-invalid
                              @enderror"
                                            hidden accept="image/png, image/jpeg, image/jpg" />
                                    </label>
                                    <button type="button" class="btn btn-label-secondary account-image-reset mb-3">
                                        <i class="ti ti-refresh-dot d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">{{ __('messages_303.Reset') }}</span>
                                    </button>

                                    <div class="text-muted">Allowed JPG, JPEG or PNG. Max size of 2M</div>
                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="nav-align-top  mb-4">
                                <ul class="nav nav-tabs" role="tablist">
                                    @foreach ($languages as $lang)
                                        <li class="nav-item" role="presentation">
                                            <button type="button" class="nav-link {{ $loop->index == 0 ? 'active' : '' }}"
                                                role="tab" data-bs-toggle="tab"
                                                data-bs-target="#navs-{{ $lang->id }}"
                                                aria-controls="navs-{{ $lang->id }}" aria-selected="true">
                                                {{ $lang->native_name }}
                                            </button>
                                        </li>
                                    @endforeach


                                </ul>
                                <div class="tab-content p-0">
                                    @foreach ($languages as $lang)
                                        <div class="tab-pane fade {{ $loop->index == 0 ? 'active show' : '' }}  "
                                            id="navs-{{ $lang->id }}" role="tabpanel">
                                            <div class="row">
                                                <div class="col-md-6 mt-3">
                                                    <label class="form-label mb-1"
                                                        for="name_{{ $lang->code }}">{{ __('messages_303.category name') }}
                                                        ({{ $lang->code }})
                                                    </label>
                                                    <input type="text" id="name_{{ $lang->code }}"
                                                        data-slug_name="slug_{{ $lang->code }}"
                                                        class="form-control input_name  @error('name_' . $lang->code) is-invalid @enderror"
                                                        name="name_{{ $lang->code }}"
                                                        value="{{ old('name_' . $lang->code) }}" />
                                                    @error('name_' . $lang->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                {{-- <div class="col-md-12 mt-3">
                                                    <label class="form-label mb-1"
                                                        for="description_{{ $lang->code }}">{{ __('messages_303.description') }}
                                                        (
                                                        {{ $lang->code }} )</label>
                                                    <textarea id="description_{{ $lang->code }}"
                                                        class="form-control editor_style_{{ $lang->code }}  @error('description_' . $lang->code) is-invalid @enderror"
                                                        name="description_{{ $lang->code }}">{{ old('description_' . $lang->code) }}</textarea>
                                                    @error('description_' . $lang->code)
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div> --}}

                                            </div>
                                        </div>
                                    @endforeach


                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="mb-1">{{ __('messages_301.Categories') }}</label>
                                <select name="category_id" id="category_id"
                                    class="form-control select2 category_id @error('category_id') is-invalid @enderror">
                                    <option value="">--------</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
@section('script')
    <script src="{{ asset('assets/js/pages-account-settings-account.js') }}"></script>
@endsection

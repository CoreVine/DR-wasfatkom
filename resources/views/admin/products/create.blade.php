@extends('layouts.app')
@section('title')
{{ __('messages_303.products') }}
@endsection
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    @include('inc.breadcrumb', [
    'breadcrumb_items' => [
    __('messages.Home') => route('home'),
    __('messages_303.products') => route('admin.products.index'),
    __('messages.Create') => 'active',
    ],
    ])


    <div class="row justify-content-center">
        <!-- Form controls -->
        <div class="col-md-12">
            <!-- Multi Column with Form Separator -->
            <div class="card mb-4">
                <h5 class="card-header">{{ __('messages_303.products') }}</h5>
                <form class="card-body" action="{{ route('admin.products.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="nav-align-top  mb-4">
                            <ul class="nav nav-tabs" role="tablist">
                                @foreach ($languages as $lang)
                                <li class="nav-item" role="presentation">
                                    <button type="button" class="nav-link {{ $loop->index == 0 ? 'active' : '' }}"
                                        role="tab" data-bs-toggle="tab" data-bs-target="#navs-{{ $lang->id }}"
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
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label mb-1" for="name_{{ $lang->code }}">{{
                                                __('messages_303.name') }}
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
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label mb-1" for="description_{{ $lang->code }}">{{
                                                __('messages_303.description') }}
                                                (
                                                {{ $lang->code }} )</label>
                                            <textarea id="description_{{ $lang->code }}"
                                                class="form-control editor_style_{{ $lang->code }}  @error('description_' . $lang->code) is-invalid @enderror"
                                                name="description_{{ $lang->code }}">{{ old('description_' . $lang->code) ?? " " }}</textarea>
                                            @error('description_' . $lang->code)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                                @endforeach
                                <div class="row">
                                    <div class="col-md-6 p-2">
                                        <label class="form-label d-block">{{ __('messages.Brand') }}</label>
                                        <select name="category_id"
                                            class=" form-control select2 show-tick @error('category_id') is-invalid @enderror"
                                            id="select_category_id" data-icon-base="ti" data-tick-icon="ti-check"
                                            data-style="btn-default" data-live-search="true">
                                            <option selected disabled>{{ __('messages.Brand') }}</option>
                                            @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                @selected(old('category_id')==$category->id)>
                                                {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 p-2">
                                        <label class="form-label d-block">{{ __('messages.category name') }}</label>
                                        <select name="sub_category_id"
                                            class=" form-control select2 show-tick @error('sub_category_id') is-invalid @enderror"
                                            id="select_sub_category_id" data-icon-base="ti" data-tick-icon="ti-check"
                                            data-style="btn-default" data-live-search="true">
                                            <option selected disabled>{{ __('messages.category name') }}</option>

                                        </select>
                                        @error('sub_category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 p-2">
                                        <label class="form-label d-block">{{ __('messages_301.Supplier') }}</label>
                                        <select name="supplier_id"
                                            class=" form-control select2 show-tick @error('supplier_id') is-invalid @enderror"
                                            id="select_supplier_id" data-icon-base="ti" data-tick-icon="ti-check"
                                            data-style="btn-default" data-live-search="true">
                                            <option selected disabled>{{ __('messages_301.Supplier name') }}</option>
                                            @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}"
                                                @selected(old('supplier_id')==$supplier->id)>
                                                {{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>



                                    <div class="col-md-6 mb-3">
                                        <label for="formFile" class="form-label">{{ __('messages_303.image') }}</label>
                                        <input class="form-control @error('image')
                                            is-invalid
                                          @enderror" type="file" id="formFile" name="image"
                                            accept="image/png, image/jpeg, image/jpg">
                                        @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="formFile" class="form-label">{{ __('messages_303.barcode')
                                            }}</label>
                                        <input class="form-control @error('barcode')
                                            is-invalid
                                          @enderror" type="text" id="" name="barcode" value="{{ old('barcode') }}">
                                        @error('barcode')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages_301.Item code') }}</label>
                                        <input class="form-control @error('code')
                                            is-invalid
                                          @enderror" type="text" name="code" value="{{ old('code') }}">
                                        @error('code')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('messages.Qty') }}</label>
                                        <input class="form-control @error('qty')
                                            is-invalid
                                          @enderror" type="number" name="qty" value="{{ old('qty') }}">
                                        @error('qty')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="formFile" class="form-label">{{ __('messages.Tax') }} %</label>
                                        <input class="form-control @error('tax') is-invalid @enderror" id="formFile"
                                            name="tax" value="{{ old('tax') }}">
                                        @error('tax')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="formFile" class="form-label">{{ __('messages_303.price') }}</label>
                                        <input class="form-control @error('price') is-invalid @enderror" type="text"
                                            id="formFile" name="price" value="{{ old('price') }}">
                                        @error('price')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>


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
@section('script')
<script>
    $(document).ready(function() {
            @if (old('category_id'))
                get_sub_categories("{{ old('category_id') }}")
            @endif

            $("#select_category_id").change(function() {
                var category_id = $(this).val();
                get_sub_categories(category_id)
            })

            function get_sub_categories(category_id) {
                var url = "{{ route('admin.get_sub_category') }}?category_id=" + category_id;
                $.ajax({
                    url,
                    success: function(res) {
                        console.log(res)
                        $("#select_sub_category_id").html(res)

                        var old_sub_category_id = "{{ old('sub_category_id') }}";
                        if (old_sub_category_id) {
                            $("#select_sub_category_id").val(old_sub_category_id);
                        }
                    }
                })
            }

            $('.select2').select2();



        })
</script>
@endsection
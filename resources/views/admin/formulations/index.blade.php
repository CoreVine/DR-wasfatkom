@extends('layouts.app')

@section('title')
{{ __('messages_303.formulations') }}
@endsection

@section('style')
<style>
  .box-img img {
    height: 100%;
    width: 100%;
    object-fit: contain;
  }

  .box-img {
    height: 50px;
  }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  @include('inc.breadcrumb', [
  'breadcrumb_items' => [
  __('messages.Home') => route('home'),
  __('messages_303.formulations') => 'active',
  ],
  ])


  <div class="card">
    <div>
      <div class="row">
        <div class="col-12">
          <div class="">
            <div class="card-body">
              <div class="">
                @if (request('key_words'))
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_product_name"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Formulation name') }}
                  : {{ request('key_words') }}</span>
                @endif
                @if ($formulation_name)
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_product_id"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Formulation name') }}
                  : {{ $formulation_name }}</span>
                @endif


                @if ($category_name)
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_category_id"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_303.category name') }}
                  : {{ $category_name }}</span>
                @endif

                @if ($sub_category_name)
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_sub_category_id"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Sub category') }}
                  : {{ $sub_category_name }}</span>
                @endif

              </div>
              <div class="col-12 col-md-6 mt-3">
                <form>
                  <div class="input-group">
                    <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control"
                      placeholder="{{ __('messages_303.name') }}" aria-label="Example text with button addon"
                      aria-describedby="button-addon1">
                    <button type="submit" class="btn btn-outline-primary waves-effect" type="button"
                      id="button-addon1"><span class="d-none d-sm-block">{{ __("messages.Search") }}</span><i
                        class="fa-solid fa-magnifying-glass"></i></button>
                  </div>
                </form>
              </div>
              <div class="d-flex justify-content-between mt-2">
                <a class="btn btn-primary me-1" data-bs-toggle="collapse" href="#collapseExample" role="button"
                  aria-expanded="false" aria-controls="collapseExample">
                  <i class="ti ti-adjustments-horizontal"></i>
                </a>
                <div class="btn-group" role="group" aria-label="Basic example">
                  <a href="{{ route('admin.formulations.export').$request_filter }}"
                    class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{
                      __('messages.EXPORT XLS') }}</span><i class="fa-solid fa-file-export"></i></a>

                  @can('create_product')
                  <a href="{{ route('admin.formulations.create') }}"
                    class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{
                      __("messages.ADD NEW ") }}</span><i class="fa-solid fa-circle-plus "></i></a>
                  @endcan

                  @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                    <form action="{{ route('admin.formulations.import') }}" method="POST" enctype="multipart/form-data"
                          style="position: relative; display: flex">
                      @csrf
                      <button type="submit" class="btn btn-outline-primary waves-effect text-primary">
                        {{ __('messages.Import') }}
                        <i class="fa-solid fa-file-import"></i>
                      </button>
                      <div class=""
                           style="position: relative; border: 1px solid #ddd; padding: 7px; margin: 0 4px; border-radius: 5px;">
                        <input style=" z-index: 9999; position: absolute; width: 100%; height: 100%; left: 0; opacity: 0;"
                               type="file" name="file" class="form-control" required>
                        {{ __('messages.Click To Select File') }}
                      </div>
                      @error('file') <span>{{ $message }}</span> @enderror
                    </form>
                  @endif

                </div>
              </div>


              <div class="collapse border border-dark rounded p-4" id="collapseExample">

                <form id="form_filter">
                  <div class="row">

                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_303.formulations') }}</label>
                      <select name="formulation_id" class="form-control select2 select_product_id">
                        <option value="">--------</option>
                        @foreach ($formulations as $formulation)
                        <option @selected(request('formulation_id')==$formulation->id) value="{{ $formulation->id }}">
                          {{ $formulation->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_303.categories') }}</label>
                      <select name="category_id" class="form-control select2 select_category_id">
                        <option value="">--------</option>
                        @foreach ($categories as $category)
                        <option @selected(request('category_id')==$category->id) value="{{ $category->id }}">
                          {{ $category->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_301.Sub categories') }}</label>
                      <select name="sub_category_id" class="form-control select2 select_sub_category_id">
                        <option value="">--------</option>
                        @foreach ($sub_categories as $sub_category)
                        <option @selected(request('sub_category_id')==$sub_category->id)
                          value="{{ $sub_category->id }}">
                          {{ $sub_category->name }}</option>
                        @endforeach
                      </select>
                    </div>

                  </div>
                  <div class="row mt-4">
                    <div class="col-12">
                      <button type="submit" class="btn btn-primary me-1 w-100">
                        {{ __('messages.Filter') }}<i class="ti ti-adjustments-horizontal"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div>
      <div class="row">
        <div class="col-12">
          <div>
            <div class="card-body">

              <!-- Table -->
              <div class="table-responsive text-nowrap">
                <table class="table" id="dynamicTable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th class="dynamic-column" data-column="name">{{ __('messages_301.Product name') }}</th>
                      <th class="dynamic-column" data-column="image">{{ __('messages_303.image') }}</th>
                      <th class="dynamic-column" data-column="category">{{ __('messages_303.category name') }}</th>
                      <th class="dynamic-column" data-column="sub_category">{{ __('messages_301.Sub category') }}</th>
                      @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                      <th class="dynamic-column" data-column="actions">{{ __('messages.Actions') }}</th>
                      @endif
                    </tr>
                  </thead>
                  <tbody class="table-border-bottom-0">
                    @php
                    $i = 1;
                    $skipCount = $data->perPage() * $data->currentPage() - $data->perPage();
                    @endphp
                    @include('inc.is_empty_data', [
                    'var_check_empty' => $data,
                    'var_check_empty_rows' => 10,
                    ])

                    @foreach ($data as $item)
                    <tr>
                      <th>{{ $skipCount + $i }}</th>
                      @php $i++ @endphp
                      <td class="dynamic-column" data-column="name">{{ $item->name }}</td>
                      <td class="dynamic-column" data-column="image">
                        <div class="box-img">
                          <img src="{{ $item->image ? asset($item->image) : asset('default-images/category.png') }}">
                        </div>
                      </td>
                      <td class="dynamic-column" data-column="category" style="">
                        <img style="width: 40px; height: 40px; border-radius: 5px; margin-right: 3px;"
                          src="{{ $item->category->image ? asset($item->category->image) : asset('default-images/category.png') }}">
                        {{ $item->category->name }}
                      </td>
                      <td class="dynamic-column" data-column="sub_category">{{ $item->sub_category?->name ??
                        __('messages.notAvailable') }}</td>
                      @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)

                      <td class="dynamic-column" data-column="actions">
                        <div class="dropdown">
                          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                          </button>
                          <div class="dropdown-menu">
                            @can('edit_product')
                            <a class="dropdown-item" href="{{ route('admin.formulations.edit', $item->id) }}"><i
                                class="ti ti-pencil "></i>{{ __('messages.Edit') }}</a>
                            @endcan

                            @can('delete_product')
                            <a data-url="{{ route('admin.formulations.destroy', $item->id) }}"
                              data-text_btn_confirm="{{ __('messages.Confirm') }}"
                              data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="delete"
                              data-message="{{ __('messages.Are you sure you want to delete? You cannot undo it') }}"
                              class="dropdown-item btn-action" href="javascript:void(0);"><i class="ti ti-trash "></i>
                              {{ __('messages.Delete') }}</a>
                            @endcan
                          </div>
                        </div>
                      </td>
                      @endif
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <div class="m-3">
                  {{ $data->appends(Request::except(['_token']))->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

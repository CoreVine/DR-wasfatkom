@extends('layouts.app')
@section('title')
{{ __('messages_303.categories') }}
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
  __('messages.brands') => 'active',
  ],
  ])


  <!-- Basic Bootstrap Table -->
  <div class="card">
    <div class="p-3 d-flex justify-content-between">
      <div class="mx-2">
        <form>
          <div class="input-group">
            <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control"
              placeholder="{{ __('messages_303.name') }}" aria-label="Example text with button addon"
              aria-describedby="button-addon1">
            <button type="submit" class="btn btn-outline-primary waves-effect" type="button" id="button-addon1"><span
                class="d-none d-sm-block">{{ __('messages.Search') }}</span><i
                class="fa-solid fa-magnifying-glass"></i></button>
          </div>
        </form>
      </div>
      <div>
        <div class="btn-group" role="group" aria-label="Basic example">
          {{-- <a href="{{ route('admin.categories.export') }}"
            class="btn btn-outline-primary waves-effect text-primary">{{ __("messages.EXPORT XLS") }}<i
              class="fa-solid fa-file-export"></i></a> --}}
          @can('create_category')
          <a href="{{ route('admin.categories.create') }}"
            class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{
              __('messages.ADD NEW ') }}</span><i class="fa-solid fa-circle-plus "></i></a>
          @endcan
        </div>
      </div>
    </div>

    {{-- OLD TABLE VIEW!!!! --}}
    {{-- <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('messages_303.name') }}</th>
            <th>Logo</th>
            @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
            <th>{{ __('messages.Status') }}</th>
            <th>{{ __('messages.Actions') }}</th>
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
          'var_check_empty_rows' => 4,
          ])

          @foreach ($data as $item)
          <tr>
            <th>{{ $skipCount + $i }}</th>
            @php $i++ @endphp


            <td><a href="{{ route('admin.products.index') }}?category_id={{ $item->id  }}">{{ $item->name }}</a></td>
            <td>
              <div class="box-img">
                <img src="{{ $item->image ? asset($item->image) : asset('default-images/category.png') }}">
              </div>
            </td>
            @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
            <td>
              @if (!$item->is_active)
              <span class="badge bg-label-danger me-1">{{ __('messages.In Active') }}<i
                  class="fa-solid fa-circle-xmark"></i></span>
              @else
              <span style="min-width: 100px" class="badge bg-label-primary me-1">{{ __('messages.Active') }}<i style=""
                  class="fa-solid fa-circle-check"></i></span>
              @endif
            </td>

            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="ti ti-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                  @can('edit_category')
                  <a class="dropdown-item" href="{{ route('admin.categories.edit', $item->id) }}"><i
                      class="ti ti-pencil "></i>{{ __('messages.Edit') }}</a>
                  @endcan

                  @can('active_category')
                  @if (!$item->is_active)
                  <a data-url="{{ route('admin.categories.active_inactive', $item->id) }}"
                    data-text_btn_confirm="{{ __('messages.Confirm') }}"
                    data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
                    data-message="{{ __('messages.Are you sure to activate the article ?') }}"
                    class="dropdown-item btn-action" href="javascript:void(0);"><i class="fa-solid fa-circle-check"></i>
                    {{ __('messages.Activation') }}</a>
                  @else
                  <a data-url="{{ route('admin.categories.active_inactive', $item->id) }}"
                    data-text_btn_confirm="{{ __('messages.Confirm') }}"
                    data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
                    data-message="{{ __('messages.Are you sure you want to deactivate the article ?') }}"
                    class="dropdown-item btn-action" href="javascript:void(0);">
                    <i class="fa-solid fa-circle-xmark"></i>
                    {{ __('messages.Deactivate') }}</a>
                  @endif
                  @endcan

                  @can('delete_category')
                  <a data-url="{{ route('admin.categories.destroy', $item->id) }}"
                    data-text_btn_confirm="{{ __('messages.Confirm') }}"
                    data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="delete"
                    data-message="{{ __('messages.Are you sure you want to delete? You cannot undo it') }}"
                    class="dropdown-item btn-action" href="javascript:void(0);"><i class="ti ti-trash "></i> {{
                    __('messages.Delete') }}</a>
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

    </div> --}}



    <div class="container">
      <div class="row">
        @php
        $i = 1;
        $skipCount = $data->perPage() * $data->currentPage() - $data->perPage();
        @endphp
        @include('inc.is_empty_data', [
        'var_check_empty' => $data,
        'var_check_empty_rows' => 4,
        ])

        @foreach ($data as $item)
        <div class="col-md-3 mb-4">
          <div class="card text-center">
            <div class="card-body">
              <div class="box-img" style="cursor: pointer;"
                onclick="window.location.href = '{{ route('admin.categories.category_products', $item->id) }}';">
                <img src="{{ $item->image ? asset($item->image) : asset('default-images/category.png') }}"
                  class="img-fluid rounded-md" style="width: 80px; height: 80px;">
              </div>
              <h5 class="card-title" style="margin-top: 40px;">{{ $item->name }}</h5>
              @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
              <div class="mt-2">
                @if (!$item->is_active)
                <span class="badge bg-label-danger me-1">{{ __('messages.In Active') }}<i
                    class="fa-solid fa-circle-xmark"></i></span>
                @else
                <span class="badge bg-label-primary me-1">{{ __('messages.Active') }}<i
                    class="fa-solid fa-circle-check"></i></span>
                @endif
              </div>
              <div class="mt-3">
                <div class="dropdown">
                  <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle"
                    data-bs-toggle="dropdown">
                    <i class="ti ti-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu">

                    <a class="dropdown-item" href="{{ route('admin.categories.category_products', $item->id) }}"><i
                        class="ti ti-pencil"></i>{{ __('messages.Products') }}</a>

                    @can('edit_category')
                    <a class="dropdown-item" href="{{ route('admin.categories.edit', $item->id) }}"><i
                        class="ti ti-pencil"></i>{{ __('messages.Edit') }}</a>
                    @endcan

                    @can('active_category')
                    @if (!$item->is_active)
                    <a data-url="{{ route('admin.categories.active_inactive', $item->id) }}"
                      data-text_btn_confirm="{{ __('messages.Confirm') }}"
                      data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
                      data-message="{{ __('messages.Are you sure to activate the article ?') }}"
                      class="dropdown-item btn-action" href="javascript:void(0);"><i
                        class="fa-solid fa-circle-check"></i> {{ __('messages.Activation') }}</a>
                    @else
                    <a data-url="{{ route('admin.categories.active_inactive', $item->id) }}"
                      data-text_btn_confirm="{{ __('messages.Confirm') }}"
                      data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
                      data-message="{{ __('messages.Are you sure you want to deactivate the article ?') }}"
                      class="dropdown-item btn-action" href="javascript:void(0);"><i
                        class="fa-solid fa-circle-xmark"></i> {{ __('messages.Deactivate') }}</a>
                    @endif
                    @endcan

                    @can('delete_category')
                    <a data-url="{{ route('admin.categories.destroy', $item->id) }}"
                      data-text_btn_confirm="{{ __('messages.Confirm') }}"
                      data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="delete"
                      data-message="{{ __('messages.Are you sure you want to delete? You cannot undo it') }}"
                      class="dropdown-item btn-action" href="javascript:void(0);"><i class="ti ti-trash"></i>
                      {{ __('messages.Delete') }}</a>
                    @endcan
                  </div>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="m-3">
        {{ $data->appends(Request::except(['_token']))->links() }}
      </div>
    </div>


  </div>
  <!--/ Basic Bootstrap Table -->

</div>
@endsection
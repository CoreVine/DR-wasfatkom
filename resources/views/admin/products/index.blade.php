@extends('layouts.app')

@section('title')
{{ __('messages_303.products') }}
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
  __('messages_303.products') => 'active',
  ],
  ])

  <!-- Basic Bootstrap Table -->
  <div class="card">
    <div>
      <div class="row">
        <div class="col-12">
          <div class="">
            <div class="card-body">
              <div class="">
                @if (request('key_words'))
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_product_name"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Product name') }}
                  : {{ request('key_words') }}</span>
                @endif
                @if ($product_name)
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_product_id"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Product name') }}
                  : {{ $product_name }}</span>
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

                @if ($supplier_name)
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_supplier_id"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Supplier') }}
                  : {{ $supplier_name }}</span>
                @endif

                @if (request('qty_low'))
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_qty_low"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Qty low') }}
                  : {{ request('qty_low') }}</span>
                @endif

                @if (request('qty_high'))
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_qty_high"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Qty high') }}
                  : {{ request('qty_high') }}</span>
                @endif

                @if (request('sale_qty_low'))
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_sale_qty_low"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Qty sale low') }}
                  : {{ request('sale_qty_low') }}</span>
                @endif

                @if (request('sale_qty_high'))
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_sale_qty_high"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Qty sale high') }}
                  : {{ request('sale_qty_high') }}</span>
                @endif

                @if (request('remain_qty_low'))
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_remain_qty_low"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Qty remain low') }}
                  : {{ request('remain_qty_low') }}</span>
                @endif

                @if (request('remain_qty_high'))
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_remain_qty_high"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages_301.Qty remain high') }}
                  : {{ request('remain_qty_high') }}</span>
                @endif

                @if (request('from_date'))
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_from_date"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages.From date') }}
                  : {{ request('from_date') }}</span>
                @endif

                @if (request('to_date'))
                <span class="badge bg-label-success mx-2 mt-3"><i data-input_name="select_to_date"
                    class="ti ti-x cursor-pointer btn_remove_filter"></i>{{ __('messages.To date') }}
                  : {{ request('to_date') }}</span>
                @endif

              </div>
              <div class="col-12 col-md-12 mt-3">
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
              <div class="d-flex justify-content-between mt-2 mb-2 flex-wrap">

                <!-- Left Section: Buttons for Filters and Trash -->
                <div class="d-flex gap-2 mb-2 mb-md-0">
                  <a class="btn btn-primary me-1" data-bs-toggle="collapse" href="#collapseExample" role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                    <i class="ti ti-adjustments-horizontal"></i>
                  </a>
                  @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                  <a class="btn btn-primary me-1" href="{{ route('admin.products.trashed') }}" role="button">
                    <i class="ti ti-trash"></i>
                    {{ __('messages.Trashed Products') }}
                  </a>
                  @endif
                </div>

                <!-- Right Section: Export, Add New, Delete All, and Import -->
                <div class="btn-group d-flex flex-wrap justify-content-end" role="group" aria-label="Basic example">

                  <!-- Export Button -->
                  <a href="{{ route('admin.products.export', request()->query()).$request_filter }}"
                    class="btn btn-outline-primary waves-effect text-primary">
                    <span class="d-none d-sm-block">{{ __('messages.EXPORT XLS') }}</span>
                    <i class="fa-solid fa-file-export"></i>
                  </a>

                  <!-- Add New Product Button (Only for Authorized Users) -->
                  @can('create_product')
                  <a href="{{ route('admin.products.create') }}"
                    class="btn btn-outline-primary waves-effect text-primary">
                    <span class="d-none d-sm-block">{{ __("messages.ADD NEW ") }}</span>
                    <i class="fa-solid fa-circle-plus"></i>
                  </a>
                  @endcan

                  <!-- Delete All Button (Only for Admin Users) -->
                  @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                  <button type="button" class="btn btn-outline-primary waves-effect" data-bs-toggle="modal"
                    data-bs-target="#deleteAllModal">
                    {{ __('messages.Delete All') }}
                    <i class="fa-solid fa-trash"></i>
                  </button>

                  <!-- Delete All Modal -->
                  <div class="modal fade" id="deleteAllModal" tabindex="-1" aria-labelledby="deleteAllModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="deleteAllModalLabel">{{ __('messages.Confirm Action') }}</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          {{ __('messages.Are you sure you want to delete all products? This action can be reversed.')
                          }}
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{
                            __('messages.Cancel') }}</button>
                          <form action="{{ route('admin.products.delete_all') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('messages.Confirm Delete') }}</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif

                  <!-- Import Button (Only for Admin Users) -->
                  @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                  <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data"
                    style="position: relative; display: flex;">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary waves-effect text-primary">
                      {{ __('messages.Import') }}
                      <i class="fa-solid fa-file-import"></i>
                    </button>
                    <div
                      style="position: relative; border: 1px solid #ddd; padding: 7px; margin: 0 4px; border-radius: 5px;">
                      <input style=" z-index: 9999; position: absolute; width: 100%; height: 100%; left: 0; opacity: 0;"
                        type="file" name="file" class="form-control" required>
                      {{ __('messages.Click To Select File') }}
                      <i class="fa-solid fa-file-import"></i>
                    </div>
                    @error('file') <span>{{ $message }}</span> @enderror
                  </form>
                  @endif

                </div>

              </div>

              <div class="collapse border border-dark rounded p-4 mt-2" id="collapseExample">

                <form id="form_filter">
                  <div class="row">

                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_303.products') }}</label>
                      <select name="product_id" class="form-control select2 select_product_id">
                        <option value="">--------</option>
                        @foreach ($products as $product)
                        <option @selected(request('product_id')==$product->id) value="{{ $product->id }}">
                          {{ $product->name }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_301.Suppliers') }}</label>
                      <select name="supplier_id" class="form-control select2 select_supplier_id">
                        <option value="">--------</option>
                        @foreach ($suppliers as $supplier)
                        <option @selected(request('supplier_id')==$supplier->id) value="{{ $supplier->id }}">
                          {{ $supplier->name }}</option>
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
                        {{-- @foreach ($sub_categories as $sub_category)
                        <option @selected(request('sub_category_id')==$sub_category->id)
                          value="{{ $sub_category->id }}">
                          {{ $sub_category->name }}</option>
                        @endforeach --}}
                      </select>
                    </div>


                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_301.Qty low') }}</label>
                      <input name="qty_low" type="number" value="{{ request('qty_low') }}"
                        class="form-control  select_qty_low">
                    </div>

                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_301.Qty high') }}</label>
                      <input name="qty_high" type="number" value="{{ request('qty_high') }}"
                        class="form-control  select_qty_high">
                    </div>


                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_301.Qty sale low') }}</label>
                      <input name="sale_qty_low" type="number" value="{{ request('sale_qty_low') }}"
                        class="form-control  select_sale_qty_low">
                    </div>

                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_301.Qty sale high') }}</label>
                      <input name="sale_qty_high" type="number" value="{{ request('sale_qty_high') }}"
                        class="form-control  select_sale_qty_high">
                    </div>


                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_301.Qty remain low') }}</label>
                      <input name="remain_qty_low" type="number" value="{{ request('remain_qty_low') }}"
                        class="form-control  select_remain_qty_low">
                    </div>

                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages_301.Qty remain high') }}</label>
                      <input name="remain_qty_high" type="number" value="{{ request('remain_qty_high') }}"
                        class="form-control  select_remain_qty_high">
                    </div>





                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages.From date') }}</label>
                      <input type="date" value="{{ request('from_date') }}" class="form-control  select_from_date"
                        name="from_date">
                    </div>
                    <div class=" col-12 col-md-6 mt-4">
                      <label class="mb-1">{{ __('messages.To date') }}</label>
                      <input type="date" value="{{ request('to_date') }}" class="form-control  select_to_date"
                        name="to_date">
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
              <!-- Column Selection -->
              <div class="row ">
                <div class="col-md-10">
                  <label for="columnSelect">Select Columns to Display:</label>
                  <select id="columnSelect" class="form-select" multiple>
                    <option value="name" selected>{{ __('messages_301.Product name') }}</option>
                    <option value="image" selected>{{ __('messages_303.image') }}</option>
                    <option value="category" selected>{{ __('messages_303.category name') }}</option>
                    <option value="sub_category" selected>{{ __('messages_301.Sub category') }}</option>
                    <option value="favorite" selected>{{ __('messages.Favorite') }}</option>
                    @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                    <option value="supplier" selected>{{ __('messages_301.Supplier') }}</option>
                    <option value="qty" selected>{{ __('messages.Qty') }}</option>
                    <option value="sale_qty" selected>{{ __('messages_301.Qty sale') }}</option>
                    <option value="remain_qty" selected>{{ __('messages_301.Qty remain') }}</option>
                    <option value="status" selected>{{ __('messages.Status') }}</option>
                    <option value="actions" selected>{{ __('messages.Actions') }}</option>
                    @endif
                  </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                  <button id="applyColumnsBtn" class="btn btn-primary">
                    <i class="ti ti-check"></i> Apply Changes
                  </button>
                </div>
              </div>

              <!-- Table -->
              <div class="table-responsive text-nowrap ">
                <table class="table" id="dynamicTable">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th class="dynamic-column" data-column="name">{{ __('messages_301.Product name') }}</th>
                      <th class="dynamic-column" data-column="image">{{ __('messages_303.image') }}</th>
                      <th class="dynamic-column" data-column="category">{{ __('messages_303.category name') }}</th>
                      <th class="dynamic-column" data-column="sub_category">{{ __('messages_301.Sub category') }}</th>
                      <th class="dynamic-column" data-column="favorite">{{ __('messages.Favorite') }}</th>
                      @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                      <th class="dynamic-column" data-column="supplier">{{ __('messages_301.Supplier') }}</th>
                      <th class="dynamic-column" data-column="qty">{{ __('messages.Qty') }}</th>
                      <th class="dynamic-column" data-column="sale_qty">{{ __('messages_301.Qty sale') }}</th>
                      <th class="dynamic-column" data-column="remain_qty">{{ __('messages_301.Qty remain') }}</th>
                      <th class="dynamic-column" data-column="status">{{ __('messages.Status') }}</th>
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
                      <td class="dynamic-column" data-column="category">{{ $item->category->name }}</td>
                      <td class="dynamic-column" data-column="sub_category">{{ $item->sub_category?->name ??
                        __('messages.notAvailable') }}</td>
                      <td class="dynamic-column" data-column="favorite">
                        <i class="fa-{{ in_array($item->id, $favorites) ? 'solid' : 'regular' }} fa-heart cursor-pointer btn_add_or_remove_item_from_favorite icon_favorite"
                          style="background-color: #645cca; padding: 10px; border-radius: 5px;"
                          data-product_id="{{ $item->id }}"></i>
                      </td>

                      @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                      <td class="dynamic-column" data-column="supplier">{{ $item->supplier?->name }}</td>
                      <td class="dynamic-column" data-column="qty">{{ $item->qty }}</td>
                      <td class="dynamic-column" data-column="sale_qty">{{ $item->sale_qty }}</td>
                      <td class="dynamic-column" data-column="remain_qty">{{ $item->remain_qty }}</td>
                      <td class="dynamic-column" data-column="status">
                        @if (auth()->user()->hasPermissionTo('edit_product'))
                        <form action="{{ route('admin.products.active_inactive', $item->id) }}" method="POST"
                          id="active_product">
                          @csrf
                          <div class="col-sm-6">
                            <label class="switch switch-success">
                              <input type="checkbox" class="switch-input" name="is_active" id="check_active"
                                @checked(old('is_active')=='on' || $item->is_active == '1') />
                              <span class="switch-toggle-slider">
                                <span class="switch-on">
                                  <i class="ti ti-check"></i>
                                </span>
                                <span class="switch-off">
                                  <i class="ti ti-x"></i>
                                </span>
                              </span>
                            </label>
                          </div>
                        </form>
                        @else
                        @if ($item->is_active)
                        {{ __('messages.Active') }}
                        @else
                        {{ __('messages.In Active') }}
                        @endif
                        @endif
                      </td>
                      <td class="dynamic-column" data-column="actions">
                        <div class="dropdown">
                          <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical"></i>
                          </button>
                          <div class="dropdown-menu">
                            @can('edit_product')
                            <a class="dropdown-item" href="{{ route('admin.products.edit', $item->id) }}"><i
                                class="ti ti-pencil "></i>{{ __('messages.Edit') }}</a>
                            @endcan

                            @can('delete_product')
                            <a data-url="{{ route('admin.products.destroy', $item->id) }}"
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

@section('script')
@include('admin.invoices.scripts.add_and_remove_favorite')
<script>
  $(document).ready(function() {
      // Apply Column Changes
      $('#applyColumnsBtn').click(function() {
        // Hide all dynamic columns
        $('.dynamic-column').hide();

        // Show selected columns
        $('#columnSelect').val().forEach(function(column) {
          $(`.dynamic-column[data-column="${column}"]`).show();
        });
      });

      // Initialize all columns as visible by default
      $('#columnSelect').val(['name', 'image', 'category', 'sub_category', 'favorite', 'supplier', 'qty', 'sale_qty', 'remain_qty', 'expire_date', 'status', 'actions']);
      $('#applyColumnsBtn').click(); // Trigger the button click to apply default visibility
    });
</script>
@endsection
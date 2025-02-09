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
                        <div class=" mb-4">
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
                                        <span class="badge bg-label-success mx-2 mt-3"><i
                                                data-input_name="select_sub_category_id"
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
                                        <span class="badge bg-label-success mx-2 mt-3"><i
                                                data-input_name="select_remain_qty_high"
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
                                <div class="col-12 col-md-6 mt-3">
                                    <form>
                                        <div class="input-group">
                                            <input value="{{ request('key_words') }}" name="key_words" type="text" class="form-control"
                                                placeholder="{{ __('messages_303.name') }}" aria-label="Example text with button addon"
                                                aria-describedby="button-addon1">
                                            <button type="submit" class="btn btn-outline-primary waves-effect" type="button"
                                                id="button-addon1"><span class="d-none d-sm-block">{{ __("messages.Search") }}</span><i class="fa-solid fa-magnifying-glass"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <p class="demo-inline-spacing">
                                <div class="p-3 d-flex justify-content-between">
                                    <a class="btn btn-primary me-1" data-bs-toggle="collapse" href="#collapseExample"
                                        role="button" aria-expanded="false" aria-controls="collapseExample">
                                        <i class="ti ti-adjustments-horizontal"></i>
                                    </a>
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('admin.products.export').$request_filter }}"
                                            class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{ __('messages.EXPORT XLS') }}</span><i
                                                class="fa-solid fa-file-export"></i></a>

                                        @can('create_product')
                                            <a href="{{ route('admin.products.create') }}"
                                                class="btn btn-outline-primary waves-effect text-primary"><span class="d-none d-sm-block">{{ __("messages.ADD NEW ") }}</span><i class="fa-solid fa-circle-plus "></i></a>
                                        @endcan
                                    </div>
                                </div>

                                </p>

                                <div class="collapse border border-dark rounded p-4" id="collapseExample">

                                    <form id="form_filter">
                                        <div class="row">

                                            <div class=" col-12 col-md-6 mt-4">
                                                <label class="mb-1">{{ __('messages_303.products') }}</label>
                                                <select name="product_id" class="form-control select2 select_product_id">
                                                    <option value="">--------</option>
                                                    @foreach ($products as $product)
                                                        <option @selected(request('product_id') == $product->id) value="{{ $product->id }}">
                                                            {{ $product->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class=" col-12 col-md-6 mt-4">
                                                <label class="mb-1">{{ __('messages_301.Suppliers') }}</label>
                                                <select name="supplier_id" class="form-control select2 select_supplier_id">
                                                    <option value="">--------</option>
                                                    @foreach ($suppliers as $supplier)
                                                        <option @selected(request('supplier_id') == $supplier->id) value="{{ $supplier->id }}">
                                                            {{ $supplier->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class=" col-12 col-md-6 mt-4">
                                                <label class="mb-1">{{ __('messages_303.categories') }}</label>
                                                <select name="category_id" class="form-control select2 select_category_id">
                                                    <option value="">--------</option>
                                                    @foreach ($categories as $category)
                                                        <option @selected(request('category_id') == $category->id) value="{{ $category->id }}">
                                                            {{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class=" col-12 col-md-6 mt-4">
                                                <label class="mb-1">{{ __('messages_301.Sub categories') }}</label>
                                                <select name="sub_category_id"
                                                    class="form-control select2 select_sub_category_id">
                                                    <option value="">--------</option>
                                                    {{-- @foreach ($sub_categories as $sub_category)
                                                        <option @selected(request('sub_category_id') == $sub_category->id)
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
                                                <input name="sale_qty_low" type="number"
                                                    value="{{ request('sale_qty_low') }}"
                                                    class="form-control  select_sale_qty_low">
                                            </div>

                                            <div class=" col-12 col-md-6 mt-4">
                                                <label class="mb-1">{{ __('messages_301.Qty sale high') }}</label>
                                                <input name="sale_qty_high" type="number"
                                                    value="{{ request('sale_qty_high') }}"
                                                    class="form-control  select_sale_qty_high">
                                            </div>


                                            <div class=" col-12 col-md-6 mt-4">
                                                <label class="mb-1">{{ __('messages_301.Qty remain low') }}</label>
                                                <input name="remain_qty_low" type="number"
                                                    value="{{ request('remain_qty_low') }}"
                                                    class="form-control  select_remain_qty_low">
                                            </div>

                                            <div class=" col-12 col-md-6 mt-4">
                                                <label class="mb-1">{{ __('messages_301.Qty remain high') }}</label>
                                                <input name="remain_qty_high" type="number"
                                                    value="{{ request('remain_qty_high') }}"
                                                    class="form-control  select_remain_qty_high">
                                            </div>





                                            <div class=" col-12 col-md-6 mt-4">
                                                <label class="mb-1">{{ __('messages.From date') }}</label>
                                                <input type="date" value="{{ request('from_date') }}"
                                                    class="form-control  select_from_date" name="from_date">
                                            </div>
                                            <div class=" col-12 col-md-6 mt-4">
                                                <label class="mb-1">{{ __('messages.To date') }}</label>
                                                <input type="date" value="{{ request('to_date') }}"
                                                    class="form-control  select_to_date" name="to_date">
                                            </div>

                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary me-1 w-100">
                                                    {{ __('messages.Filter') }}<i
                                                        class="ti ti-adjustments-horizontal"></i>
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

            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('messages_301.Product name') }}</th>
                            <th>{{ __('messages_303.image') }}</th>
                            <th>{{ __('messages_303.category name') }}</th>
                            <th>{{ __('messages_301.Sub category') }}</th>
                            <th>{{ __('messages.Favorite') }}</th>
                            @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                            <th>{{ __('messages_301.Supplier') }}</th>
                            <th>{{ __('messages.Qty') }}</th>
                            <th>{{ __('messages_301.Qty sale') }}</th>
                            <th>{{ __('messages_301.Qty remain') }}</th>
                            <th>{{ __('messages_301.Expire date') }}</th>
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
                            'var_check_empty_rows' => 10,
                        ])

                        @foreach ($data as $item)
                            <tr>
                                <th>{{ $skipCount + $i }}</th>
                                @php $i++  @endphp
                                <td>{{ $item->name }}</td>
                                <td>
                                    <div class="box-img">
                                        <img src="{{ $item->image ? asset($item->image) : asset('default-images/category.png') }}">
                                    </div>
                                </td>
                                <td>{{ $item->category->name }}</td>
                                <td>{{ $item->sub_category?->name }}</td>
                                <td>
                                    <i class="fa-{{ in_array($item->id, $favorites) ? 'solid' : 'regular' }} fa-heart cursor-pointer  btn_add_or_remove_item_from_favorite  icon_favorite"
                                        data-product_id="{{ $item->id }}"></i>

                                </td>

                                @if (auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                                <td>{{ $item->supplier?->name }}</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ $item->sale_qty }}</td>
                                <td>{{ $item->remain_qty }}</td>
                                <td>{{ $item->expire_date }}</td>
                                <td>
                                    @if(auth()->user()->hasPermissionTo('edit_product'))
                                    <form action="{{ route('admin.products.active_inactive', $item->id) }}"
                                        method="POST" id="active_product">
                                        @csrf
                                        <div class="col-sm-6">


                                            <label class="switch switch-success">
                                                <input type="checkbox" class="switch-input" name="is_active"
                                                    id="check_active" @checked(old('is_active') == 'on' || $item->is_active == '1') />
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
                                            {{ __("messages.In Active") }}
                                        @endif
                                    @endif



                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="ti ti-dots-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            @can('edit_product')
                                                <a class="dropdown-item"
                                                    href="{{ route('admin.products.edit', $item->id) }}"><i
                                                        class="ti ti-pencil "></i>{{ __('messages.Edit') }}</a>
                                            @endcan

                                            @can('delete_product')
                                                <a data-url="{{ route('admin.products.destroy', $item->id) }}"
                                                    data-text_btn_confirm="{{ __('messages.Confirm') }}"
                                                    data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="delete"
                                                    data-message="{{ __('messages.Are you sure you want to delete? You cannot undo it') }}"
                                                    class="dropdown-item btn-action" href="javascript:void(0);"><i
                                                        class="ti ti-trash "></i> {{ __('messages.Delete') }}</a>
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
        <!--/ Basic Bootstrap Table -->

    </div>
@endsection
@section('script')
    @include('admin.invoices.scripts.add_and_remove_favorite')
    <script>
        $(function() {

            @if (old('category_id'))
                get_sub_categories("{{ old('category_id') }}")
            @endif

            $(".select_category_id").change(function() {
                var category_id = $(this).val();
                get_sub_categories(category_id)
            })

            function get_sub_categories(category_id) {
                var url = "{{ route('admin.get_sub_category_filter') }}?category_id=" + category_id;
                $.ajax({
                    url,
                    success: function(res) {
                        console.log(res)
                        $(".select_sub_category_id").html(res)

                        var old_sub_category_id = "{{ old('sub_category_id') }}";
                        if (old_sub_category_id) {
                            $(".select_sub_category_id").val(old_sub_category_id);
                        }
                    }
                })
            }



            $('.select2').select2();


            $(".btn_remove_filter").click(function() {
                var input_name = $(this).data('input_name')
                $(`.${input_name}`).html(null)
                $(`.${input_name}`).val(null)
                $('#form_filter').submit()
            })
        })
    </script>
    <script>
        $(document).ready(function() {
            $('#check_active').on('click', function() {
                $('#active_product').submit();
            })
        })
    </script>
@endsection

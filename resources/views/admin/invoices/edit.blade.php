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
                    __('messages.Edit') => 'active',
                ],
            ])
        @else
            @include('inc.breadcrumb', [
                'breadcrumb_items' => [
                    __('messages.Home') => route('home'),
                    __('messages_301.Recipes') => route('admin.invoices.index'),
                    __('messages.Edit') => 'active',
                ],
            ])
        @endif

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">

            <form id="form_submit" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.invoices.update', $item->id) }}">
                @csrf
                @method('PUT')
                <div class="row invoice-add">
                    <!-- Invoice Add-->
                    <div class="col-lg-9 col-12 mb-lg-0 mb-4">
                        <div class="card invoice-preview-card">
                            <div class="card-body">
                                <div class="row m-sm-4 m-0">
                                    <div class="col-md-7 mb-md-0 mb-4 ps-0">
                                        @include('admin.invoices.logo')
                                    </div>
                                    <div class="col-md-5 mt-4">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <input disabled type="text" class="form-control" placeholder="YYYY-MM-DD"
                                                    value="{{ $item->created_at_format }}" />
                                            </div>
                                            {{-- <div class="col-6">
                                            <input disabled value="#21454" type="text" class="form-control">
                                        </div> --}}

                                        </div>

                                    </div>
                                </div>

                                <hr class="my-3 mx-n4" />
                                <div class="row">
                                    @if (auth()->user()->type->value != 'doctor')

                                        <div class="col-12 col-md-4 mt-2  mt-2">
                                            <label for=""
                                                class="form-label">{{ __('messages.Customer name') }}</label>
                                            <input name="client_name" class="form-control"
                                                value="{{ old('client_name', $item->client_name) }}">
                                        </div>
                                        <div class="col-12 col-md-4 mt-2  mt-2">
                                            <label for=""
                                                class="form-label">{{ __('messages.Location') }}</label>
                                            <input name="client_location" class="form-control" value="{{ old('client_location', $item->client_location) }}">
                                        </div>
                                        <div class="col-12 col-md-4 mt-2  mt-2">
                                            <label for=""
                                                class="form-label">{{ __('messages.Phone Number') }}</label>
                                            <input name="client_mobile" class="form-control"
                                                value="{{ old('client_mobile', $item->client_mobile) }}">
                                        </div>
                                        <div class="col-12 col-md-4  mt-2">
                                            <label for=""
                                                class="form-label">{{ __('messages_303.doctors') }}</label>
                                            <select name="doctor_id" id="select2"
                                                class=" form-select form-control select2" data-allow-clear="true">
                                                <option value="">----</option>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}" @selected(old('doctor_id', $item->doctor_id) == $doctor->id)>
                                                        {{ $doctor->name }} - {{ $doctor->clinic_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12 col-md-4  mt-2">
                                            <label for="" class="form-label">{{ __('messages.Reviewer') }}</label>
                                            <select name="review_id" class=" form-select  form-control select2"
                                                data-allow-clear="true">
                                                <option value="">----</option>
                                                @foreach ($reviewers as $reviewer)
                                                    <option  value="{{ $reviewer->id }}" @selected(old('review_id', $item->review_id) == $reviewer->id  || $reviewers->count() == 1 )>
                                                        {{ $reviewer->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        <div class="col-12 col-md-4  mt-2">
                                            <label for=""
                                                class="form-label">{{ __('messages.Customer name') }}</label>
                                            <input name="client_name" class="form-control"
                                                value="{{ old('client_name', $item->client_name) }}">
                                        </div>
                                        <div class="col-12 col-md-4  mt-2">
                                            <label for=""
                                                class="form-label">{{ __('messages.Location') }}</label>
                                            <input name="client_location" class="form-control" value="{{ old('client_location', $item->client_location) }}">
                                        </div>
                                        <div class="col-12 col-md-4  mt-2">
                                            <label for=""
                                                class="form-label">{{ __('messages.Phone Number') }}</label>
                                            <input name="client_mobile" class="form-control"
                                                value="{{ old('client_mobile', $item->client_mobile) }}">
                                        </div>

                                        <input type="hidden" name="review_id" class="form-control"
                                            value="{{ $item->review_id ?: null }}">

                                        <input type="hidden" name="doctor_id" class="form-control"
                                            value="{{ auth()->id() }}">
                                    @endif
                                </div>
                                <hr class="my-3 mx-n4" />
                                @if (count($item->invoice_items))
                                    <div class="source-item pt-4 px-0 px-sm-4">
                                        <div class="mb-3" data-repeater-list="items">
                                            @foreach ($item->invoice_items as $invoice)
                                                <div class="repeater-wrapper pt-0 pt-md-4 repeater_item" data-repeater-item>
                                                    <div class="d-flex border rounded position-relative pe-0">
                                                        <div class="row w-100 p-3">
                                                            <div class="col-md-8 col-12">
                                                                <label class="my-2">{{ __('messages.Product') }}</label>
                                                                <select name="product_id"
                                                                    class="select2 form-select product_select"
                                                                    data-allow-clear="true">
                                                                    <option data-category="" value="">----</option>
                                                                    @foreach ($products as $product)
                                                                        <option  data-category="{{ $product->sub_category?->name }} {{ $product->name }} {{ $product->category->name }}  {{ $product->barcode }}" data-price="{{ $product->price }}"
                                                                            value="{{ $product->id }}"
                                                                            @selected($invoice->product_id == $product->id)>
                                                                            {{ $product->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 col-12">
                                                                <label class="my-2">{{ __('messages.Price') }}</label>
                                                                <input readonly value="{{ $invoice->price }}"
                                                                    type="text"
                                                                    class="form-control invoice-item-price mb-3 price"
                                                                    name="price" placeholder="" />
                                                            </div>
                                                            <div class="col-md-2 col-12">
                                                                <label class="my-2">{{ __('messages.Qty') }}</label>
                                                                <input name="qty" value="{{ $invoice->qty }}"
                                                                    type="text"
                                                                    class="form-control invoice-item-price mb-3 qty"
                                                                    placeholder="" />
                                                            </div>
                                                            <div class="col-md-2 col-12"
                                                                @if (auth()->user()->type->value != 'admin') style="display: none" @endif>
                                                                <label
                                                                    class="my-2">{{ __('messages.Discount percentage') }}
                                                                    %
                                                                </label>
                                                                <input value="{{ $invoice->discount }}" name="discount"
                                                                    type="text"
                                                                    class="form-control invoice-item-price mb-3 discount"
                                                                    placeholder="" />
                                                            </div>
                                                            <div class="col-md-2 col-12">
                                                                <label class="my-2">{{ __('messages.Total') }}</label>
                                                                <input value="{{ $invoice->total_befor_discount }}"
                                                                    name="total_befor_discount" type="hidden"
                                                                    class="form-control invoice-item-price mb-3 total_befor_discount"
                                                                    placeholder="" />
                                                                <input readonly value="{{ $invoice->total }}"
                                                                    name="total" type="text"
                                                                    class="form-control invoice-item-price mb-3 total"
                                                                    placeholder="" />
                                                            </div>



                                                            <div class="col-12">
                                                                <label class="my-2">{{ __('messages.The use') }}</label>
                                                                <textarea name="the_use" class="form-control" rows="2">{{ $invoice->the_use }}</textarea>
                                                            </div>

                                                        </div>

                                                        <div
                                                            class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                                            <i class="ti ti-x cursor-pointer" data-repeater-delete></i>
                                                            <i class="fa-regular fa-heart cursor-pointer  btn_add_or_remove_item_from_favorite  icon_favorite"
                                                                data-product_id="{{ $invoice->product_id }}"></i>
                                                            <div class="dropdown">

                                                                <i class="d-none fa-solid fa-money-bill ti-xs cursor-pointer more-options-dropdown"
                                                                    role="button" id="dropdownMenuButton"
                                                                    data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                                                    aria-expanded="false">
                                                                </i>
                                                                <div class="dropdown-menu dropdown-menu-end w-px-300 p-3"
                                                                    aria-labelledby="dropdownMenuButton">
                                                                    <div class="row g-3">
                                                                        <div class="col-12">
                                                                            <label for="discountInput"
                                                                                class="form-label">{{ __('messages.Coupon') }}</label>
                                                                            <input value="DSFFTGRET4" type="text"
                                                                                class="form-control" id="discountInput" />
                                                                        </div>


                                                                    </div>
                                                                    <div class="dropdown-divider my-3"></div>

                                                                    <button type="button"
                                                                        class="btn btn-label-primary btn-apply-changes">{{ __('messages.Apply') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row pb-4">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary add_another_product"
                                                    data-repeater-create>{{ __('messages.Add another product') }}<i
                                                        class="fa-solid fa-circle-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (count($item->invoice_packages))
                                    <div class="source-item pt-4 px-0 px-sm-4">
                                        <div class="mb-3" data-repeater-list="packages">
                                            @foreach ($item->invoice_packages as $package_item)
                                                <div class="repeater-wrapper pt-0 pt-md-4 "
                                                    data-repeater-item>
                                                    <div class="d-flex border rounded position-relative pe-0">
                                                        <div class="row w-100 p-3">

                                                            <div class="col-md-8 col-12">
                                                                <label
                                                                    class="my-2">{{ __('messages_301.Package') }}</label>
                                                                <select name="package_id"
                                                                    class="select2 form-select package_select"
                                                                    data-allow-clear="true">
                                                                    <option value="">----</option>
                                                                    @foreach ($packages as $package)
                                                                        <option data-price="{{ $package->price }}"
                                                                            value="{{ $package->id }}"
                                                                            @selected($package_item->package_id == $package->id)>
                                                                            {{ $package->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-2 col-12">
                                                                <label class="my-2">{{ __('messages.Price') }}</label>
                                                                <input readonly value="{{ $package_item->price }}"
                                                                    type="text"
                                                                    class="form-control invoice-package-price mb-3 price"
                                                                    name="price" placeholder="" />
                                                            </div>
                                                            <div class="col-md-2 col-12">
                                                                <label class="my-2">{{ __('messages.Qty') }}</label>
                                                                <input name="qty" value="{{ $package_item->qty }}"
                                                                    type="text"
                                                                    class="form-control invoice-package-price mb-3 qty"
                                                                    placeholder="" />
                                                            </div>
                                                            <div class="col-md-2 col-12">
                                                                <label class="my-2">{{ __('messages.Total') }}</label>
                                                                <input readonly value="{{ $package_item->total }}"
                                                                    name="total" type="text"
                                                                    class="form-control invoice-package-price mb-3 total"
                                                                    placeholder="" />

                                                            </div>
                                                        </div>
                                                        <div
                                                            class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                                            <i class="ti ti-x cursor-pointer" data-repeater-delete></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="row pb-4">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary "
                                                    data-repeater-create>{{ __('messages_301.Add another package') }}<i
                                                        class="fa-solid fa-circle-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="source-item pt-4 px-0 px-sm-4">
                                        <div class="mb-3" data-repeater-list="packages">
                                            <div class="repeater-wrapper  repeater-item   pt-0 pt-md-4 " data-repeater-item>
                                                <div class="d-flex border rounded position-relative pe-0">
                                                    <div class="row w-100 p-3">
                                                        <div class="col-md-8 col-12">
                                                            <label class="my-2">{{ __('messages_301.Package') }}</label>
                                                            <select name="package_id"
                                                                class="select2 form-select package_select"
                                                                data-allow-clear="true">
                                                                <option value="">----</option>
                                                                @foreach ($packages as $package)
                                                                    <option data-price="{{ $package->price }}"
                                                                        value="{{ $package->id }}">{{ $package->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2 col-12">
                                                            <label class="my-2">{{ __('messages.Price') }}</label>
                                                            <input readonly value="" type="text"
                                                                class="form-control invoice-package-price mb-3 price"
                                                                name="price" placeholder="" />
                                                        </div>
                                                        <div class="col-md-2 col-12">
                                                            <label class="my-2">{{ __('messages.Qty') }}</label>
                                                            <input name="qty" value="" type="text"
                                                                class="form-control invoice-package-price mb-3 qty"
                                                                placeholder="" />
                                                        </div>
                                                        <div class="col-md-2 col-12">
                                                            <label class="my-2">{{ __('messages.Total') }}</label>
                                                            <input value="0" name="total" type="number"
                                                                class="form-control invoice-package-price mb-3 total"
                                                                placeholder="" readonly />

                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column align-items-center justify-content-between border-start p-2">
                                                        <i class="ti ti-x cursor-pointer" data-repeater-delete></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row pb-4">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary "
                                                    data-repeater-create>{{ __('messages_301.Add another package') }}<i
                                                        class="fa-solid fa-circle-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <hr class="my-3 mx-n4" />





                                <div class="row">
                                    <div class="col-md-4 col-12 mt-2" @if (auth()->user()->type->value != 'admin') style="display: none" @endif>
                                        <label for=""
                                            class="form-label">{{ __('messages.Doctors commission ( % )') }}</label>
                                        <input name="doctor_commission" value="{{ $item->doctor_commission }}"
                                            class="form-control doctors_commission" type="number">
                                    </div>
                                    <div class="col-md-4 col-12 mt-2">
                                        <label for="" class="form-label">{{ __('messages.Total') }}</label>
                                        <input name="sub_total" readonly class="form-control invoice_sub_total"
                                            value="{{ $item->sub_total }}">
                                    </div>
                                    <div class="col-md-4 col-12 mt-2">
                                        <label for="" class="form-label">{{ __('messages.Discount') }}</label>
                                        <input name="discount" readonly class="form-control invoice_discount"
                                            value="{{ $item->discount }}">
                                    </div>
                                    <div class="col-md-4 col-12 mt-2">
                                        <label for=""
                                            class="form-label">{{ __('messages.Total after discount') }}</label>
                                        <input name="total" readonly class="form-control invoice_total"
                                            value="{{ $item->total }}">
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="note"
                                                class="form-label fw-medium">{{ __('messages.Notes') }}:</label>
                                            <textarea name="notes" class="form-control" rows="2" id="note"
                                                placeholder="{{ __('messages.Invoice note') }}">{{ $item->notes }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <button class="btn btn-primary btn-submit btn_with_load  d-grid w-100 mb-2">
                                            <span class="d-flex align-items-center  justify-content-center text-nowrap"><i
                                                    class="fa-solid fa-paper-plane"></i>{{ __('messages.Send') }}</span>
                                        </button>
                                    </div>
                                    {{-- <div class="col-6">
                                        <button class="btn btn-label-secondary  d-grid w-100 mb-2">
                                            <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                                    class="fa-solid fa-floppy-disk"></i>{{ __('messages.Temporary saving') }}</span>
                                    </div> --}}


                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Invoice Add-->

                    <!-- Invoice Actions -->
                    <div class="col-lg-3 col-12 invoice-actions">
                        <div class="card mb-4">
                            <div class="card-body">
                                {{-- <button type="button" class="btn btn-primary d-grid w-100 mb-2" data-bs-toggle="offcanvas"
                                    data-bs-target="#sendInvoiceOffcanvas">
                                    <span class="d-flex align-items-center justify-content-center text-nowrap"><i
                                            class="fa-solid fa-heart"></i>{{ __('messages.Favorite') }}</span>
                                </button> --}}



                                <span
                                    class="d-flex align-items-center justify-content-center text-nowrap btn btn-primary d-grid w-100 mb-2"><i
                                        class="fa-solid fa-heart"></i>{{ __('messages.Favorite') }}</span>
                                <div class="text-center my-2">
                                    <small
                                        class="text-muted">{{ __('messages.Click on the product image to add it to the invoice') }}</small>
                                </div>
                                <ul class="list-group mb-3" id="container_list_favorite">
                                    @foreach ($favorites as $favorite_item)
                                        <li class="list-group-item  ">

                                            <div class="row">
                                                <div class="col-md-12 mb-3">
                                                    <div class="text-md-end">
                                                        <button type="button"
                                                            data-product_id="{{ $favorite_item->product_id }}"
                                                            class="btn-close btn-pinned btn_add_or_remove_item_from_favorite"
                                                            aria-label="Close"></button>
                                                    </div>
                                                </div>

                                                <div class="cursor-pointer item_product_favorite"
                                                    data-price ="{{ $favorite_item->product->price }}"
                                                    data-id="{{ $favorite_item->product_id }}"
                                                    id="item_product_favorite_{{ $favorite_item->product_id }}">
                                                    <div class="col-12 text-center ">
                                                        <img width="100" height="80"
                                                            src="{{ asset($favorite_item->product->image) }}"
                                                            alt="google home" class="">
                                                    </div>
                                                    <div class="col-12 text-center">
                                                        {{ $favorite_item->product->name }}
                                                    </div>
                                                    <div class="col-12 text-center">
                                                        {{ $favorite_item->product->price }} SAR
                                                    </div>
                                                </div>

                                            </div>
                                        </li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>

                    </div>
                    <!-- /Invoice Actions -->

                </div>

            </form>
            <!-- Offcanvas -->
            <!-- Send Invoice Sidebar -->
            <div class="offcanvas offcanvas-end" id="sendInvoiceOffcanvas" aria-hidden="true">
                <div class="offcanvas-header my-1">
                    <h5 class="offcanvas-title">Send Invoice</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body pt-0 flex-grow-1">
                    <form>
                        <div class="mb-3">
                            <label for="invoice-from" class="form-label">From</label>
                            <input type="text" class="form-control" id="invoice-from" value="shelbyComapny@email.com"
                                placeholder="company@email.com" />
                        </div>
                        <div class="mb-3">
                            <label for="invoice-to" class="form-label">To</label>
                            <input type="text" class="form-control" id="invoice-to" value="qConsolidated@email.com"
                                placeholder="company@email.com" />
                        </div>
                        <div class="mb-3">
                            <label for="invoice-subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="invoice-subject"
                                value="Invoice of purchased Admin Templates" placeholder="Invoice regarding goods" />
                        </div>
                        <div class="mb-3">
                            <label for="invoice-message" class="form-label">Message</label>
                            <textarea class="form-control" name="invoice-message" id="invoice-message" cols="3" rows="8">
    Dear Queen Consolidated,
    Thank you for your business, always a pleasure to work with you!
    We have generated a new invoice in the amount of $95.59
    We would appreciate payment of this invoice by 05/11/2021</textarea>
                        </div>
                        <div class="mb-4">
                            <span class="badge bg-label-primary">
                                <i class="ti ti-link ti-xs"></i>
                                <span class="align-middle">Invoice Attached</span>
                            </span>
                        </div>
                        <div class="mb-3 d-flex flex-wrap">
                            <button type="button" class="btn btn-primary me-3" data-bs-dismiss="offcanvas">Send</button>
                            <button type="button" class="btn btn-label-secondary"
                                data-bs-dismiss="offcanvas">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /Send Invoice Sidebar -->

            <!-- /Offcanvas -->
        </div>
        <!-- / Content -->


    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
    <script src="{{ asset('assets/js/offcanvas-send-invoice.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/js/app-invoice-add.js') }}"></script>
    @include('inc.script_submit')

    <script>
        $(function() {
            $('.repeater-item:first').hide();
            $('.select2').select2({
                placeholder: 'Select a product',
                matcher: function(params, data) {
                    if ($.trim(params.term) === '') {
                        return data;
                    }

                    if (typeof data.text === 'undefined') {
                        return null;
                    }


                    var category = $(data.element).data('category').toLowerCase();
                    var term = params.term.toLowerCase();

                    if (category.includes(term)) {
                        return data;
                    }

                    return null;
                }
            });
        })
    </script>
    @include('admin.invoices.scripts.add_and_remove_favorite')
    @include('admin.invoices.scripts.favorite')
    @include('admin.invoices.scripts.calc')
    @include('admin.invoices.scripts.main')
@endsection

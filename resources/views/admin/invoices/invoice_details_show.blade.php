<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row invoice-preview">
        <!-- Invoice -->
        <div class="col-xl-12 col-md-12 col-12 mb-md-0 mb-4">
            <div class="card invoice-preview-card">
                <div class="card-body">
                    <div class="row m-sm-4 m-0">

                        <div
                            class="d-flex flex-column flex-md-row justify-content-end align-items-start align-items-md-center mb-3">
                            @if ($is_admin)
                            <a class="btn btn-outline-success mx-2"
                                href="{{ route('admin.invoices.pdf', $item->id) }}"><i class="ti ti-file "></i>PDF</a>
                            <div class="d-flex flex-column justify-content-end gap-2 gap-sm-0">

                                @can('review_invoice')
                                <div class="col-12">
                                    @include('admin.invoices.status_selected' , ['invoice_item_status'=>$item->status ,
                                    'invoice_item_id'=>$item->id])
                                </div>
                                @else
                                <h5 class="mb-1 mt-3 d-flex flex-wrap gap-2 align-items-end">

                                    {{ __('messages.Status') }} <span
                                        class="badge bg-label-{{ App\Http\Helpers\HelperApp::get_color_status($item->status) }} me-1">{{
                                        __('messages.' . $item->status) }}</span>
                                </h5>
                                @endcan
                            </div>
                            {{-- <div class="d-flex align-content-center flex-wrap gap-2">

                                @can('send_invoice')
                                <a data-url="{{ route('admin.invoices.under_delivery', $item->id) }}"
                                    data-text_btn_confirm="{{ __('messages.Confirm') }}"
                                    data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
                                    data-message="{{ __('messages_301.Are you sure to send the invoice ?') }}"
                                    class="btn btn-action btn-success me-1 " href="javascript:void(0);"><i
                                        class="fa-solid fa-circle-check"></i>
                                    {{ __('messages.Under Delivery') }}</a>


                                <a data-url="{{ route('admin.invoices.send_status', $item->id) }}"
                                    data-text_btn_confirm="{{ __('messages.Confirm') }}"
                                    data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
                                    data-message="{{ __('messages_301.Are you sure to send the invoice ?') }}"
                                    class="btn btn-action btn-success me-1 " href="javascript:void(0);"><i
                                        class="fa-solid fa-circle-check"></i>
                                    {{ __('messages_301.Send') }}</a>



                                @endcan






                                @can('cancel_invoice')
                                <a data-url="{{ route('admin.invoices.cancel_status', $item->id) }}"
                                    data-text_btn_confirm="{{ __('messages.Confirm') }}"
                                    data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
                                    data-message="{{ __('messages_301.Are you sure to cancel the invoice ?') }}"
                                    class="btn btn-action btn-danger me-1 " href="javascript:void(0);"><i
                                        class="fa-solid fa-circle-xmark"></i>
                                    {{ __('messages_301.Cancel') }}</a>
                                @endcan


                            </div> --}}
                            @endif
                        </div>
                        <div class="col-md-7 mb-md-0 mb-4 ps-0">
                            @include('admin.invoices.logo')
                        </div>


                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-6">
                                    <input disabled type="text" class="form-control" placeholder="YYYY-MM-DD"
                                        value="{{ $item->created_at_format }}" />
                                </div>
                                <div class="col-6">
                                    <input disabled value="#{{ $item->invoice_num }}" type="text" class="form-control">
                                </div>

                            </div>

                        </div>

                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <img width="100" height="100"
                                    src="{{ asset('uploads/invoice-qr').'/' . $item->id . '.png' }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                <hr class="my-0" />
                <div class="card-body">
                    <div class="row p-sm-3 p-0">
                        <div class="col-xl-4 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                            <h6 class="mb-3">{{ __('messages_301.Customer') }}</h6>
                            <p class="mb-1">{{ __('messages.Name') }} : {{ $item->client_name }}</p>
                            <p class="mb-1">{{ __('messages.Phone Number') }} : {{ $item->client_mobile }}</p>

                        </div>
                        @if ($item->reviewer)
                        <div class="col-xl-4 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                            <h6 class="mb-3">{{ __('messages.Reviewer') }}</h6>
                            <p class="mb-1">{{ __('messages.Name') }} : {{ $item->reviewer->name }}</p>
                            <p class="mb-1">{{ __('messages.Email') }} : {{ $item->reviewer->email }}</p>
                        </div>
                        @endif

                        <div class="col-xl-4 col-md-12 col-sm-5 col-12 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                            <h6 class="mb-3">{{ __('messages.Doctor') }}</h6>
                            <p class="mb-1">{{ __('messages.Name') }} : {{ $item->doctor->name }}</p>
                            <p class="mb-1">{{ __('messages.Email') }} : {{ $item->doctor->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="table-responsive border-top">
                    <table class="table m-0">
                        <thead>
                            <tr>
                                <th>{{ __('messages.Product') }}</th>
                                <th>{{ __('messages.The use') }}</th>
                                <th>{{ __('messages.Price') }}</th>
                                <th>{{ __('messages.Qty') }}</th>
                                <th>{{ __('messages.Total') }}</th>
                                <th>{{ __('messages.Discount percentage') }}</th>
                                <th>{{ __('messages.Total after discount') }}</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item->invoice_items as $item_data)
                            <tr>
                                <td>{{ $item_data->product->name }}</td>
                                <td>{{ $item_data->the_use }}</td>
                                <td>{{ $item_data->price }}</td>
                                <td>{{ $item_data->qty }}</td>
                                <td>{{ $item_data->total_befor_discount }}</td>
                                <td>{{ $item_data->discount }}</td>
                                <td style="font-weight: bold">{{ $item_data->total }}</td>
                            </tr>
                            @endforeach
                            @if (count($item->invoice_packages))
                            @foreach ($item->invoice_packages as $item_data)
                            <tr>
                                <td>{{ $item_data->package->name }}</td>
                                <td>{{ '---' }}</td>
                                <td>{{ $item_data->price }}</td>
                                <td>{{ $item_data->qty }}</td>
                                <td>{{ $item_data->total }}</td>
                                <td>{{ '0.00' }}</td>
                                <td style="font-weight: bold">{{ $item_data->total }}</td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>


                <div class="row justify-content-end mt-5 mb-5">
                    <div class="col-4">
                        <div class="mb-2">
                            {{ __('messages.Total') }} : {{ $item->sub_total }}
                        </div>

                        <div class="mb-1">
                            {{ __('messages.Discount') }} : {{ $item->discount }}
                        </div>
                        @if ($item->coupon)
                        <div class="mb-1">
                            {{ __('messages.Coupon') }} : {{ $item->coupon->code }}
                        </div>
                        <div class="mb-1">
                            {{ __('messages.Discount code value') }} : {{ $item->coupon_value }}
                        </div>
                        @endif
                        <div class="mb-1">
                            {{ __('messages.Tax') }} : {{ $item->tax_value }}
                        </div>
                        <div class="coupon_discount">
                            {{ __('messages.Total before tax') }} : <span class="h5">{{ $item->total - $item->tax_value
                                }}</span>
                        </div>
                        <div class="coupon_discount">
                            {{ __('messages.Total') }} : <span class="h5">{{ $item->total }}</span>
                        </div>

                        <div class="coupon_total" style="display: none">

                        </div>

                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 p-5">
                        {{ __('messages.Notes') }} : <br>
                        {{ $item->notes ?? 'N/A' }}
                    </div>
                </div>

                @if ($is_admin)

                @can('review_invoice')
                @if ($item->reviewer && $item->status == \App\Enums\OrderStatusEnum::Draft->value)
                <div class="d-flex justify-content-end mb-3">
                    <button type="button" data-url="{{ route('admin.invoices.review', $item->id) }}"
                        data-text_btn_confirm="{{ __('messages.Confirm') }}"
                        data-text_btn_cancel="{{ __('messages.Cancel') }}" data-method="post"
                        data-message="{{ __('messages.Reviewed and obtained client copy') }}"
                        class="btn btn-primary me-sm-3 me-1 waves-effect waves-light btn-action">{{
                        __('messages.Reviewed and obtained client copy') }}</button>
                </div>
                @endif
                @endcan

                @if ($item->reviewer && $item->status == \App\Enums\OrderStatusEnum::Done->value)
                <div class="d-flex justify-content-end mb-3">
                    <div class="col-10">
                        <form action="{{ route('admin.invoices.send_invoice' , $item->id) }}" method="POST">
                            <div class="d-flex">
                                <input readonly name="link_payment" id="input_link" type="text" class="form-control"
                                    value="{{ route('invoices.show', $item->id) }}">

                                <input name="coupon" placeholder="Coupone Code" id="input_link" type="text"
                                    class="form-control mx-2" value="">

                                <button type="button" onclick="myFunction()"
                                    class="btn btn-primary me-sm-3 me-1 waves-effect waves-light mx-2">Copy <i
                                        class="ti ti-copy"></i> </button>
                                {{-- <form action="{{ route('admin.invoices.send_invoice' , $item->id) }}"
                                    method="POST"> --}}
                                    @csrf

                                    <button type="submit"
                                        class="btn btn-success me-sm-3 me-1 waves-effect waves-light ">Send <i
                                            class="fa-brands fa-whatsapp"></i> </button>
                                    {{--
                                </form> --}}

                            </div>
                        </form>
                        {{-- <div class="d-flex">
                            <input readonly id="input_link" type="text" class="form-control"
                                value="{{ route('invoices.show', $item->id) }}">

                            <button type="button" onclick="myFunction()"
                                class="btn btn-primary me-sm-3 me-1 waves-effect waves-light mx-2">Copy <i
                                    class="ti ti-copy"></i> </button>
                            <form action="{{ route('admin.invoices.send_invoice' , $item->id) }}" method="POST">
                                @csrf

                                <button type="submit"
                                    class="btn btn-success me-sm-3 me-1 waves-effect waves-light ">Send <i
                                        class="fa-brands fa-whatsapp"></i> </button>
                            </form>

                        </div> --}}
                    </div>
                </div>
                @endif


                @if (!$item->reviewer && auth()->user()->type == \App\Enums\UserRoleEnum::Admin)
                <div class="d-flex justify-content-end mb-3 p-2">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <span class="alert-icon text-warning me-2">
                            <i class="ti ti-bell ti-xs"></i>
                        </span>
                        {{ __('messages.Assign it to references and review it to be able to obtain the client’s copy')
                        }}
                    </div>

                </div>
                @endif

                @else

                @if ($item->status == "paid")
                <div class="p-5">
                    <span class="bg bg-success p-2 rounded" style="color: #fff"> الفاتورة رقم
                        {{ $item->invoice_num }}
                        مدفوعة
                    </span>
                </div>
                @else
                <div class="row g-3">
                    <form class="card-body" id="coupon_check" action="{{ route('payment.create') }}" method="POST"
                        enctype="multipart/form-data">
                        <div class="col-md-7 mb-md-0  ps-0">
                            <h5>
                                {{ __('messages.Coupon') }}
                            </h5>
                        </div>
                        @csrf

                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="d-flex justify-content-start">
                            {{-- <div class="row g-3 input-group justify-contet-between"> --}}
                                <div class="col-md-4">
                                    <label class="form-label" for="input-code">{{ __('messages_301.Code') }}</label>
                                    <input type="text" @readonly( $item->coupon )
                                    class="form-control @error('code') is-invalid @enderror coupon_inp"
                                    name="code" id="coupon" value="{{ old('code' , $item->coupon?->code) }}" />
                                    @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-2 pt-4">
                                    <button
                                        class="btn btn-primary me-sm-3 me-1 btn-check-coupon {{ $item->coupon ? 'd-none' : '' }}">{{
                                        __('messages_301.Check') }}</button>
                                </div>

                            </div>

                            <!-- Form with Tabs -->




                            <div class="row mb-3 mt-5">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="customRadioAddress1">
                                            <input name="payment_type" class="form-check-input" required type="radio"
                                                value="my_fatoora" id="customRadioAddress1">
                                            <span class="custom-option-header mb-2">
                                                <span class="fw-medium text-heading mb-0 px-3 py-1 rounded"
                                                    style="background: #fff">
                                                    @foreach (['apple' , 'mastercard' , 'visa' , 'mada'] as $logo)
                                                    <img @if ($logo !="mada" ) width="30" height="30" @else width="55"
                                                        height="20" @endif
                                                        src="{{ asset('assets/payments-logos/'.$logo.'.png') }}">
                                                    @endforeach
                                                </span>
                                                <span class="badge bg-label-success">
                                                    (
                                                    دفعة واحدة
                                                    )
                                                </span>
                                            </span>
                                            <span class="custom-option-body">
                                                <ul>
                                                    <li>
                                                        ادفع فاتورتك على دفعه واحدة
                                                    </li>
                                                </ul>

                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic checked">
                                        <label class="form-check-label custom-option-content" for="customRadioAddress2">
                                            <input name="payment_type" class="form-check-input" required type="radio"
                                                value="tamara" id="customRadioAddress2">
                                            <span class="custom-option-header mb-2">
                                                <span class="fw-medium text-heading mb-0 rounded px-3 py-1"
                                                    style="background: #fff;width: 186px">
                                                    <img src="{{ asset('assets/payments-logos/tamara.png') }}">
                                                </span>
                                                <span class="badge bg-label-success">
                                                    (
                                                    تقسيط
                                                    )
                                                </span>
                                            </span>
                                            <span class="custom-option-body">
                                                <ul>
                                                    @isset($installments)
                                                    @foreach ($installments['available_payment_labels'] as $installment)
                                                    @if ($installment['payment_type'] == "PAY_BY_INSTALMENTS")

                                                    @endif
                                                    <li>قسم فاتورتك حتى {{ $installment['instalment'] }} دفعات</li>
                                                    @endforeach
                                                    @endisset


                                                </ul>

                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end p-5">
                                <button type="submit" style="color: #fff !important"
                                    class="btn btn-success me-sm-3 me-1 waves-effect waves-light ">Payment process
                                    continues
                                    <i class="fa-solid fa-cart-shopping"></i> </button>
                            </div>


                    </form>

                </div>
                @endif

                @endif


            </div>
        </div>
        <!-- /Invoice -->

        <!-- Invoice Actions -->

        <!-- /Invoice Actions -->
    </div>

    <!-- Offcanvas -->
    <!-- Send Invoice Sidebar -->

</div>


<div class="btn-group" style="min-width: 150px" >
    <button type="button" id="btn_status_invoice_{{ $invoice_item_id }}" class="btn btn-outline-{{ App\Http\Helpers\HelperApp::get_color_status($invoice_item_status) }} dropdown-toggle waves-effect" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('messages.' . $invoice_item_status) }}
    </button>
    <ul class="dropdown-menu" style="">
        @foreach (App\Enums\OrderStatusEnum::values() as $order_status_enum_val)
            @if ($order_status_enum_val != "review")
            <li  data-invoice_id="{{ $invoice_item_id }}" data-old_value="{{ $item->status }}" data-value="{{ $order_status_enum_val }}"  class="{{ $invoice_item_status == $order_status_enum_val ? 'bg bg-primary' : '' }} change_status"><a class="dropdown-item" href="javascript:void(0);">{{ __('messages.' . $order_status_enum_val) }}</a></li>
            @endif
        @endforeach

    </ul>
  </div>

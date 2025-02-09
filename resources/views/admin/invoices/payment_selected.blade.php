

<div class="btn-group" style="min-width: 150px" >
    <button type="button" id="btn_payment_invoice_{{ $invoice_item_id }}" class="btn btn-outline-primary dropdown-toggle waves-effect" data-bs-toggle="dropdown" aria-expanded="false">
        {{ $invoice_item_payment_type  ?  $invoice_item_payment_type : 'Unpaid' }}
    </button>
    <ul class="dropdown-menu" style="">
        @foreach (App\Enums\PaymentTypeEnum::values() as $order_status_enum_val)
            @if ($order_status_enum_val != "review")
            <li  data-invoice_id="{{ $invoice_item_id }}" data-old_value="{{ $item->status }}" data-value="{{ $order_status_enum_val }}"  class="{{ $invoice_item_payment_type == $order_status_enum_val ? 'bg bg-primary' : '' }}  change_payment"><a class="dropdown-item" href="javascript:void(0);">{{  $order_status_enum_val }}</a></li>
            @endif
        @endforeach
    </ul>
  </div>

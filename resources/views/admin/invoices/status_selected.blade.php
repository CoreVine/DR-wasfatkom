<div class="btn-group" style="min-width: 150px">
    <button type="button" id="btn_status_invoice_{{ $invoice_item_id }}" class="btn btn-outline-{{ App\Http\Helpers\HelperApp::get_color_status($invoice_item_status) }} 
        dropdown-toggle waves-effect" data-bs-toggle="dropdown" aria-expanded="false">
        {{ __('messages.' . $invoice_item_status) }}
    </button>
    <ul class="dropdown-menu">
        @foreach (App\Enums\OrderStatusEnum::values() as $order_status_enum_val)
        @if ($order_status_enum_val != "paid")
        @if ($order_status_enum_val != "done")
        <li data-invoice_id="{{ $invoice_item_id }}" data-old_value="{{ $item->status }}"
            data-value="{{ $order_status_enum_val }}"
            class="{{ $invoice_item_status == $order_status_enum_val ? 'bg bg-primary' : '' }} change_status">
            <a class="dropdown-item" href="javascript:void(0);">
                {{ __('messages.' . $order_status_enum_val) }}
            </a>
        </li>
        @endif
        @endif
        @endforeach
    </ul>
</div>

<!-- Cancel Reason Modal -->
<div class="modal fade" id="cancelReasonModal" tabindex="-1" aria-labelledby="cancelReasonModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelReasonModalLabel">Enter Cancel Reason</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cancelForm">
                    <input type="hidden" id="invoice_id">
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">Cancel Reason</label>
                        <textarea class="form-control" id="cancel_reason" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
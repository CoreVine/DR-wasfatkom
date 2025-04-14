<?php

namespace App\Observers;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Enums\OrderStatusEnum;
use App\Http\Helpers\HelperApp;
use App\Models\Invoice;

class InvoiceObserver
{
    /**
     * Handle the Invoice "created" event.
     */
    public function created(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "updated" event.
     */
    public function updated(Invoice $invoice): void
    {



        //
        if ($invoice->status == OrderStatusEnum::Done->value) {
            foreach ($invoice->invoice_items as $item) {
                $product = $item->product;
                $product->sale_qty += $item->qty;
                $product->remain_qty -=  $item->qty;
                $product->save();
            }
        }
    }

    /**
     * Handle the Invoice "deleted" event.
     */
    public function deleted(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "restored" event.
     */
    public function restored(Invoice $invoice): void
    {
        //
    }

    /**
     * Handle the Invoice "force deleted" event.
     */
    public function forceDeleted(Invoice $invoice): void
    {
        //
    }


}

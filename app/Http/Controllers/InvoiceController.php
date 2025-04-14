<?php

namespace App\Http\Controllers;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Enums\PaymentTypeEnum;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Helpers\HelperApp;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ResponseHelper;
use App\Payments\MyFatooraTransaction;
use App\Payments\TamaraTransaction;
use RealRashid\SweetAlert\Facades\Alert;

class InvoiceController extends Controller
{
    //
    public function show(string $id)
    {
        //
        $item = Invoice::with(['doctor', 'reviewer', 'invoice_items.product'])->findOrFail($id);

        HelperApp::make_qr_code($item->id);

        $installments =  $this->get_installments($item);
        // return   $installments['available_payment_labels'];
        app()->setLocale("ar");
        return view('show', compact('item', 'installments'));
    }

    private function get_installments($order)
    {
        $types_payments =  TamaraTransaction::get_types_payments($order);
        return $types_payments;
    }

    public function check_coupon()
    {
        $coupon = request('code');
        $invoice_id = request('invoice_id');
        $check_code = HelperApp::check_coupon_use($coupon, $invoice_id);
        if ($check_code['status'] == false) {
            return ResponseHelper::sendResponseError(null, Response::HTTP_BAD_REQUEST, $check_code['message']);
        }

        $code = $check_code['code'];
        $invoice = $check_code['invoice'];

        $calc_total_after_except_medicine = $this->calc_total_after_except_medicine($invoice, $code->percentage);

        $invoice->coupon_id = $code->id;
        $invoice->total = $calc_total_after_except_medicine['total'];
        $invoice->discount = $calc_total_after_except_medicine['total_discount'];
        $invoice->coupon_value =$calc_total_after_except_medicine['coupon_value'];
        $invoice->save();
        return ResponseHelper::sendResponseSuccess([
            "code" => $code->code,
            "percentage" => $code->percentage,
            'discount' => number_format($calc_total_after_except_medicine['total_discount'], 2),
            'total' => number_format($calc_total_after_except_medicine['total'], 2)
        ], Response::HTTP_OK, __('messages.done successfully'));
    }

    private function calc_total_after_except_medicine($invoice, $percentage)
    {
        $total = $invoice->total;

        foreach ($invoice->invoice_items as $item) {
            if ($item->product->category_id == 2) {
                $total -= $item->total;
            }
        }

        $discount = $total * ($percentage / 100);
        return [
            "coupon_value"=>$discount,
            'total_discount' =>  $invoice->discount + $discount,
            "total" =>  $invoice->total - $discount
        ];
    }


    public function create(Request $request)
    {



        if (!in_array($request->payment_type, ['my_fatoora', 'tamara'])) {
            abort(404);
        }
        $item = Invoice::with(['doctor', 'reviewer', 'invoice_items.product'])->findOrFail($request->id);

        if ($request->payment_type == "my_fatoora") {
            $payment_url  =  MyFatooraTransaction::create($item);
            return redirect($payment_url);
        } else {
            // Alert::toast("قيد العمل على الدفع بنظام التقسيط", "error");
            // return back();

            $payment_url = TamaraTransaction::create($item);
            if (!isset($payment_url['checkout_url'])) {
                if (isset($payment_url['message'])) {
                    Alert::toast($payment_url['message'], "error");
                    return back();
                } else {
                    Alert::toast("Invalid url checkout", "error");
                    return back();
                }
            }
            return redirect($payment_url['checkout_url']);
        }
    }

    public function payment($id)
    {
        $item = Invoice::with(['doctor', 'reviewer', 'invoice_items.product'])->findOrFail($id);
        return MyFatooraTransaction::create($item);
    }


    public function myfatoora_callback()
    {


        // return request();
        $res =   MyFatooraTransaction::check_payment(request('paymentId'));
        $invoice = Invoice::where("payment_order_id", $res['customer_reference'])->first();
        if (!$res['status']) {
            Alert::toast($res['message'], 'error');
            return redirect()->route('invoices.show', $invoice->id);
        } else {

            $invoice->payment_type = PaymentTypeEnum::MyFatoora->value;
            $invoice->save();

            Alert::toast($res['message'], 'success');
            return redirect()->route('invoices.show', $invoice->id);
        }
    }

    public function tamara_callback()
    {
        return request();
    }
}

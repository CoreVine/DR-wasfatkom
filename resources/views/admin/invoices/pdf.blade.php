<!DOCTYPE html>
<html lang="en" moznomarginboxes mozdisallowselectionprint xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif !important;
            -webkit-print-color-adjust: exact !important;
            color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            color: #404040;
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
        }

        @media print {
            @page {
                size: auto;
                margin: 0;
                padding: 0;
            }
        }

        #absolute-element-footer2 {
            position: fixed;
            bottom: 0;
            left: 0;
        }
    </style>
</head>

<body style="color: #404040; margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
        <tr>
            <td style="padding-top: 20px; padding-left: 30px; padding-right: 30px;">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td style="padding-top: 12px; padding-bottom: 12px;">
                            <img src="assets/img/logo.png" alt="logo" height="60" draggable="false" /><br>
                            <p>Mobile: {{ App\Http\Helpers\HelperSetting::get_value('communication_mobile') }}</p><br>
                            <p>شركة الندى الطبية</p><br>
                            <p>VAT Registration number: 310100358600003</p><br>
                            <p>Location: {{ App\Http\Helpers\HelperSetting::get_value('communication_address') }}</p>
                        </td>
                        <td style="padding-top: 12px; padding-bottom: 12px;">
                            <img width="100" height="100" src="uploads/invoice-qr/{{ $item->id }}.png">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 30px; padding-right: 30px;">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td
                            style="padding-top: 12px; padding-bottom: 12px; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                            <p style="font-family: 'Poppins', sans-serif;">
                                <strong>Date:</strong> {{ $item->created_at_format }}
                            </p>
                        </td>
                        <td
                            style="padding-top: 12px; padding-bottom: 12px; border-top: 1px solid #dee2e6; border-bottom: 1px solid #dee2e6;">
                            <p align="right" style="text-align: right; font-family: 'Poppins', sans-serif;">
                                <strong>Invoice No:</strong> #{{ $item->invoice_num }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-left: 30px; padding-right: 30px;">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                    <tr>
                        <td style="padding-top: 20px; padding-bottom: 20px;">
                            <p style="font-family: 'Poppins', sans-serif; line-height: 1.2;">
                                <strong style="display: block; margin-bottom: 5px;">Client:</strong><br />
                                Name: {{ $item->client_name }} <br />
                                Location: {{ $item->client_location }} <br />
                                Mobile: {{ $item->client_mobile }}
                            </p>
                        </td>
                        <td style="padding-top: 20px; padding-bottom: 20px;">
                            <p align="right"
                                style="text-align: right; font-family: 'Poppins', sans-serif; line-height: 1.2;">
                                <strong style="display: block; margin-bottom: 5px;">Doctor:</strong><br />
                                Name: {{ $item->doctor->name }} <br />
                                Clinic Name: {{ $item->doctor->clinic_name }}<br />
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- Products Table -->
        <tr>
            <td style="padding-left: 30px; padding-right: 30px;">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border: 1px solid #dee2e6;">
                    <thead>
                        <tr>
                            <th bgcolor="#f8f9fa" align="left"
                                style="white-space: nowrap; padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Product</th>
                            <th bgcolor="#f8f9fa" align="left"
                                style="white-space: nowrap; padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Barcode</th>
                            <th bgcolor="#f8f9fa" align="left"
                                style="white-space: nowrap; padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                The use</th>
                            <th bgcolor="#f8f9fa"
                                style="white-space: nowrap; padding: 12px; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Price</th>
                            <th bgcolor="#f8f9fa"
                                style="white-space: nowrap; padding: 12px; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                QTY</th>
                            <th bgcolor="#f8f9fa"
                                style="white-space: nowrap; padding: 12px; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Discount</th>
                            <th bgcolor="#f8f9fa" align="right"
                                style="white-space: nowrap; padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->invoice_items as $item_data)
                        <tr>
                            <td align="left"
                                style="padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->product->name }}</td>
                            <td align="left"
                                style="padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->product->barcode }}</td>
                            <td align="left"
                                style="padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; font-size: 14px;">
                                {{ $item_data->the_use ?? '----' }}</td>
                            <td align="center"
                                style="padding: 12px; text-align: center; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->price }}</td>
                            <td align="center"
                                style="padding: 12px; text-align: center; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->qty }}</td>
                            <td align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->discount }}</td>
                            <td align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>

        <!-- Packages Table -->
        @if(count($item->invoice_packages) > 0)
        <tr>
            <td style="padding-left: 30px; padding-right: 30px; padding-top: 20px;">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border: 1px solid #dee2e6;">
                    <thead>
                        <tr>
                            <th bgcolor="#f8f9fa" align="left"
                                style="white-space: nowrap; padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Package</th>

                            <th bgcolor="#f8f9fa"
                                style="white-space: nowrap; padding: 12px; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Price</th>
                            <th bgcolor="#f8f9fa"
                                style="white-space: nowrap; padding: 12px; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                QTY</th>

                            <th bgcolor="#f8f9fa" align="right"
                                style="white-space: nowrap; padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->invoice_packages as $item_data)
                        <tr>
                            <td align="left"
                                style="padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->package->name }}</td>

                            <td align="center"
                                style="padding: 12px; text-align: center; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->price }}</td>
                            <td align="center"
                                style="padding: 12px; text-align: center; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->qty }}</td>

                            <td align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        @endif


        <!-- Formulations Table -->
        @if(count($item->invoice_formulations) > 0)
        <tr>
            <td style="padding-left: 30px; padding-right: 30px; padding-top: 20px;">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border: 1px solid #dee2e6;">
                    <thead>
                        <tr>
                            <th bgcolor="#f8f9fa" align="left"
                                style="white-space: nowrap; padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Formulation</th>
                            <th bgcolor="#f8f9fa" align="left"
                                style="white-space: nowrap; padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Barcode</th>

                            <th bgcolor="#f8f9fa"
                                style="white-space: nowrap; padding: 12px; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Price</th>
                            <th bgcolor="#f8f9fa"
                                style="white-space: nowrap; padding: 12px; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                QTY</th>
                            <th bgcolor="#f8f9fa"
                                style="white-space: nowrap; padding: 12px; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Discount</th>
                            <th bgcolor="#f8f9fa" align="right"
                                style="white-space: nowrap; padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item->invoice_formulations as $item_data)
                        <tr>
                            <td align="left"
                                style="padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->formulation->name }}</td>
                            <td align="left"
                                style="padding: 12px; text-align: left; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->formulation->code ?? '---' }}</td>

                            <td align="center"
                                style="padding: 12px; text-align: center; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->price }}</td>
                            <td align="center"
                                style="padding: 12px; text-align: center; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->qty }}</td>
                            <td align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->discount }}</td>
                            <td align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;">
                                {{ $item_data->total }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
        @endif

        <!-- Totals Section -->
        <tr>
            <td style="padding-left: 30px; padding-right: 30px; padding-top: 20px;">
                <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; border: 1px solid #dee2e6;">
                    <tfoot>
                        <!-- Sub Total and Overall Discount in one row -->
                        <tr>
                            <td bgcolor="#f8f9fa" colspan="5"
                                style="padding: 12px; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                <strong style="white-space: nowrap;">Sub Total:</strong>
                            </td>

                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6;  background: rgb(120, 233, 144); -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                <strong style="white-space: nowrap;">{{ $item->sub_total }} SAR</strong>
                            </td>

                        </tr>

                        <tr>
                            <td bgcolor="#f8f9fa" colspan="3"
                                style="padding: 12px; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                <strong style="white-space: nowrap;">Overall Percentage</strong>
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                {{ $item->overall_percentage }}%
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                <strong style="white-space: nowrap;">Overall Discount:</strong>
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                -{{ $item->overall_discount }} SAR
                            </td>
                        </tr>

                        @if ($item->coupon)
                        <!-- Coupon and Coupon Value in one row -->
                        <tr>
                            <td bgcolor="#f8f9fa" colspan="3"
                                style="padding: 12px; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                <strong style="white-space: nowrap;">Coupon:</strong>
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                {{ $item->coupon->code }} | {{ $item->coupon_percentage }}%
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                <strong style="white-space: nowrap;">Coupon discount:</strong>
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                -{{ $item->coupon_discount }} SAR
                            </td>
                        </tr>
                        @endif

                        <!-- Tax and Total Before Tax in one row -->
                        <tr>
                            <td bgcolor="#f8f9fa" colspan="3"
                                style="padding: 12px; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                <strong style="white-space: nowrap;">Tax:</strong>
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                {{ $item->tax_value }} SAR
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                <strong style="white-space: nowrap;">Total Before Tax:</strong>
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                {{ $item->total - $item->tax_value }} SAR
                            </td>
                        </tr>

                        <!-- Total in its own row (centered) -->
                        <tr>
                            <td bgcolor="#f8f9fa" colspan="3"
                                style="padding: 12px; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                <strong style="white-space: nowrap;">Item Discounts:</strong>
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; border-bottom: 1px solid #dee2e6; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                -{{ $item->items_discount }} SAR
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; background-color: #f8f9fa; -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                <strong style="white-space: nowrap;">Total:</strong>
                            </td>
                            <td bgcolor="#f8f9fa" align="right"
                                style="padding: 12px; text-align: right; font-family: 'Poppins', sans-serif; background-color: #f8f9fa; font-weight: bold; background: rgb(120, 233, 144); -webkit-print-color-adjust: exact !important; color-adjust: exact !important; print-color-adjust: exact !important;">
                                {{ $item->total }} SAR
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </td>
        </tr>

    </table>
    <div id="absolute-element-footer2" style="margin-top: 150px">
        <table id="footer-content" dir="rtl" style="margin: 10px">
            <tr>
                <td style="font-weight: bold">تنويه هام :</td>
            </tr>
            <tr>
                <td>1-الإسترجاع في خلال 24 ساعه فقط بشرط سلامه المنتج</td>
            </tr>
            <tr>
                <td>2-الأدوية لا تتوفر بمستودعنا ونقوم بتوصيلها مقابل رسوم</td>
            </tr>
            <tr>
                <td>3- الأسعار تشمل ضريبة القيمة المضافة للمنتجات التي عليها ضريبة</td>
            </tr>
        </table>
    </div>
</body>

</html>
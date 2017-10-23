<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Invoice</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #fff;
            background-image: none;
            font-size: 12px;
        }
        address{
            margin-top:15px;
        }
        h2 {
            font-size:28px;
            color:#cccccc;
        }
        .container {
            padding-top:30px;
        }
        .invoice-head td {
            padding: 0 8px;
        }
        .invoice-body{
            background-color:transparent;
        }
        .logo {
            padding-bottom: 10px;
        }
        .table th {
            vertical-align: bottom;
            font-weight: bold;
            padding: 8px;
            line-height: 20px;
            text-align: left;
        }
        .table td {
            padding: 8px;
            line-height: 20px;
            text-align: left;
            vertical-align: top;
            border-top: 1px solid #dddddd;
        }
        .well {
            margin-top: 15px;
        }
    </style>
</head>

<body>
<div class="container">
    <table style="margin-left: auto; margin-right: auto" width="550">
        <tr>
            <td width="160">
                &nbsp;
            </td>

            <!-- Organization Name / Image -->
            <td align="right">
                <strong>{{ $header or $vendor }}</strong>
            </td>
        </tr>
        <tr valign="top">
            <td style="font-size:28px;color:#cccccc;">
                <img src="{{public_path()}}/img/logo.png" width="150px">
            </td>

            <!-- Organization Name / Date -->
            <td>
                <br><br>
                <strong>Para:</strong> {{ $owner->email ?: $owner->name }}
                <br>
                <strong>Fecha:</strong> {{ $invoice->date()->toFormattedDateString() }}
            </td>
        </tr>
        <tr valign="top">
            <!-- Organization Details -->
            <td style="font-size:9px;">
                <br>
                @if (isset($street))
                    {{ $street }}<br>
                @endif
                @if (isset($location))
                    {{ $location }}<br>
                @endif
                @if (isset($phone))
                    <strong>T</strong> {{ $phone }}<br>
                @endif
                @if (isset($vendorVat))
                    {{ $vendorVat }}<br>
                @endif
                @if (isset($url))
                    <a href="{{ $url }}">{{ $url }}</a>
                @endif
            </td>
            <td>
                <!-- Invoice Info -->
                <p>
                    <strong>Producto:</strong> {{ $product }}<br>
                    <strong>Número de comprobante de pago:</strong> {{ $id or $invoice->id }}<br>
                </p>

                <!-- Extra / VAT Information -->
                @if (isset($vat))
                    <p>
                        {{ $vat }}
                    </p>
                @endif

                <br><br>

                <!-- Invoice Table -->
                <table width="100%" class="table" border="0">
                    <tr>
                        <th align="left">Descripción</th>
                        <th align="right">Fecha</th>
                        <th align="right">Monto</th>
                    </tr>

                    <!-- Existing Balance -->
                    <tr>
                        <td>Balance Inicial</td>
                        <td>&nbsp;</td>
                        <td>{{ $invoice->startingBalance() }}</td>
                    </tr>

                    <!-- Display The Invoice Items -->
                    @foreach ($invoice->invoiceItems() as $item)
                        <tr>
                            <td colspan="2">{{ $item->description }}</td>
                            <td>{{ $item->total() }}</td>
                        </tr>
                    @endforeach

                    <!-- Display The Subscriptions -->
                    @foreach ($invoice->subscriptions() as $subscription)
                        <tr>
                            <td>Subscripción ({{ $subscription->quantity }})</td>
                            <td>
                                {{ $subscription->startDateAsCarbon()->formatLocalized('%B %e, %Y') }} -
                                {{ $subscription->endDateAsCarbon()->formatLocalized('%B %e, %Y') }}
                            </td>
                            <td>{{ $subscription->total() }}</td>
                        </tr>
                    @endforeach

                    <!-- Display The Discount -->
                    @if ($invoice->hasDiscount())
                        <tr>
                            @if ($invoice->discountIsPercentage())
                                <td>{{ $invoice->coupon() }} ({{ $invoice->percentOff() }}% Off)</td>
                            @else
                                <td>{{ $invoice->coupon() }} ({{ $invoice->amountOff() }} Off)</td>
                            @endif
                            <td>&nbsp;</td>
                            <td>-{{ $invoice->discount() }}</td>
                        </tr>
                    @endif

                    <!-- Display The Tax Amount -->
                    @if ($invoice->tax_percent)
                        <tr>
                            <td>Tax ({{ $invoice->tax_percent }}%)</td>
                            <td>&nbsp;</td>
                            <td>{{ Laravel\Cashier\Cashier::formatAmount($invoice->tax) }}</td>
                        </tr>
                    @endif

                    <!-- Display The Final Total -->
                    <tr style="border-top:2px solid #000;">
                        <td>&nbsp;</td>
                        <td style="text-align: right;"><strong>Total</strong></td>
                        <td><strong>{{ $invoice->total() }}</strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>

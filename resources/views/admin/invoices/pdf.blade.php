<!DOCTYPE html>
<html>
<head>
    <title>{{ __('message.Invoices') }} #{{ $invoice->invoice_id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header h1 { margin: 0; font-size: 2.2em; color: #2c3e50; letter-spacing: 2px; }
        .header p { color: #888; margin-top: 8px; font-size: 1.1em; }
        .invoice-section { background: #fff; box-shadow: 0 2px 8px #eee; padding: 30px 25px 25px 25px; }
        .invoice-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .invoice-title-row h2 { margin: 0; color: #2980b9; font-size: 1.5em; }
        .invoice-title-row .date { color: #555; font-size: 1.1em; }
        .info-table { width: 100%; margin-bottom: 25px; }
        .info-table td { background: #f6f8fa; padding: 12px 14px; vertical-align: top; font-size: 1em; }
        .info-table strong { color: #222; }
        .info-table address { font-style: normal; color: #444; }
        .summary-table { width: 100%; border-collapse: separate;border:none; margin-bottom: 15px; }
        .summary-table th { background: #e2e3e4; color: #000; font-weight: bold; text-align: right; border: 1px solid #d1d1d1; }
        .summary-table td { text-align: right;}
        .total-row { display:table;text-align: right; margin:10px 20px 40px auto; padding-top: 10px; border-top: 1px solid #d1d1d1 }
        .main-table { width: 100%; border-collapse: separate;border:none; margin-top: 10px; }
        .main-table th { background: #e2e3e4; color: #000; font-weight: bold; border: 1px solid #d1d1d1; text-align: left; }
        .main-table tr:nth-child(even) { background: #f2f6fa; }
        .footer { text-align: left;width:70px; margin:left:30px; font-size: 1.1em;padding:5px; border:2px solid black; transform: rotate(-30deg) translateY(-70px)}
        .footer > strong { text-transform: uppercase; display: block; margin-left:10px;}
  </style>
</head>
<body>

    <div class="invoice-section">
        <div class="invoice-title-row">
            <h2>Financial FMS</h2>
            <div class="date">
                {{ __('message.Date') }}: {{ $invoice->created_at ? $invoice->created_at->format('Y-m-d') : '-' }}
            </div>
        </div>
        <table class="info-table">
            <tr>
                <td style="width:33%;margin-right: 2px">
                    <strong>{{ __('message.From') }}</strong>
                    <address>
                        <strong>Manager</strong><br>
                        Address<br>
                        Lebanon.co
                    </address>
                </td>
                <td style="width:33%;margin-right: 2px">
                    <strong>{{ __('message.To') }}</strong>
                    <address>
                        <strong>{{ $invoice->income && $invoice->income->client ? $invoice->income->client->full_name : '-' }}</strong><br>
                        {{ $invoice->income && $invoice->income->client ? $invoice->income->client->address ?? '' : '' }}<br>
                        {{ __('message.Phone') }}: {{ $invoice->income && $invoice->income->client ? $invoice->income->client->client_phone ?? '' : '' }}<br>
                        {{ __('message.Email') }}: {{ $invoice->income && $invoice->income->client ? $invoice->income->client->email ?? '' : '' }}
                    </address>
                </td>
                <td style="width:34%">
                    <b>{{ __('message.Invoice') }} #{{ $invoice->invoice_id }}</b><br><br>
                    <b>{{ __('message.Payment Due') }}:</b> {{ $invoice->created_at ? $invoice->created_at->format('Y-m-d') : '-' }}<br>
                    <b>{{ __('message.Account') }}:</b> 000-12345
                </td>
            </tr>
        </table>
        <table class="summary-table">
          <thead>
            <tr>
              <th style="text-align:left;">Category</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            <tr style="border-bottom:1px solid #d1d1d1">
              <td style="text-align:left;">{{ $invoice->income->subcategory->category->name. ' | '. $invoice->income->subcategory->name }}</td>
              <td>${{ number_format($invoice->income->amount, 2) }}</td>
            </tr>
            <tr>
              <td style="text-align:left;">Discount rate: %{{ number_format($invoice->income->discount->rate ?? 0) }} </td>
              <td>${{ number_format($invoice->income->discount_amount ?? 0,2) }}</td>
            </tr>
          </tbody>
        </table>
        <div class="total-row">
          <b>Total price:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ${{ number_format($invoice->income->discount_amount > 0 ? $invoice->income->final_amount : $invoice->income->amount, 2) }}
        </div>
        <table class="main-table">
            <thead>
                <tr>
                    <th>{{ __('message.Description') }}</th>
                    <th style="text-align:right;">{{ __('message.Payments') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->payment->trans_description }}</td>
                    <td style="text-align:right;">${{ number_format($invoice->payment->payment_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <div class="total-row">
          <b>Remaining:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ${{ number_format(($invoice->income->remaining), 2)  }}
        </div>
    </div>
    <div class="footer">
        <strong>{{ $invoice->status }}</strong>
    </div>
</body>
</html>
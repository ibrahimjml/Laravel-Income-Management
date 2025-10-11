<!DOCTYPE html>
<html>
<head>
    <title>{{ __('message.Invoices') }} #{{ $invoice->invoice_id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header h1 { margin: 0; font-size: 2.2em; color: #2c3e50; letter-spacing: 2px; }
        .header p { color: #888; margin-top: 8px; font-size: 1.1em; }
        .invoice-section { background: #fff; border: 1px solid #e3e3e3; box-shadow: 0 2px 8px #eee; padding: 30px 25px 25px 25px; border-radius: 12px; }
        .invoice-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
        .invoice-title-row h2 { margin: 0; color: #2980b9; font-size: 1.5em; }
        .invoice-title-row .date { color: #555; font-size: 1.1em; }
        .info-table { width: 100%; margin-bottom: 25px; }
        .info-table td { background: #f6f8fa; padding: 12px 14px; vertical-align: top; font-size: 1em; }
        .info-table strong { color: #222; }
        .info-table address { font-style: normal; color: #444; }
        .main-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .main-table th { background: #2980b9; color: #fff; font-weight: bold; }
        .main-table th, .main-table td { border: 1px solid #d1d1d1; padding: 10px 8px; text-align: center; font-size: 1em; }
        .main-table tr:nth-child(even) { background: #f2f6fa; }
        .footer { text-align: left; margin-top: 40px; font-size: 1.1em; display: flex;flex-direction: column;gap: 2px;}
        .footer > span{ display: block;}
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
        <table class="main-table">
            <thead>
                <tr>
                    <th>{{ __('message.Description') }}</th>
                    <th>{{ __('message.Payments') }}</th>
                    <th>{{ __('message.Amount') }}</th>
                    <th>{{ __('message.Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @if($invoice->payment)
                <tr>
                    <td>{{ $invoice->payment->trans_description }}</td>
                    <td>${{ number_format($invoice->payment->payment_amount, 2) }}</td>
                    <td>${{ number_format($invoice->income->amount, 2) }}</td>
                    <td>{{ ucfirst($invoice->income->status) }}</td>
                </tr>
                @else
                <tr>
                    <td colspan="4">{{ __('message.No payments due today') }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
    <div class="footer">
        <strong>{{ __('message.Thanks') }},</strong>
        <span>
          <small>Copyright © {{ date('Y') }}&nbsp;Ibrahim jamal</small>
        </span>
    </div>
</body>
</html>
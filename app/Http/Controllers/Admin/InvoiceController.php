<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
  
public function index(InvoiceService $invoiceService)
{
    $invoices = $invoiceService->getAllInvoices();
    return view('admin.invoices.index', compact('invoices'));
}

public function show(InvoiceService $invoiceService, Invoice $invoice)
{
    $invoice = $invoiceService->getInvoiceDetails($invoice->invoice_id);
    return view('admin.invoices.show', compact('invoice'));
}

public function create(Request $request)
{
   $clients = Client::notDeleted()->get();
    $selectedClientId = $request->get('client_id');
    $selectedIncomeId = $request->get('income_id');

    $incomes = collect();
    $payments = collect();

    if ($selectedClientId) {
        $incomes = Income::notDeleted()->where('client_id', $selectedClientId)->get();
    }
    if ($selectedIncomeId) {
        $payments = Payment::notDeleted()->where('income_id', $selectedIncomeId)->get();
    }
    return view('admin.invoices.create',['clients'=>$clients,'incomes'=>$incomes,'payments'=>$payments,'selectedClientId'=>$selectedClientId,'selectedIncomeId'=>$selectedIncomeId]);
}

public function store(Request $request, InvoiceService $invoiceService)
{
    $invoice = $invoiceService->generateInvoice($request->income_id, $request->payment_id);
    return redirect()->route('invoices.show', ['invoice' => $invoice->invoice_id]);
}

public function edit(InvoiceService $invoiceService, Invoice $invoice)
{
    $invoice = $invoiceService->getInvoiceDetails($invoice->invoice_id);
  return view('admin.invoices.edit', compact('invoice'));
}

public function update(Request $request, InvoiceService $invoiceService, Invoice $invoice)
{
    $invoiceService->updateInvoice($invoice->invoice_id, $request->all());
    return redirect()->route('invoices.show', $invoice->invoice_id);
}

public function destroy(InvoiceService $invoiceService, Invoice $invoice)
{
    $invoiceService->deleteInvoice($invoice->invoice_id);
    return redirect()->route('invoices.index');
}

public function pdf(InvoiceService $invoiceService, Invoice $invoice)
{
    return $invoiceService->downloadInvoicePdf($invoice->invoice_id);
}
  }

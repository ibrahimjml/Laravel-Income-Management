<?php

namespace App\Services;

use PDF;
use App\Models\Income;
use App\Models\Invoice;
use App\Models\Payment;

class InvoiceService
{
    public function generateInvoice(int $incomeId, int $paymentId)
    {
        $income = Income::with('client', 'subcategory')->findOrFail($incomeId);
        $payment = Payment::findOrFail($paymentId);

        $invoice = Invoice::create([
            'income_id' => $income->income_id,
            'payment_id' => $payment->payment_id,
            'amount' => $income->final_amount,
            'payment_amount' => $payment->payment_amount,
            'status' => 'unpaid',
            'issue_date' => now()->toDateString(),
            'description' => $income->description,
        ]);

        return $invoice;
    }
    public function getAllInvoices()
    {
        return Invoice::with(['income.client', 'payment'])->paginate(10);
    }

    public function getInvoiceDetails(int $invoiceId)
    {
        return Invoice::with(['income.client', 'payment'])->findOrFail($invoiceId);
    }

    public function updateInvoice(int $invoiceId, array $data)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $invoice->update($data);
        return $invoice;
    }

    public function deleteInvoice(int $invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId);
        $invoice->delete();
        return true;
    }

    public function downloadInvoicePdf(int $invoiceId)
    {
        $invoice = $this->getInvoiceDetails($invoiceId);
        $pdf = PDF::loadView('admin.invoices.pdf', compact('invoice'));
        return $pdf->download('invoice_' . $invoice->id . '.pdf');
    }
}

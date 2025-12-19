<?php

namespace App\Http\Controllers\Admin;

use App\Models\Income;
use App\Services\PaymentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use Carbon\Carbon;

class PaymentController extends Controller
{
    protected $paymentService;
    public function __construct(PaymentService $paymentService){
      $this->paymentService = $paymentService;
    }
    public function payments_page()
  {
    $data = $this->paymentService->getPaymentsData();

    return view('admin.payments.payments',[
      
    'outdated_payments' => $data['outdated_payments'],
    'today_payments'    => $data['today_payments'],
    'upcoming_payments' => $data['upcoming_payments']
    ]);
  }
  public function add_payment(CreatePaymentRequest $request,Income $income)
    {
      $fields = $request->validated();

       try {
            $this->paymentService->addPayment($income->income_id, $fields);
            return back()->with('success','payment updated !');

    } catch (\Exception $e) {

        return back()->with('error','Error: ' . $e->getMessage());
    }

    }
  public function edit_payment(UpdatePaymentRequest $request,$payment_id,Income $income)
    {
      $fields = $request->validated();

       try {
            $this->paymentService->editPayment($payment_id,$income->income_id, $fields);
             return response()->json([
            'success' => true,
            'message' => 'payment updated successfully!'
        ]);

    } catch (\Exception $e) {

       return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }

    }
    public function upcoming_page()
    {
       $today = Carbon::today()->toDateString();
       $data = $this->paymentService->getUpComingPayments($today, 6);
       return view('admin.payments.upcoming',['upcoming_payments'=>$data]);
    }
    public function outdated_page()
    {
       $today = Carbon::today()->toDateString();
      $data = $this->paymentService->getOutDatedPayments($today, 2);
       return view('admin.payments.outdated',['outdated_payments'=>$data]);
    }
    public function today_page()
    {
      $today = Carbon::today()->toDateString();
       $data = $this->paymentService->getTodayPayments($today, 6);
        return view('admin.payments.today',['today_payments'=>$data]);
    }
}

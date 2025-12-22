<?php

namespace App\Http\Controllers\Admin;

use App\Enums\IncomeStatus;
use App\Models\Income;
use App\Services\PaymentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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

    } catch (\Throwable $e) {
        report($e);
        return back();
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
  public function delete_payment(Payment $payment, Income $income)
  {
     try{
        $this->paymentService->deletePayment($payment,$income);
           return response()->json([
            'success' => true,
            'message' => 'payment deleted successfully!'
        ]);
     }catch(\Exception $e){
       return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
      }
     }
  
    public function upcoming_page()
    {
       $today = Carbon::today()->toDateString();
       $data = $this->paymentService->getPaymentsByDate($today, '>',2);
       return view('admin.payments.upcoming',['upcoming_payments'=>$data]);
    }
    public function outdated_page()
    {
       $today = Carbon::today()->toDateString();
      $data = $this->paymentService->getPaymentsByDate($today, '<',2);
       return view('admin.payments.outdated',['outdated_payments'=>$data]);
    }
    public function today_page()
    {
      $today = Carbon::today()->toDateString();
       $data = $this->paymentService->getPaymentsByDate($today, '=',2);
        return view('admin.payments.today',['today_payments'=>$data]);
    }

    public function trashed_payments()
    {
      $payments = Payment::isDeleted()->with(['income','income.client','income.subcategory','income.subcategory.category'])->paginate(7);
      return view('admin.payments.trashed',[
        'payments' => $payments
      ]);
    }
      public function recover($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $income = $payment->income;

          $payment->update([
              'is_deleted' => 0
            ]);
            $totalPaid = $income->total_paid;

            $income->update([
              'status' => match (true) {
                      $totalPaid <= 0 => IncomeStatus::PENDING,
                      ($totalPaid > 0 && $totalPaid < $income->amount) => IncomeStatus::PARTIAL,
                      default => IncomeStatus::COMPLETE,
                  }
            ]);
  
        
        return response()->json([
            'success' => true,
            'message' => __('message.Payment Recovered')
        ]);
        
      } catch (\Exception $e) {  
         return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
         ], 500);
     }
    }
    public function force_delete($id)
    {
       try {
        Payment::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => __('message.Payment Permanently Deleted')
        ]);
        
      } catch (\Exception $e) {  
         return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
         ], 500);
     }
    }
}

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
  <h1 class="mb-4 text-center payments">{{__('message.Payments')}}</h1>
 <div id="content" class="d-flex flex-wrap gap-3 mt-5">
      
      <!-- Outdated Payments Card -->
      <x-payment-cards 
          :payments="$outdated_payments"
          title="message.Outdated Payments"
          headerColor="danger"
          emptyMessage="message.No outdated payments"
      />

      <!-- Today's Payments Card -->
      <x-payment-cards 
          :payments="$today_payments"
          title="message.Today's Payments"
          headerColor="primary"
          emptyMessage="message.No payments due today"
      />

      <!-- Upcoming Payments Card -->
      <x-payment-cards 
          :payments="$upcoming_payments"
          title="message.Upcoming Payments"
          headerColor="success"
          emptyMessage="message.No upcoming payments"
      />

  </div>
</div>
@endsection
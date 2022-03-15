@extends('layouts.app')

@section('title', 'Edit Customer')

@section('breadcrumb')
<ol class="breadcrumb border-0 m-0">
    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a></li>
    <li class="breadcrumb-item active">Pay</li>
</ol>
@endsection

@section('content')
<div class="container-fluid">
    <form action="{{ route('customers.pay', $customer) }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-12">
                @include('utils.alerts')
                <div class="form-group">
                    <button class="btn btn-primary">Pay <i class="bi bi-check"></i></button>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="offset-lg-4 col-lg-6">
                                <div class="form-group">
                                    <label for="customer_name">Customer Name <span class="text-danger">*</span></label>
                                    <input readonly="true" type="text" class="form-control" name="customer_name" required value="{{ $customer->customer_name }}">
                                </div>
                            </div>
                            <div class="offset-lg-4 col-lg-6">
                                <div class="form-group">
                                    <label for="customer_name">Customer Previous Balance <span class="text-danger">*</span></label>
                                    <input readonly="true" type="text" id="prev_balance" class="form-control" name="previous_balance" required value="{{ $customer->customer_balance }}">
                                </div>
                            </div>
                            <div class="offset-lg-4 col-lg-6">
                                <div class="form-group">
                                    <label for="customer_name">Pay<span class="text-danger">*</span></label>
                                    <input type="number" min=0 class="form-control" id="payingAmount" name="paid_balance" required value=0 onblur="payFunction(this.value)">
                                </div>
                            </div>
                            <div class="offset-lg-4 col-lg-6">
                                <div class="form-group">
                                    <label for="customer_name">Customer Remaining Balance <span class="text-danger">*</span></label>
                                    <input readonly="true" type="text" id="remaining_balance" class="form-control" name="remaining_balance" required value="0">
                                </div>
                            </div>
                            <script>
                                const payFunction = (amount) => {
                                    console.log(amount)
                                    var prevBal = document.getElementById("prev_balance").value;
                                    var remaining = prevBal - amount;
                                    console.log(remaining)
                                    if(remaining >= 0 ){
                                        document.getElementById("remaining_balance").value = remaining;
                                    }else{
                                        alert("Please pay an amount smaller than due Balance")
                                        document.getElementById("payingAmount").value = 0
                                        document.getElementById("remaining_balance").value= 0
                                    }


                                }
                            </script>


                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>
@endsection

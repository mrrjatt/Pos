<?php

namespace Modules\People\Http\Controllers;

use Carbon\Carbon;
use Modules\People\DataTables\CustomersDataTable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\People\Entities\Customer;
use Modules\People\Entities\CustomerPayment;

class CustomersController extends Controller
{

    public function index(CustomersDataTable $dataTable)
    {
        abort_if(Gate::denies('access_customers'), 403);

        return $dataTable->render('people::customers.index');
    }


    public function create()
    {
        abort_if(Gate::denies('create_customers'), 403);

        return view('people::customers.create');
    }


    public function store(Request $request)
    {
        abort_if(Gate::denies('create_customers'), 403);

        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|max:255',
            'customer_email' => 'required|email|max:255',
            'city'           => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'address'        => 'required|string|max:500',
        ]);

        Customer::create([
            'customer_name'  => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'city'           => $request->city,
            'country'        => $request->country,
            'address'        => $request->address,
            'customer_balance'        => $request->customer_balance
        ]);

        toast('Customer Created!', 'success');

        return redirect()->route('customers.index');
    }


    public function show(Customer $customer)
    {
        abort_if(Gate::denies('show_customers'), 403);

        return view('people::customers.show', compact('customer'));
    }


    public function edit(Customer $customer)
    {
        abort_if(Gate::denies('edit_customers'), 403);

        return view('people::customers.edit', compact('customer'));
    }
    public function pay(Customer $customer)
    {
        abort_if(Gate::denies('pay_customers'), 403);

        return view('people::customers.pay', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        abort_if(Gate::denies('update_customers'), 403);

        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'customer_phone' => 'required|max:255',
            'customer_email' => 'required|email|max:255',
            'city'           => 'required|string|max:255',
            'country'        => 'required|string|max:255',
            'address'        => 'required|string|max:500',
        ]);

        $customer->update([
            'customer_name'  => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_email' => $request->customer_email,
            'city'           => $request->city,
            'country'        => $request->country,
            'address'        => $request->address
        ]);

        toast('Customer Updated!', 'info');

        return redirect()->route('customers.index');
    }


    public function destroy(Customer $customer)
    {
        abort_if(Gate::denies('delete_customers'), 403);

        $customer->delete();

        toast('Customer Deleted!', 'warning');

        return redirect()->route('customers.index');
    }

    public function customerPayView(Customer $customer)
    {
        return view('people::customers.pay', compact('customer'));
    }


    public function customerPay(Request $request, Customer $customer)
    {

        $request->validate([
            'remaining_balance'  => 'required',

        ]);

        $customer->update([
            'customer_balance'  => $request->remaining_balance,

        ]);


        $request->validate([
            'customer_name'  => 'required|string|max:255',
            'previous_balance' => 'required',
            'paid_balance' => 'required',
            'remaining_balance' => 'required'
        ]);

        CustomerPayment::create([
            'customer_id' => $customer->id,
            'customer_name'  => $request->customer_name,
            'previous_balance' => $request->previous_balance,
            'paid_balance' => $request->paid_balance,
            'remaining_balance' => $request->remaining_balance,

        ]);

        toast('Customer Created!', 'success');

        return redirect()->route('customers.index');

        toast('Customer Updated!', 'info');

        return redirect()->route('customers.index');
    }
    public function customerPaymentHistoryView(Customer $customer){
        $data = ['id' => $customer->id];
        return view('people::customers.paymentHistory' , compact('data'));
    }
    public function customerPaymentHistory(Customer $customer , Request $request)
    {
        // // dd("reache here");
        // $paymentHistory = CustomerPayment::get();//where('customer_id', $customer->id)->get();
        // //dd($paymentHistory);
        // return view('people::customers.paymentHistory');
        if ($request->ajax()) {

            if ($request->input('start_date') && $request->input('end_date')) {

                $start_date = Carbon::parse($request->input('start_date'));
                $end_date = Carbon::parse($request->input('end_date'));

                if ($end_date->greaterThan($start_date)) {
                    $payments = CustomerPayment::where('customer_id' ,$request->customer_id )->whereBetween('created_at', [$start_date, $end_date])->get();
                } else {
                    $payments = CustomerPayment::latest()->get();
                }
            } else {
                $payments = CustomerPayment::latest()->get();
            }

            return response()->json([
                'students' => $payments
            ]);
        } else {
            dd('this is 403 error');
            abort(403);
        }
    }
}

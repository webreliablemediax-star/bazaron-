<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $payment_gateways = PaymentGateway::latest()->get();

        return view('backend.pages.payment_gateway.index', compact('payment_gateways'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        PaymentGateway::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Payment Gateway Added Successfully');
    }

    public function edit($id)
    {
        $payment_gateway = PaymentGateway::findOrFail($id);

        $payment_gateways = PaymentGateway::latest()->get();

        return view('backend.pages.payment_gateway.index', compact('payment_gateway', 'payment_gateways'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $payment_gateway = PaymentGateway::findOrFail($id);

        $payment_gateway->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.payment.gateway.index')
            ->with('success', 'Payment Gateway Updated Successfully');
    }

    public function delete($id)
    {
        $payment_gateway = PaymentGateway::findOrFail($id);

        $payment_gateway->delete();

        return redirect()->back()->with('success', 'Payment Gateway Deleted Successfully');
    }
}
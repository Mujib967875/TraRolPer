<?php

namespace App\Http\Controllers\Midtrans_Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $params = array(
            'transaction_details' => array(
                'order_id' => Str::uuid(),
                'gross_amount' => $request->price,
            ),
            'item_details' => array(
                array(
                    'price' => $request->price,
                    'quantity' => 1,
                    'name' => $request->item_name,
            )
            ),
            'costumer_details' => array(
                'first_name' => $request->customer_first_name,
                'email' => $request->customer_email,
            ),
        );

        $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

        $respone = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic $auth',
        ])->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

        $respone = json_decode($respone->body());

        // Save to DB
        $payment = new Payment;
        $payment->user_id = $request->user_id;
        $payment->tour_id = $request->tour_id;
        $payment->package_id = $request->package_id;
        $payment->order_id = $params['transaction_details']['order_id'];
        $payment->status = 'Pending';
        $payment->price = $request->price;
        $payment->costumer_first_name = $request->customer_first_name;
        $payment->costumer_email = $request->customer_email;
        $payment->item->name = $request->item_name;
        $payment->checkout_link = $respone->redirect_url;
        $payment->save();

        return response()->json($respone);
    }

    public function webhook(Request $request)
    {
        $auth = base64_encode(env('MIDTRANS_SERVER_KEY'));

        $respone = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic $auth',
        ])->post("https://api.sandbox.midtrans.com/v2/$request->order_id/status");

        $respone = json_decode($respone->body());

        $payment = Payment::where('order_id', $request->order_id)->firstorFail();

        if ($payment->status === 'settlement' || $payment->status === 'capture') {
            return response()->json('Payment has been already processed');
        }

        if ($respone->transaction_status === 'capture') {
            // Misal Memasukan atau Mengirimkan link dari pembelian ke customer
            $payment->status = 'capture';
        } else if ($respone->transaction_status === 'settlement') {
            $payment->status ='settlement';
        } else if ($respone->transaction_status === 'Pending') {
            $payment->status = 'Pending';
        } else if ($respone->transaction_status === 'deny') {
            $payment->status = 'deny';
        } else if ($respone->transaction_status === 'expire') {
            $payment->status = 'expire';
        } else if ($respone->transaction_status === 'cancel') {
            $payment->status = 'cancel';
        }

        $payment->save();
        return response()->json('success');
    }
}

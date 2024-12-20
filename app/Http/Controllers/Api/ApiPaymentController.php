<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiPaymentController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
        \Midtrans\Config::$clientKey = config('services.midtrans.client_key');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Log konfigurasi untuk debug
        \Log::info('Midtrans Config:', [
            'server_key' => \Midtrans\Config::$serverKey,
            'client_key' => \Midtrans\Config::$clientKey,
            'is_production' => \Midtrans\Config::$isProduction,
        ]);

    }

    // API untuk membuat link pembayaran
    public function createPaymentLink(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $totalAmount = $booking->seats_booked * 50000;

        $transactionDetails = [
            'order_id' => 'ORDER-' . time(),
            'gross_amount' => $totalAmount,
        ];

        $itemDetails = [
            [
                'id' => 'tiket-' . $bookingId,
                'price' => $totalAmount,
                'quantity' => 1,
                'name' => 'Booking Tiket Film',
            ],
        ];

        $customerDetails = [
            'first_name' => $booking->user->name,
            'email' => $booking->user->email,
        ];

        $paymentData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($paymentData);
        } catch (\Midtrans\Exceptions\MidtransException $e) {
            return response()->json([
                'error' => 'Midtrans API error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create payment link: ' . $e->getMessage()
            ], 500);
        }

        // Simpan pembayaran
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'order_id' => $transactionDetails['order_id'],
            'payment_type' => 'midtrans',  // Tambahkan payment_type dengan nilai 'midtrans'
            'status' => 'pending',
            'snap_token' => $snapToken,
        ]);

        return response()->json([
            'message' => 'Pembayaran Berhasil',
            'snap_token' => $snapToken,
        ]);
        return redirect()->route('payment.redirect', ['paymentId' => $payment->id]);

    }

    public function redirectToMidtransOnly($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

        // Kirim snap token ke view untuk digunakan di JavaScript
        return view('payment_redirect', ['snapToken' => $payment->snap_token]);
    }
    // 5. Handle notifikasi dari Midtrans
    public function handleNotification(Request $request)
    {
        $notification = $request->all();

        $payment = Payment::where('order_id', $notification['order_id'])->first();

        if ($notification['transaction_status'] === 'settlement') {
            $payment->status = 'paid';
        } elseif ($notification['transaction_status'] === 'pending') {
            $payment->status = 'pending';
        } elseif ($notification['transaction_status'] === 'cancel' || $notification['transaction_status'] === 'expire') {
            $payment->status = 'cancelled';
        }

        $payment->save();

        return response()->json(['status' => 'success']);
    }
}

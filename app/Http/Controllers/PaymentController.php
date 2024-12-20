<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Seat;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
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

    // 1. Tampilkan halaman checkout
    public function checkout($booking_id)
    {
        // Ambil data booking beserta relasi screening dan kursi
        $booking = Booking::with('screening.film')->findOrFail($booking_id);

        // Ambil kursi yang dibooking berdasarkan booking_id
        $seats = Seat::where('screening_id', $booking->screening_id)
                    ->where('is_booked', true)
                    ->get();

        // Total harga (misalnya: harga per kursi = 50.000)
        $hargaPerKursi = 50000;
        $totalHarga = count($seats) * $hargaPerKursi;

        return view('checkout', compact('booking', 'seats', 'totalHarga', 'hargaPerKursi'));
    }

    // 2. Tampilkan halaman pemilihan metode pembayaran
    public function showPaymentMethods($bookingId)
    {
        // Ambil data booking
        $booking = Booking::findOrFail($bookingId);

        return view('payment_method', compact('booking'));
    }

    // 3. Buat link pembayaran menggunakan Midtrans
    public function createPaymentLink(Request $request, $bookingId)
    {
        // Ambil data booking
        $booking = Booking::findOrFail($bookingId);

        // Hitung total biaya
        $totalAmount = $booking->seats_booked * 50000;

        // Detail transaksi untuk Midtrans
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
            ]
        ];

        $customerDetails = [
            'first_name' => $booking->user->name,
            'email'      => $booking->user->email,
        ];

        $paymentData = [
            'transaction_details' => $transactionDetails,
            'item_details'        => $itemDetails,
            'customer_details'    => $customerDetails,
        ];
        // return $paymentData;
        try {
            $snapToken = Snap::getSnapToken($paymentData);
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat pembayaran. Detail: ' . $e->getMessage());
        }

        // Simpan pembayaran ke tabel payments
        $payment = new Payment();
        $payment->booking_id = $booking->id;
        // $payment->order_id = $transactionDetails['order_id'];
        $payment->payment_type = 'midtrans';
        $payment->status = 'pending';
        $payment->snap_token = $snapToken;
        $payment->save();

        return redirect()->route('payment.redirect', $payment->id);
    }

    // 4. Redirect ke halaman pembayaran Midtrans
    public function redirectToMidtrans($paymentId)
    {
        $payment = Payment::findOrFail($paymentId);

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

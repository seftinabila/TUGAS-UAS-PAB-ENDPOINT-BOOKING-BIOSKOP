<?php
namespace App\Midtrans;

use Carbon\Carbon;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Support\Facades\Log;

class CreateSnapTokenService extends Midtrans {
    protected $transaksi;

    public function __construct($transaksi) {
        parent::__construct();
        $this->transaksi = $transaksi;

        // Log current server key for debugging
        Log::info('Using MIDTRANS_SERVER_KEY: ' . env('MIDTRANS_SERVER_KEY'));

        // Ensure Midtrans is configured correctly
        Config::$serverKey = 'SB-Mid-server-IHgXZrlganPQu17zEpCnBuzr';
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

    public function getSnapToken() {
        $order_id = Carbon::parse($this->transaksi->tanggal_order)
            ->format('Y-m-d') . str_pad($this->transaksi->id, 4, '0', STR_PAD_LEFT);

        $gross_amount = $this->transaksi->total_harga;

        // Log order ID and amount for debugging
        Log::info('Order ID: ' . $order_id . ' | Gross Amount: ' . $gross_amount);

        // Check if the total_harga is valid
        if (is_null($gross_amount) || $gross_amount <= 0) {
            Log::error('Invalid gross amount: ' . $gross_amount);
            throw new \Exception('Invalid total price');
        }

        // Prepare the params for Snap Token
        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $gross_amount,
            ],
        ];

        try {
            // Request Snap Token from Midtrans
            $snapToken = Snap::getSnapToken($params);
            Log::info('Snap Token generated: ' . $snapToken);
            return $snapToken;
        } catch (\Exception $e) {
            // Log the error and rethrow it
            Log::error('Failed to get Snap token: ' . $e->getMessage());
            throw new \Exception('Failed to get Snap token');
        }
    }
}





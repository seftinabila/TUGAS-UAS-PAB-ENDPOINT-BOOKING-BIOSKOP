<?php

namespace App\Midtrans;

use stdClass;
use Carbon\Carbon;
use App\Models\Pesanan;
use App\Models\Transaksi;
use App\Midtrans\Midtrans;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

class CallbackService extends Midtrans
{
    protected $notification;
    protected $order;
    protected $serverKey;

    public function __construct()
    {
        parent::__construct();
        $this->serverKey = env('MIDTRANS_SERVER_KEY');
        $this->_handleNotification();
    }

    // Verifies the signature key (for testing purposes)
    public function _isSignatureKeyVerified()
    {
        // For testing using RESTER
        return true;

        // For testing using ngrok
        // return ($this->_createLocalSignatureKey() == $this->notification->signature_key);
    }

    // Check if the transaction is successful
    public function isSuccess()
    {
        $statusCode = $this->notification->status_code;
        $transactionStatus = $this->notification->transaction_status;
        $fraudStatus = trim($this->notification->fraud_status) !== '';

        return ($statusCode == 200 && $fraudStatus &&
                ($transactionStatus == 'capture' || $transactionStatus == 'settlement'));
    }

    // Check if the transaction has expired
    public function isExpire()
    {
        return ($this->notification->transaction_status == 'expire');
    }

    // Check if the transaction was cancelled
    public function isCancelled()
    {
        return ($this->notification->transaction_status == 'cancel');
    }

    // Return the notification data
    public function getNotification()
    {
        return $this->notification;
    }

    // Return the order data
    public function getOrder()
    {
        return $this->order;
    }

    // Create a local signature key to verify the notification
    public function _createLocalSignatureKey()
    {
        $orderId = Carbon::parse($this->order->tanggal_order)->format('Y-m-d') .
                   str_pad($this->order->id, 4, '0', STR_PAD_LEFT);
        $statusCode = $this->notification->status_code;
        $grossAmount = $this->order->total_harga;
        $serverKey = $this->serverKey;

        $input = $orderId . $statusCode . $grossAmount . '.00' . $serverKey;
        $signature = openssl_digest($input, 'sha512');

        return $signature;
    }

    // Handle the incoming notification
    protected function _handleNotification()
    {
        $notification = new Notification();
        $pesananId = $notification->order_id;
        $id = substr($pesananId, -4);

        // Retrieve the transaction using the last 4 digits of the order ID
        $transaksi = Transaksi::find($id);

        $this->notification = $notification;
        $this->order = $transaksi;
    }
}

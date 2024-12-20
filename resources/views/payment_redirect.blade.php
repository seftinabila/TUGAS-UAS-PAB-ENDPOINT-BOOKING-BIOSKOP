<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body>
    <h3>Memproses pembayaran...</h3>
    <script type="text/javascript">
        window.onload = function () {
            snap.pay("{{ $snapToken }}", {
                onSuccess: function(result) {
                    alert("Pembayaran sukses!");
                    window.location.href = "/bookings"; // Ganti dengan redirect sesuai kebutuhan
                },
                onPending: function(result) {
                    alert("Pembayaran pending!");
                },
                onError: function(result) {
                    alert("Pembayaran gagal!");
                },
                onClose: function() {
                    alert("Anda belum menyelesaikan pembayaran.");
                }
            });
        };
    </script>
</body>
</html>

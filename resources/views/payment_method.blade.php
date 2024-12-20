<section class="anime-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('payment.create', $booking->id) }}" method="POST">
                    @csrf
                    <h5>Metode Pembayaran</h5>

                    <!-- Pilihan Metode Pembayaran -->
                    <div class="form-group">
                        <label for="payment_method">Pilih Metode Pembayaran</label>
                        <select name="payment_method" id="payment_method" class="form-control" required>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="bank_transfer">Transfer Bank</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Lanjutkan ke Pembayaran</button>
                </form>
            </div>
        </div>
    </div>
</section>

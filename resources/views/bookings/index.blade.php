@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h1>Booking Film</h1>
</div>

<!-- Page Content -->
<div class="page-content">
    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h5>Daftar Booking</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('bookings.create') }}" class="btn btn-primary mb-3">Tambah Booking</a>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover" id="filmsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Film</th>
                        <th>Waktu Tayang</th>
                        <th>Nama User</th>
                        <th>Jumlah Kursi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $booking->screening->film->title }}</td>
                        <td>{{ $booking->screening->show_time }}</td>
                        <td>{{ $booking->user->name }}</td>
                        <td>{{ $booking->seats_booked }}</td>
                        <td>
                            <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus booking ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


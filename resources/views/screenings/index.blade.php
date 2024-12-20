@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h1>Jadwal Film</h1>
</div>

<!-- Page Content -->
<div class="page-content">
    <!-- Card -->
    <div class="card">
        <div class="card-header">
            <h5>Daftar Jadwal</h5>
        </div>
        <div class="card-body">
            <a href="{{ route('screenings.create') }}" class="btn btn-primary mb-3">Tambah Jadwal</a>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover" id="filmsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Film</th>
                        <th>Waktu Tayang</th>
                        <th>Kursi Tersedia</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($screenings as $screening)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $screening->film->title }}</td>
                        <td>{{ $screening->show_time }}</td>
                        <td>{{ $screening->available_seats }}</td>
                        <td>
                            <a href="{{ route('screenings.edit', $screening->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('screenings.destroy', $screening->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">Hapus</button>
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


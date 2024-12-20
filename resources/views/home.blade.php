@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Selamat Datang di Bioskop Online</h1>
    <a href="{{ route('films.index') }}">Lihat Daftar Film</a>
</div>
@endsection

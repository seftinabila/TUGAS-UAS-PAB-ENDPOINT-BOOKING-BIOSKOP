@extends('layouts.app')
@section('content')
<div class="container">
    <h1>{{ $film->title }}</h1>
    <p>{{ $film->description }}</p>
    <p>Genre: {{ $film->genre }}</p>
    <p>Tanggal Rilis: {{ $film->release_date }}</p>
    <a href="{{ route('screenings.index', $film->id) }}">Lihat Jadwal</a>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="page-heading">
    <h1>Dashboard</h1>
</div>

<!-- Page Content -->
<div class="page-content">
    <h2>Selamat datang {{Auth::user()->name}}</h2>
    {{-- <p>This is your dashboard where you can manage the films, users, and settings.</p> --}}
</div>
@endsection

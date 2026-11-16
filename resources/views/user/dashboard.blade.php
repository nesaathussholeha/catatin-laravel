
@extends('layouts.user')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

    <div class="dashboard-welcome">
        <h2>Selamat Datang {{ auth()->user()->name }}! 👋</h2>
        <p>
            Yuk, mulai berbagi catatan dan belajar bersama mahasiswa lainnya.
        </p>
    </div>

@endsection

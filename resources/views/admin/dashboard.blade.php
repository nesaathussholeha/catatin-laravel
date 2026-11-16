@extends('layouts.admin')

@section('title', 'Dashboard Admin - CatatIn')
@section('page-heading', 'Dashboard')

@section('content')

    <div class="dashboard-welcome">
        <h2>Selamat Datang, {{ auth()->user()->name }}!</h2>
        <p>Berikut ringkasan aktivitas platform CatatIn.</p>
    </div>

@endsection

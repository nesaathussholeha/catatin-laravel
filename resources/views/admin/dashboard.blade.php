@extends('layouts.admin')

@section('title', 'Dashboard Admin - CatatIn')
@section('page-heading', 'Dashboard')

@section('content')

<div class="sapaan">
    <h2>Halo, {{ auth()->user()->name }}! 👋</h2>
    <p>Temukan catatan kuliah yang kamu butuhkan hari ini.</p>
</div>

@endsection

@extends('auth.app')

@section('title', 'Verify Your Email')

@section('content')
    <div class="container mt-5">
        <h1>Verify Your Email Address</h1>
        <p>
            Sebelum melanjutkan, silakan cek email Anda untuk tautan verifikasi.
            Jika Anda tidak menerima email,
            <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">klik di sini untuk mengirim ulang</button>.
            </form>
        </p>
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
    </div>
@endsection

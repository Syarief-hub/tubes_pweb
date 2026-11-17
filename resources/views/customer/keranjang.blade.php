@extends('customer.layouts')

@section('title', 'Keranjang - NafilCafe & Resto')
@section('subtitle', 'Keranjang')

@section('content')
    <div class="px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-800">Keranjang *={{ $cartItems->count() }}, Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}</h1>
        <p class="text-gray-600">Berikut adalah item yang telah Anda tambahkan ke keranjang.</p>
    </div>
@endsection
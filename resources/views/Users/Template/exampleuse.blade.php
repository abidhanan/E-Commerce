@extends('Users.Template.index')

@section('title', 'Contoh Halaman User')

@push('css')
    <style>
        .example-page {
            min-height: 60vh;
            padding: 120px 40px 80px;
            max-width: 960px;
            margin: 0 auto;
        }
    </style>
@endpush

@section('content')
    <section class="example-page">
        <h1>Contoh pakai Users.Template.index</h1>
        <p>Isi halaman bisa ditaruh di section content ini. Navbar, shop sidebar, cart, newsletter, dan footer sudah
            otomatis dari template.</p>
    </section>
@endsection

@push('scripts')
@endpush

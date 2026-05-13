@extends('Admin.Template.index')
@section('title', 'Custom Collections')

@push('styles')
    <style>
        .collection-card {
            transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
        }

        .collection-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .collection-card.is-active {
            border-color: #212529;
            box-shadow: 0 12px 24px rgba(33, 37, 41, 0.12);
        }

        .collection-thumb {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
            background: #f3f3f3;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        @include('Admin.partials.index-help-header', [
            'heading' => 'Pilih Custom Collection',
            'collapseId' => 'customCollectionInfoCollapse',
            'helpTitle' => 'Keterangan Custom Collections',
            'messages' => [
                'Halaman ini menentukan collection khusus yang dipakai pada section custom collection di homepage.',
                'Pilih satu collection untuk melanjutkan pengaturan produk yang ingin ditampilkan pada section tersebut.',
                'Collection yang sedang aktif akan menjadi sumber produk untuk tampilan custom collection di storefront.',
            ],
        ])

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <p class="text-muted mb-0">Pilih satu collection, lalu tentukan produk dari collection tersebut yang akan ditampilkan.</p>

            @if ($selectedCollectionId)
                <span class="badge text-bg-dark fs-6">{{ $selectedProductCount }} produk sedang aktif</span>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row g-4">
            @foreach ($collections as $collection)
                @php
                    $isActive = (int) $selectedCollectionId === (int) $collection->id;
                @endphp

                <div class="col-md-6 col-xl-4">
                    <div class="card h-100 p-3 collection-card {{ $isActive ? 'is-active' : '' }}">
                        <div class="position-relative mb-3">
                            <img src="{{ $collection->img ? asset('storage/' . $collection->img) : 'https://via.placeholder.com/640x480?text=Collection' }}"
                                alt="{{ $collection->name }}" class="collection-thumb">

                            @if ($isActive)
                                <span class="badge text-bg-dark position-absolute top-0 end-0 m-3">Sedang dipakai</span>
                            @endif
                        </div>

                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                            <div>
                                <h5 class="mb-1">{{ $collection->name }}</h5>
                                <p class="text-muted mb-0">{{ $collection->active_products_count }} produk aktif tersedia</p>
                            </div>

                            <span class="badge text-bg-light border">{{ $collection->active_products_count }}</span>
                        </div>

                        <div class="mt-auto pt-3">
                            <a href="{{ route('admin.custom-collections-display.choose', $collection) }}"
                                class="btn {{ $isActive ? 'btn-dark' : 'btn-outline-dark' }} w-100">
                                {{ $isActive ? 'Atur Produk Display' : 'Pilih Collection Ini' }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

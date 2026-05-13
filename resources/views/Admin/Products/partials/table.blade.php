@forelse($products as $product)
    <tr>
        <td>
            @php $primary = $product->images->first(); @endphp
            @if ($primary)
                <img src="{{ asset('storage/' . $primary->image) }}" class="rounded border"
                    style="width:60px;height:60px;object-fit:cover;">
            @else
                -
            @endif
        </td>

        <td class="fw-semibold">{{ $product->name }}</td>

        <td>{{ $product->category->name ?? '-' }}</td>
        <td>{{ $product->collection->name ?? '-' }}</td>
        <td>
            <span class="badge bg-secondary">
                {{ $product->variants->count() }} Variant
            </span>
        </td>

        <td>
            @if ($product->is_active)
                <span class="badge bg-success">Aktif</span>
            @else
                <span class="badge bg-danger">Nonaktif</span>
            @endif
        </td>

        <td>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                    Edit
                </a>

                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="m-0"
                    data-confirm-message="Yakin hapus produk ini?" data-confirm-title="Hapus Produk"
                    data-confirm-button="Hapus" data-confirm-class="btn-danger">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">
                        Hapus
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-4 text-muted">
            Tidak ada data produk
        </td>
    </tr>
@endforelse

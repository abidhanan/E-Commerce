<div class="table-responsive">
    <table class="table align-middle table-hover">
        <thead class="table-light">
            <tr>
                <th width="80">Image</th>
                <th>Name</th>
                <th>Use Product</th>
                <th width="220" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($collections as $cat)
                <tr>
                    <td>
                        @if ($cat->img)
                            <img src="{{ asset('storage/' . $cat->img) }}"
                                style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                        @else
                            <img src="{{ asset('images/no-image.jpg') }}"
                                style="width:50px;height:50px;object-fit:cover;border-radius:6px;">
                        @endif
                    </td>

                    <td>
                        <span class="fw-semibold text-uppercase" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                            {{ $cat->name }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-dark">
                            {{ $cat->products_count ?? 0 }} Products
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                            
                            {{-- JEMBATAN PINTAS MUTLAK: Terhubung langsung ke Custom Collection Display --}}
                            <a href="{{ route('admin.custom-collections-display.choose', $cat->id) }}" 
                               class="btn btn-outline-dark btn-sm text-uppercase fw-bold" 
                               style="font-size: 0.75rem; letter-spacing: 0.5px;"
                               title="Atur tampilan produk seri ini di Landing Page Beranda">
                                <i class="bi bi-grid-3x3-gap-fill me-1"></i> Showcase
                            </a>

                            <a href="{{ route('admin.collections.edit', $cat->id) }}" class="btn btn-primary btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('admin.collections.destroy', $cat->id) }}" method="POST"
                                class="m-0" onsubmit="return confirm('Yakin ingin menghapus seri collection ini?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">
                        No data found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Memastikan paginasi selaras dengan container AJAX --}}
<div class="mt-3">
    {{ $collections->links('pagination::bootstrap-5') }}
</div>
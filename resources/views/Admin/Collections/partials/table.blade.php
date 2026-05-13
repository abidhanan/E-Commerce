<div class="table-responsive">
    <table class="table align-middle table-hover">
        <thead class="table-light">
            <tr>
                <th width="80">Image</th>
                <th>Name</th>
                <th>Use Product</th>
                <th width="150">Aksi</th>
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
                            -
                        @endif
                    </td>

                    <td>{{ $cat->name }}</td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ $cat->products_count }} Products
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                            <a href="{{ route('admin.collections.edit', $cat->id) }}" class="btn btn-primary btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('admin.collections.destroy', $cat->id) }}" method="POST"
                                class="m-0" data-confirm-message="Yakin hapus collection ini?"
                                data-confirm-title="Hapus Collection" data-confirm-button="Hapus"
                                data-confirm-class="btn-danger">
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
                    <td colspan="4" class="text-center">
                        No data found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $collections->links('pagination::bootstrap-5') }}

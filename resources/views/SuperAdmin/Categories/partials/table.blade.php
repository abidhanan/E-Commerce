<div class="table-responsive">
    <table class="table align-middle table-hover">
        <thead class="table-light">
            <tr>
                <th width="80">Image</th>
                <th>Name</th>
                <th>Parent</th>
                <th width="150">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($categories as $cat)
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

                    <td>{{ $cat->parent->name ?? '-' }}</td>

                    <td>
                        <a href="{{ route('superadmin.categories.edit', $cat->id) }}" class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <form action="{{ route('superadmin.categories.destroy', $cat->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')

                            <button type="submit" onclick="return confirm('Yakin hapus?')"
                                class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </form>
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

{{ $categories->links() }}

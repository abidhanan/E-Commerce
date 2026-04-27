<table class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Tags</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Preview</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($posts as $post)
            <tr>
                <td>{{ $posts->firstItem() ? $posts->firstItem() + $loop->index : $loop->iteration }}</td>
                <td>{{ $post->title }}</td>

                <td>
                    {{ $post->category->name ?? '-' }}
                </td>

                <td>
                    @foreach ($post->tag_objects as $tag)
                        <span class="badge bg-primary">{{ $tag->name }}</span>
                    @endforeach
                </td>

                <td>
                    @if ($post->status == 'published')
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </td>

                <td>
                    {{ $post->created_at->format('d M Y') }}
                </td>
                <td>
                    <button class="btn btn-sm btn-info btn-preview" data-title="{{ $post->title }}"
                        data-content="{{ base64_encode($post->content) }}"
                        data-image="{{ $post->thumbnail ? asset('storage/' . $post->thumbnail) : '' }}">
                        Preview
                    </button>
                </td>
                <td>
                    <div class="btn-group" role="group">
                        @if ($post->status !== 'published')
                            <a href="{{ route('superadmin.blogs.publish', $post->id) }}" class="btn btn-sm btn-success">
                                Publish
                            </a>
                        @endif

                        <a href="{{ route('superadmin.blogs.edit', $post->id) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>
                        <form action="{{ route('superadmin.blogs.destroy', $post->id) }}" method="POST" class="d-inline"
                            data-confirm-message="Apakah Anda yakin ingin menghapus blog ini?"
                            data-confirm-title="Hapus Blog" data-confirm-button="Hapus"
                            data-confirm-class="btn-danger">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center">Tidak ada data</td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- PAGINATION --}}
<div class="mt-3">
    <div class="d-flex justify-content-end">
        {{ $posts->links('pagination::bootstrap-5') }}
    </div>
</div>

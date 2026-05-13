@foreach ($breathabilities as $breathability)
    <tr>
        <td>{{ $breathability->id }}</td>
        <td>{{ $breathability->level }}</td>
        <td>{{ $breathability->label }}</td>
        <td>{!! \App\Support\HtmlSanitizer::clean($breathability->description ?? '', ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'blockquote', 'a']) !!}</td>
        <td>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                <a href="{{ route('admin.breathabilities.edit', $breathability->id) }}"
                    class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('admin.breathabilities.destroy', $breathability->id) }}" method="POST"
                    class="m-0" data-confirm-message="Apakah Anda yakin ingin menghapus breathability ini?"
                    data-confirm-title="Hapus Breathability" data-confirm-button="Hapus" data-confirm-class="btn-danger">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
        </td>
    </tr>
@endforeach

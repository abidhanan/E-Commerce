@foreach ($insulations as $insulation)
    <tr>
        <td>{{ $insulation->id }}</td>
        <td>{{ $insulation->level }}</td>
        <td>{{ $insulation->label }}</td>
        <td>{!! \App\Support\HtmlSanitizer::clean($insulation->description ?? '', ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'blockquote', 'a']) !!}</td>
        <td>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                <a href="{{ route('admin.insulations.edit', $insulation->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('admin.insulations.destroy', $insulation->id) }}" method="POST" class="m-0"
                    data-confirm-message="Apakah Anda yakin ingin menghapus insulation ini?"
                    data-confirm-title="Hapus Insulation" data-confirm-button="Hapus" data-confirm-class="btn-danger">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
        </td>
    </tr>
@endforeach

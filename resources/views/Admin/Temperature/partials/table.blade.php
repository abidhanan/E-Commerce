@foreach ($temperatures as $temperature)
    <tr>
        <td>{{ $temperature->label }}</td>
        <td>{!! \App\Support\HtmlSanitizer::clean($temperature->description ?? '', ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'blockquote', 'a']) !!}</td>
        <td>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                <a href="{{ route('admin.temperatures.edit', $temperature->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('admin.temperatures.destroy', $temperature->id) }}" method="POST"
                class="m-0"
                data-confirm-message="Yakin ingin menghapus temperature ini?"
                data-confirm-title="Hapus Temperature" data-confirm-button="Hapus"
                data-confirm-class="btn-danger">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
        </td>
    </tr>
@endforeach

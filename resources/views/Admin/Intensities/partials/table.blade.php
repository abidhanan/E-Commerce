@foreach ($intensities as $Intensity)
    <tr>
        <td>{{ $Intensity->id }}</td>
        <td>{{ $Intensity->label }}</td>
        <td>{!! \App\Support\HtmlSanitizer::clean($Intensity->description ?? '', ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'blockquote', 'a']) !!}</td>
        <td>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                <a href="{{ route('admin.intensities.edit', $Intensity) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('admin.intensities.destroy', $Intensity) }}" method="POST" class="m-0"
                    data-confirm-message="Are you sure you want to delete this intensity?"
                    data-confirm-title="Delete Intensity" data-confirm-button="Delete"
                    data-confirm-class="btn-danger">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </td>
    </tr>
@endforeach

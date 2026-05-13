@foreach ($materials as $material)
    <tr>
        <td>{{ $material->id }}</td>
        <td>
            @if ($material->image)
                <img src="{{ asset('storage/' . $material->image) }}" alt="{{ $material->material }}" width="100">
            @else
                No Image
            @endif
        </td>
        <td>{{ $material->material }}</td>
        <td>{!! \App\Support\HtmlSanitizer::clean($material->description ?? '', ['p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'blockquote', 'a']) !!}</td>
        <td>
            <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">
                <a href="{{ route('admin.materials.edit', $material) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="m-0"
                    data-confirm-message="Are you sure you want to delete this material?"
                    data-confirm-title="Delete Material" data-confirm-button="Delete"
                    data-confirm-class="btn-danger">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </form>
            </div>
        </td>
    </tr>
@endforeach

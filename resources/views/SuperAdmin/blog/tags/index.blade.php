@extends('SuperAdmin.Template.Index')

@section('title', 'Tags')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Tags</h5>
            <a href="{{ route('superadmin.tags.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> Add Tag
            </a>
        </div>

        <div class="card-body">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tags as $tag)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $tag->name }}</td>
                            <td>
                                <a href="{{ route('superadmin.tags.edit', $tag) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('superadmin.tags.destroy', $tag->id) }}" method="POST" class="d-inline"
                                    data-confirm-message="Delete this tag?" data-confirm-title="Delete Tag"
                                    data-confirm-button="Delete" data-confirm-class="btn-danger">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">
                                No tags found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

@endsection

@extends('SuperAdmin.Template.Index')

@section('title', 'Blog Categories')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Blog Categories</h5>

            <a href="{{ route('superadmin.blog-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus"></i> Add Category
            </a>
        </div>

        <div class="card-body">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($categories ?? [] as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>

                                <a href="{{ route('superadmin.blog-categories.edit', $category) }}"
                                    class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route('superadmin.blog-categories.destroy', $category->id) }}" method="POST"
                                    class="d-inline" data-confirm-message="Delete this category?"
                                    data-confirm-title="Delete Category" data-confirm-button="Delete"
                                    data-confirm-class="btn-danger">
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
                            <td colspan="4" class="text-center text-muted">
                                No categories found
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
    </div>

@endsection

@forelse ($users as $user)
    <tr>
        <td class="fw-medium">{{ $user->name }}</td>

        <td>{{ $user->email }}</td>

        <td>
            <span class="badge bg-info text-dark">
                {{ $user->getRoleNames()->first() }}
            </span>
        </td>

        <td>
            @if ($user->email_verified_at)
                <span class="badge bg-success">Terverifikasi</span>
            @else
                <span class="badge bg-secondary">Belum Verifikasi</span>
            @endif
        </td>

        <td>
            <div class="d-flex gap-2">
                <a href="{{ route('superadmin.users.edit', $user->id) }}" class="btn btn-outline-primary btn-sm">
                    Edit
                </a>

                <form action="{{ route('superadmin.users.destroy', $user->id) }}" method="POST"
                    onsubmit="return confirm('Hapus user?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        Hapus
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-muted">
            Data tidak ditemukan
        </td>
    </tr>
@endforelse

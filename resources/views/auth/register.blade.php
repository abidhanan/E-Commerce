
    <h4 class="text-center mb-4">Register</h4>

    <form method="POST" action="{{ url('/register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Register
        </button>
    </form>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            {{ $errors->first() }}
        </div>
    @endif

    <a href="{{ url('/admin/login') }}" class="d-block text-center mt-3">
        Sudah punya akun? Login
    </a>

    <a href="{{ route('login') }}" class="d-block text-center mt-3">
        Sudah punya akun? Login
    </a>

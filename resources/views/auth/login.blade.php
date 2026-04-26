
    <h4 class="text-center mb-4">Login</h4>

    <form method="POST" action="{{ url('/login') }}">
        @csrf

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            {{ $errors->first() }}
        </div>
    @endif

    <a href="{{ url('/password/reset') }}" class="d-block text-center mt-3">
        Lupa password?
    </a>
    <a href="{{ url('/admin/register') }}" class="d-block text-center mt-3">
        Belum punya akun? Register
    </a>
    <a href="{{ route('register') }}" class="d-block text-center mt-3">
        Belum punya akun? Register
    </a>


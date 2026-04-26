<h1>Forgot Password</h1>
<p>Enter your email address and we'll send you a link to reset your password.</p>

{{-- Success message --}}
@if (session('status'))
    <div>{{ session('status') }}</div>
@endif

{{-- Validation errors --}}
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('password.email') }}" id="forgot-form">
    @csrf

    <label>Email</label><br>
    <input type="email" name="email"
        class="@error('email') is-invalid @enderror"
        value="{{ old('email') }}" required><br>

    @error('email')
        <div>{{ $message }}</div>
    @enderror

    <button type="submit">Send Reset Link</button>
</form>

<p>
    Remember your password?
    <a href="{{ route('login') }}">Remember password?Login here</a>
</p>

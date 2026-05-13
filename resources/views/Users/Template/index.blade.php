<!DOCTYPE html>
<html lang="en">

<head>
    @include('Users.Template.header')
</head>

<body>

    @include('Users.Template.navbar')

    @include('Users.Template.cart-sidebar')
    @include('Users.Template.shop-sidebar')
    @include('Users.Template.notification')
    @include('Users.Template.dialog')

    @hasSection('content')
        <main class="site-main">
            @yield('content')
        </main>
    @else
        @include('Users.Template.home')
    @endif

    @include('Users.Template.footer')
    @include('Users.Template.scripts')
    @stack('scripts')
</body>

</html>

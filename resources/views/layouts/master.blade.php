<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - {{ config('app.name', 'Laravel Vue App') }}</title>

    {{-- google font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="icon" href="{{ getImageUrl($settings['store_icon']) }}" type="image/x-icon">
    @vite(['resources/web/assets/bootstrap-5.3.8-dist/css/bootstrap.min.css', 'resources/web/assets/css/style.css'])
    {{-- Page-specific styles --}}
    @stack('styles')
</head>

<body>
    @include('layouts.partial.navbar')
    <main>
        @if (request()->route()->getName() != 'home')
            <!-- Breadcrumb Section Start -->
            <section class="breadcrumb position-relative">
                <img class="img-fluid" src="{{ asset('storage/web/about/breadcrumb.png') }}" alt="">
                <img class="position-absolute bottom-0 w-100" draggable="false"
                    src="{{ asset('storage/web/about/breadcrumb-gradient.png') }}" alt="">
                <h6 class="page-title title-one position-absolute text-capitalize">@yield('breadcrumb')</h6>
            </section>
        @endif
        <!-- Breadcrumb Section Start -->
        @yield('content')
    </main>
    <button id="backToTop" class="back-to-top">
        ↑
    </button>
    @include('layouts.partial.footer')

    {{-- Global Bootstrap JS --}}
    @if (request()->route()->getName() == 'home')
        @vite('resources/web/assets/js/home.js')
    @endif
    @vite(['resources/web/assets/bootstrap-5.3.8-dist/js/bootstrap.bundle.min.js', 'resources/web/assets/js/main.js'])

    {{-- Page-specific scripts --}}
    @stack('scripts')
</body>

</html>

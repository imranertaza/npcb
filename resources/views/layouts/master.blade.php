<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> @yield('title') - {{ config('app.name', 'Laravel Vue App') }}</title>

    <!-- Global Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Common CSS -->
    <link href="{{ asset('assets/css/common.css') }}" rel="stylesheet">

    <!-- Page-specific styles -->
    @stack('styles')
</head>
<body>
    <div id="app">
          <nav class="navbar navbar-expand-lg navbar-light bg-light mb-5  rounded">
      <div class="container">
          <a class="navbar-brand fw-bold text-primary" href="{{ route('home') }}">MyApp</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
              aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
              <ul class="navbar-nav">
                  <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                  <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
                  <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{ route('page.index') }}">Pages</a></li>
                  <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                  <li class="nav-item"><a class="nav-link" href="#cta">Get Started</a></li>
              </ul>
          </div>
      </div>
  </nav>
        @yield('content')
    </div>

    <!-- Global Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
    <!-- Page-specific scripts -->
    @stack('scripts')
</body>
</html>

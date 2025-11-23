@extends('layouts.master')

@section('title', 'Default Page')

@section('content')
<div class="container ">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col text-center">
            <h1 class="display-5 fw-bold text-primary">Default Page</h1>
            <p class="text-muted">This is the default layout for pages in your application.</p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="card-title">Page Content</h4>
                    <p class="card-text">
                        Default Page Content Goes Here
                    </p>
                    <div class="mt-3">
                        @yield('page-content')
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Quick Links</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="{{ route('home') }}">About Us</a></li>
                        <li class="list-group-item"><a href="{{ route('home') }}">Contact Us</a></li>
                        <li class="list-group-item"><a href="{{ route('page.index') }}">All Pages</a></li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Need Help?</h5>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">Support Center</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

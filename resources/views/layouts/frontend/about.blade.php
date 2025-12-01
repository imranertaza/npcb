@extends('layouts.master')

@section('title', 'About Us')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1 class="display-4 text-primary fw-bold">About Us</h1>
            <p class="lead text-muted">
                We are committed to delivering recruiter‑grade solutions with clarity, modularity, and real‑world usability.
            </p>
        </div>
        <div class="col-md-6 text-center">
            <img src="{{ getImageUrl('about_us_banner.jpg') }}" 
                 alt="About Us" 
                 class="img-fluid rounded shadow-sm">
        </div>
    </div>

    <!-- Content Section -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="card-title text-capitalize">Our Story</h2>
                    <p class="card-text">
                        @yield('page-content')
                    </p>
                </div>
            </div>
        </div>

        <!-- Sidebar Section -->
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Quick Facts</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Founded: 2020</li>
                        <li class="list-group-item">Team: 25+ professionals</li>
                        <li class="list-group-item">Focus: Scalable dashboards & workflows</li>
                    </ul>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Get in Touch</h5>
                    <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

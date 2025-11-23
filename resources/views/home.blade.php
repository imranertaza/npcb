@extends('layouts.master')

@section('title', 'Home')

@section('content')
<!-- Navigation -->
<div class="container my-5">
    <!-- Section 1: Hero -->
    <div class="row align-items-center mb-5">
        <div class="col-md-6">
            <h1 class="display-4 fw-bold text-primary">Welcome to Our Application</h1>
            <p class="lead text-muted">
                Recruiter‑grade dashboards and modular layouts with clarity and polish.
            </p>
            <a href="{{ route('home') }}" class="btn btn-primary btn-lg mt-3">Get Started</a>
        </div>
        <div class="col-md-6 text-center">
            <img src="https://placehold.co/600x400" alt="Hero" class="img-fluid rounded shadow-sm">
        </div>
    </div>

    <!-- Section 2: Features -->
    <div class="row text-center mb-5">
        <div class="col-md-4">
            <img src="https://placehold.co/200x200" class="mb-3 rounded-circle" alt="Feature 1">
            <h5>Modular Layouts</h5>
            <p class="text-muted">Reusable Blade structures for scalable workflows.</p>
        </div>
        <div class="col-md-4">
            <img src="https://placehold.co/200x200" class="mb-3 rounded-circle" alt="Feature 2">
            <h5>Smart Design</h5>
            <p class="text-muted">Bootstrap cards and grids for recruiter‑grade polish.</p>
        </div>
        <div class="col-md-4">
            <img src="https://placehold.co/200x200" class="mb-3 rounded-circle" alt="Feature 3">
            <h5>Future‑Proof</h5>
            <p class="text-muted">Admin/frontend separation with stackable assets.</p>
        </div>
    </div>

    <!-- Section 3: About -->
    <div class="row mb-5">
        <div class="col-md-6">
            <img src="https://placehold.co/500x300" alt="About" class="img-fluid rounded shadow-sm">
        </div>
        <div class="col-md-6 d-flex flex-column justify-content-center">
            <h2>About Us</h2>
            <p class="text-muted">
                We deliver recruiter‑grade solutions with clarity, modularity, and usability.
            </p>
            <a href="{{ route('home') }}" class="btn btn-outline-primary">Learn More</a>
        </div>
    </div>

    <!-- Section 4: Services -->
    <section class="mt-5">
        <div class="col text-center">
            <h2 class="mb-4">Our Services</h2>
        </div>
        <div class="row mb-5">
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <img src="https://placehold.co/400x200" class="card-img-top" alt="Service 1">
                    <div class="card-body">
                        <h5 class="card-title">Consulting</h5>
                        <p class="card-text text-muted">Helping teams architect scalable solutions.</p>
                        <a href="{{ route('home') }}" class="btn btn-sm btn-primary">Explore</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <img src="https://placehold.co/400x200" class="card-img-top" alt="Service 2">
                    <div class="card-body">
                        <h5 class="card-title">Design</h5>
                        <p class="card-text text-muted">Recruiter‑grade UI/UX with Bootstrap polish.</p>
                        <a href="{{ route('home') }}" class="btn btn-sm btn-primary">Explore</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <img src="https://placehold.co/400x200" class="card-img-top" alt="Service 3">
                    <div class="card-body">
                        <h5 class="card-title">Development</h5>
                        <p class="card-text text-muted">Layered, modular systems with Laravel + Vue.</p>
                        <a href="{{ route('home') }}" class="btn btn-sm btn-primary">Explore</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section 5: Testimonials -->
    <section class="mt-5">
        <div class="col text-center">
            <h2 class="mb-4">What People Say</h2>
        </div>
        <div class="row mb-5">
            <div class="col-md-6">
                <blockquote class="blockquote p-4 bg-light rounded shadow-sm">
                    <p>"This dashboard transformed our workflow!"</p>
                    <footer class="blockquote-footer">Client A</footer>
                </blockquote>
            </div>
            <div class="col-md-6">
                <blockquote class="blockquote p-4 bg-light rounded shadow-sm">
                    <p>"Recruiter‑grade polish that impressed our team."</p>
                    <footer class="blockquote-footer">Client B</footer>
                </blockquote>
            </div>
        </div>
    </section>

    <!-- Section 6: Call to Action -->
    <section class="row mb-5">
        <div class="col text-center">
            <h2 class="mb-3">Ready to Get Started?</h2>
            <p class="text-muted">Join us today and build recruiter‑grade solutions with clarity and polish.</p>
            <a href="{{ route('home') }}" class="btn btn-lg btn-success">Go Home</a>
        </div>
    </section>

</div>
@endsection

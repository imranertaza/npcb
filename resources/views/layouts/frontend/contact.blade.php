@extends('layouts.master')

@section('title', 'Contact Us')

@section('content')
<div class="container ">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-md-8">
            <h1 class="display-5 fw-bold text-primary">Get in Touch</h1>
            <p class="lead text-muted">
                We’d love to hear from you. Fill out the form below or reach us directly.
            </p>
        </div>
        <div class="col-md-4 text-center" style="max-height: 150px; overflow: hidden;">
            <img height="100" class="img-fluid rounded shadow-sm overflow-hidden" src="{{ getImageUrl('contact_us_banner.jpg') }}" 
                 alt="Contact Us" 
                 class="img-fluid rounded shadow-sm">
        </div>
    </div>

    <!-- Contact Form + Info -->
    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title mb-4">Send Us a Message</h4>
                    <form method="POST" action="{{ route('home') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="message" rows="5" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title">Our Office</h5>
                    <p class="card-text">
                        123 Recruiter Street<br>
                        Khulna, Bangladesh<br>
                        Phone: +880 1234 567890<br>
                        Email: info@example.com
                    </p>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Follow Us</h5>
                    <a href="#" class="btn btn-outline-primary btn-sm me-2">Facebook</a>
                    <a href="#" class="btn btn-outline-info btn-sm me-2">Twitter</a>
                    <a href="#" class="btn btn-outline-danger btn-sm">YouTube</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Yield for extra content -->
    <div class="mt-5">
        @yield('page-content')
    </div>
</div>
@endsection

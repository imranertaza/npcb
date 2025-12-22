@extends('layouts.master')

@section('title', 'Running Events')
@section('breadcrumb', 'Running Events')
@section('content')
    <!-- Running Event section start -->
    <section class="py-100 container">
        <div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-2 g-4">
            @forelse ($events as $event)
            <div>
                <a href="{{ route('running-events-details', $event->slug) }}">
                    <div class="gaming-news-card running-event-card card-smooth">
                        <img src="{{ getImageUrl($event->featured_image) }}" class="card-img-top" alt="{{ $event->title }}">
                        <div class="card-body">
                            <h5 class="title-five">
                                <a href="{{ route('running-events-details', $event->slug) }}"
                                    class="">{{ $event->title }}</a>
                            </h5>
                        </div>
                    </div>
                </a>
            </div>
            @empty
                <div class="col-12">
                    <p class="text-center">No events found.</p>
                </div>
            @endforelse

        </div>
        <div class=" text-center mt-60">
            {{ $events->links() }}
        </div>
    </section>
    <!-- Running Event contact section End -->
@endsection

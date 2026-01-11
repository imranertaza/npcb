@extends('layouts.master')

@section('title', $page->page_title)
@section('breadcrumb', $page->page_title)
@section('content')
    @yield('page-content')
    @php
        $events = \App\Models\Event::where('type', '2')
            ->where('event_scope', 0)
            ->where('status', 1)
            ->latest()
            ->paginate(10);
        $internationalEvents = \App\Models\Event::where('type', '2')
            ->where('event_scope', 1)
            ->where('status', 1)
            ->latest()
            ->paginate(10);
    @endphp
    <!-- Past Event section start -->
    <section class="container">
        <div class="mt-5">
            <h2 class="title-three pb-2">National Events</h2>
            <div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-2 g-4">
                @forelse ($events as $event)
                    <div>
                        <a href="{{ route('event-details', $event->slug) }}">
                            <div class="gaming-news-card running-event-card card-smooth">
                                <img src="{{ getImageCacheUrl($event->featured_image, 262, 230) }}" class="card-img-top"
                                    alt="{{ $event->title }}">
                                <div class="card-body">
                                    <h5 class="title-five min-h-38">
                                        <a href="{{ route('event-details', $event->slug) }}"
                                            class="">{{ truncateText($event->title, 40) }}</a>
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
        </div>
        <div class="mt-5 pb-100">
            <h2 class="title-three pb-2">International Events</h2>
            <div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-2 g-4">
                @forelse ($internationalEvents as $event)
                    <div>
                        <a href="{{ route('event-details', $event->slug) }}">
                            <div class="gaming-news-card running-event-card card-smooth">
                                <img src="{{ getImageCacheUrl($event->featured_image, 262, 230) }}" class="card-img-top"
                                    alt="{{ $event->title }}">
                                <div class="card-body">
                                    <h5 class="title-five min-h-38">
                                        <a href="{{ route('event-details', $event->slug) }}"
                                            class="">{{ truncateText($event->title, 40) }}</a>
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
        </div>

    </section>
    <!-- Running Event contact section End -->
@endsection

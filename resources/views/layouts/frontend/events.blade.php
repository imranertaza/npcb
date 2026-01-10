@extends('layouts.master')

@section('title', $page->page_title)
@section('breadcrumb', $page->page_title)
@section('content')
    @yield('page-content')
    @php
        $title = 'Events';
        $events = \App\Models\Event::where('type', '1')
            ->where('event_scope', 0)
            ->where('status', 1)
            ->latest()
            ->paginate(10);
        $internationalEvents = \App\Models\Event::where('type', '1')
            ->where('event_scope', 1)
            ->where('status', 1)
            ->latest()
            ->paginate(10);
    @endphp
    <!-- Running Event section start -->

    <!-- Running Event contact section End -->
@endsection

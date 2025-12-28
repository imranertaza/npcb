@extends('layouts.master')

@section('title', $page->page_title)
@section('breadcrumb', $page->page_title)
@section('content')
    @yield('page-content')

    @if (request()->slug == 'about-us')
        <!-- Our mission and vision Section Start  -->
        <section class="bg-primary py-100 ">
            @php
                $about_mission_vision = App\Models\Section::where('name', 'about_mission_vision')->first();
            @endphp
            <div class="container">
                <div class="row row-cols-md-2 justify-content-between align-items-center g-3">
                    <div class="">
                        <div class="">
                            <h2 class="title-two text-white">{{ $about_mission_vision->data['title'] }}</h2>
                            <span class="custom-underline mt-2 d-inline-block mb-3 w-25 bg-white"></span>
                            {!! $about_mission_vision->data['content'] !!}
                        </div>
                    </div>
                    <div class="">
                        <img class="img-fluid" src="{{ getImageCacheUrl($about_mission_vision->data['image'], 467,375) }}"
                            alt="">
                    </div>
                </div>
            </div>

        </section>
        <!-- Our mission and vision Section End -->
        <!-- Vision Start  -->
        @php
            $vision = App\Models\Section::where('name', 'about_vision')->first();
        @endphp
        <section class="container py-100 vision">
            <div
                class="row row-cols-md-2 flex-column-reverse flex-md-row justify-content-between align-items-center g-md-5">
                <div class="">
                    <img src="{{ getImageCacheUrl($vision->data['image'], 467,375) }}" class="img-fluid" alt="">
                </div>
                <div class="mb-4 mb-md-0">
                    <div class="d-inline-flex flex-column gap-2">
                        <h2 class="title-two d-inline-block">{{ $vision->data['title'] }}</h2>
                        <div class="custom-underline mt-0 mb-3 w-100"></div>
                    </div>
                    {!! $vision->data['content'] !!}

                </div>
            </div>
        </section>
        <!-- Vision End  -->
        <!-- Mission Start  -->
        <section class="container py-100 mission">
            @php
                $mission = App\Models\Section::where('name', 'about_mission')->first();
            @endphp
            <div class="row row-cols-md-2 justify-content-between align-items-center g-3">

                <div class="">
                    <div class="d-inline-flex flex-column gap-2">
                        <h2 class="title-two d-inline-block">{{ $mission->data['title'] }}</h2>
                        <div class="custom-underline mt-0 mb-3 w-100"></div>
                    </div>
                    {!! $mission->data['content'] !!}
                </div>
                <div class="text-md-end text-center">
                    <img src="{{ getImageCacheUrl($mission->data['image'], 467,375) }}" class="img-fluid" alt="">
                </div>

            </div>
        </section>
        <!-- Mission End  -->
    @endif

    @if (request()->slug == 'history')
        @php
            $history = App\Models\Section::where('name', 'history_history')->first();
        @endphp
        <!-- History Start  -->
        <section class="bg-primary py-100 mission">
            <div class="container">
                <div class="row row-cols-md-2 align-items-center gx-xl-5">
                    <div class="">
                        <div class="pe-md-3">
                            <div class="d-inline-flex flex-column gap-2">
                                <h2 class="title-two d-inline-block text-white">{{ $history->data['title'] }}</h2>
                                <div class="custom-underline mt-0 mb-3 w-100 bg-white"></div>
                            </div>
                            {!! $history->data['content'] !!}
                        </div>
                    </div>
                    <div class="">
                        <div class="ps-md-3">
                            <img src="{{ getImageCacheUrl($history->data['image'],520,507) }}" class="img-fluid w-100" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- History End  -->
    @endif

@endsection

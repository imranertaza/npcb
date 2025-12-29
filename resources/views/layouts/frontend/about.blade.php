@extends('layouts.master')

@section('title', $page->page_title)
@section('breadcrumb', $page->page_title)
@section('content')
    @yield('page-content')
    <!-- Our mission and vision Section Start  -->
    <section class="bg-primary py-100 ">
        <div class="container">
            <div class="row row-cols-md-2 justify-content-between align-items-center g-3">
                <div class="">
                    <div class="">  
                        <h2 class="title-two text-white">Our mission and vision</h2>
                        <span class="custom-underline mt-2 d-inline-block mb-3 w-25 bg-white"></span>
                        <p class="content-text text-white opacity-75">The mission & vision of the National
                            Paralympic Committee of Bangladesh
                            (NPCB)
                            is aligned with International Paralympic Committee's mission & vision. NPCB promotes the
                            Paralympic
                            movement within the country, enabling Para athletes to achieve sporting excellence.</p>

                    </div>
                </div>
                <div class="">
                    <img class="img-fluid" src="{{ asset('storage/web/about/mission-visson.png') }}" alt="">
                </div>
            </div>
        </div>

    </section>
    <!-- Our mission and vision Section End -->
    <!-- Vision Start  -->
    <section class="container py-100 vision">
        <div class="row row-cols-md-2 flex-column-reverse flex-md-row justify-content-between align-items-center g-md-5">
            <div class="">
                <img src="{{ asset('storage/web/about/vission.png') }}" class="img-fluid" alt="">
            </div>
            <div class="mb-4 mb-md-0">
                <div class="d-inline-flex flex-column gap-2">
                    <h2 class="title-two d-inline-block">Vision</h2>
                    <div class="custom-underline mt-0 mb-3 w-100"></div>
                </div>
                <p class="content-text text-dark-emphasis">Through its programs and events, the NPCB’s
                    vision is to foster a truly inclusive Bangladesh, where Para athletes have equal opportunities
                    to participate in sports at all levels and are recognized for their successes.</p>

            </div>
        </div>
    </section>
    <!-- Vision End  -->
    <!-- Mission Start  -->
    <section class="container py-100 mission">
        <div class="row row-cols-md-2 justify-content-between align-items-center g-3">

            <div class="">
                <div class="d-inline-flex flex-column gap-2">
                    <h2 class="title-two d-inline-block">Mission</h2>
                    <div class="custom-underline mt-0 mb-3 w-100"></div>
                </div>
                <ul class=" ps-3 ">
                    <li class="text-dark-emphasis content-text mb-4">Supporting members and providing platforms
                        for Para athletes to achieve their best in
                        sports, from national competitions to the Paralympic Games.</li>
                    <li class="text-dark-emphasis content-text mb-4">Using sports as a tool for social and
                        cultural development, fostering hope, independence,
                        and showing that "disability is not inability</li>
                    <li class="text-dark-emphasis content-text mb-4">Leading the development of various Para
                        sports and helping to train coaches and
                        administrators</li>
                    <li class="text-dark-emphasis content-text mb-4">Ensuring the observance of the International
                        Paralympic Committee's rules and regulations
                        within Bangladesh</li>
                </ul>

            </div>
            <div class="text-md-end text-center">
                <img src="{{ asset('storage/web/about/mission.png') }}" class="img-fluid" alt="">
            </div>

        </div>
    </section>
    <!-- Mission End  -->
@endsection

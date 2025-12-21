@extends('layouts.frontend.' . $page->temp)
@section('page-content')
    <!-- About section start  -->
    <section class="container py-100">
        <h2 class="visually-hidden">About The National Paralympic Committee of Bangladesh</h2>
        <div class="">

            <img class="img-fluid" src="{{ getImageCacheUrl($page->f_image,1140, 375) }}" alt="">
        </div>
        <div class="text-center mx-md-5 text-dark-emphasis">
            {!! $page->page_description !!}
        </div>
    </section>
    <!-- About section End  -->
@endsection

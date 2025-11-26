@extends('layouts.frontend.'.$page->temp)
@section('page-content')

<h1>{{ $page->page_title }}</h1>
<img src="{{ getImageUrl($page->f_image) }}" alt="{{ $page->page_title }}">
{!! $page->page_description !!}

@endsection
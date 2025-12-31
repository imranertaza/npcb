@extends('layouts.master')

@section('title', $page->page_title)
@section('breadcrumb', $page->page_title)
@section('content')
    @yield('page-content')
@endsection

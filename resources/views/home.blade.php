@extends('layouts.app')
@section('title', 'Home')
@section('content')

    @include('sections.hero')
    @include('sections.posts')
    @include('sections.about')
    @include('sections.create_posts')
    @include('sections.footer')

@endsection

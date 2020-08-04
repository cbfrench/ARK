{{--
  Template Name: Check Page Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.page-header')
    @include('partials.check-page')
  @endwhile
@endsection

{{--
  Template Name: Verify Page Template
--}}

@extends('layouts.app')

@section('content')
  @while(have_posts()) @php the_post() @endphp
    @include('partials.page-header')
    @include('partials.verify-page')
  @endwhile
@endsection

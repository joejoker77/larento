@php /** @var App\Entities\Blog\Post $post */ @endphp
@extends('layouts.index')
@section('content')
    <div class="blog post" id="postPage">
        <h1 class="h1">{{$post->title}}</h1>
        <div class="d-flex flex-column flex-lg-row mb-0 mb-lg-5">
            <div class="col-lg-3">
                <aside class="sticky-top">
                    <x-menu handler="blogMenu" />
                </aside>
            </div>
            <div class="col-lg-9">
                @if($post->photos->isNotEmpty())
                    <div class="post_photo-main">
                        <img src="{{ $post->photos->first()->getPhoto('large') }}" alt="{{$post->photos->first()->alt_tag}} @if($post->photos->first()->alt_tag) размер большой @endif">
                    </div>
                @endif
                <div class="post_description mb-5">
                    {!! $post->content !!}
                </div>
            </div>
        </div>
    </div>
    <x-promotion-module />
@endsection

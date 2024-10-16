@php /** @var App\Entities\Blog\Post $post */ @endphp
@extends('layouts.index')
@section('content')
    <div class="blog post" id="postPage">
        <div class="row mb-5">
            <h1 class="h1">
                {{$post->title}}
            </h1>
            <div class="col-lg-3">
                <aside class="w-100 sticky-top">
                    <x-menu handler="blogMenu" />
                </aside>
            </div>
            <div class="w-75">
                <div class="col-lg-12">
                    @if($post->photos->isNotEmpty())
                        <div class="post_photo-main">
                            <img src="{{ $post->photos->first()->getPhoto('large') }}" alt="{{$post->photos->first()->alt_tag}}">
                        </div>
                    @endif
                </div>
                <div class="col-lg-12">
                    {!! $post->content !!}
                </div>
            </div>
        </div>
    </div>
    <x-promotion-module />
@endsection

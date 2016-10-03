@extends('layouts.master')

@section ('meta')

  @if ((isset($settings->title))AND(isset($settings->site_name))AND(!isset($categoryTitle)))
  <title>{{$settings->title.' | '}}{{$settings->site_name}}</title>
  @elseif(isset($settings->title)AND(isset($categoryTitle))AND(isset($settings->site_name)))
  <title>{{$settings->title.' | '}}{{$categoryTitle.' | '}}{{$settings->site_name}}</title>
  @elseif(isset($settings->title)AND(isset($categoryTitle))AND(isset($sets->site_name)))
  <title>{{$settings->title.' | '}}{{$categoryTitle.' | '}}{{$sets->site_name}}</title>
  @elseif(isset($settings->title)AND(isset($categoryTitle))AND(!isset($sets->site_name))AND(!$settings->site_name))
  <title>{{$settings->title.' | '}}{{$categoryTitle}}</title>
  @elseif(isset($settings->title)AND(!isset($categoryTitle))AND(!isset($settings->site_name)))
  <title>{{$settings->title}}</title>
  @endif

  @if (isset($settings->description))
  <meta name="description" content="{{$settings->description}}">
  @endif

  @if (isset($settings->keywords))
  <meta name="keywords" content="{{$settings->keywords}}" >
  @endif

@stop


  @if (isset($settings->description))
  <meta name="description" content="{{$settings->description}}">
  @endif

  @if (isset($settings->keywords))
  <meta name="keywords" content="{{$settings->keywords}}" >
  @endif

@stop

@section('content')
<div class="container">
@if ($questions->currentPage() == 1)    
<div class="jumbotron" style="margin-top: 60px;">
  {!!trans('messages.jumbotron-part1')!!}
  <p><a class="btn btn-primary btn-lg makeQuestion" data-toggle="modal" data-target="#make-question">{{trans('messages.video-question-up')}}!</a></p>
  <p>{{trans('messages.jumbotron-part2')}} <a href="#video-answers">{{trans('messages.video-answers')}}</a>!</p>
  <p>{{trans('messages.jumbotron-part3')}} <a href="#video-questions">{{trans('messages.video-questions')}}</a>.</p>
  <a href="https://play.google.com/store/apps/details?id=me.vimm.den.vimm" target="_blank">
      <img src="{{ asset('/css/google_play.png') }}" height="40" alt="Vimm.me on Google Play Market">
  </a>
</div>
@endif  

<a name="video-questions"></a>   
@include('_partials.questions')
</div>
@stop

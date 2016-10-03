@extends('layouts.master')

@section ('meta')

  @if(isset($question->title) AND isset($settings->site_name))
  <title>{{trans('messages.you-question')}} "{{$question->title}}?" {{trans('messages.from')}} {{$settings->site_name}}</title>
  @elseif(!isset($question->title) AND isset($settings->site_name))
  <title>{{trans('messages.you-question')}} {{trans('messages.from')}} {{$settings->site_name}}.</title>
  @endif

  @if (isset($question->description))
  <meta name="description" content="{{trans('messages.video-question')}} {{$question->description}}">
  @endif

  @if (isset($question->keywords))
  <meta name="keywords" content="{{$settings->keywords}}, {{$question->keywords}}" >
  @endif

@stop

@section('content')
<div class="container" style="margin-top:75px">
   
   @include('_partials.question')
    
</div>
@stop


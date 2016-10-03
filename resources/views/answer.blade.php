@extends('layouts.master')

@section ('meta')

  @if(isset($answer->title) AND isset($settings->site_name))
  <title>{{trans('messages.you-got-answer')}} "{{$answer->title}}!" {{trans('messages.from')}} {{$settings->site_name}}</title>
  @elseif(!isset($answer->title) AND isset($settings->site_name))
  <title>{{trans('messages.you-got-answer')}} {{trans('messages.from')}} {{$settings->site_name}}!</title>
  @endif

  @if (isset($answer->description))
  <meta name="description" content="{{trans('messages.video-answer')}} {{$answer->description}}">
  @endif

  @if (isset($answer->keywords))
  <meta name="keywords" content="{{$settings->keywords}}, {{$answer->keywords}}" >
  @endif

@stop

@section('content')
<div class="container" style="margin-top:75px">
   
   @include('_partials.answerOne')
    
</div>
@stop


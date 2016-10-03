
<div class="page-header">
    <h2>{{trans('messages.video-questions-and-answers')}}</h1>
</div>

<div class="row">
    @if ($questions && count($questions))

    {!! str_replace('a href', 'a rel="nofollow" href', $questions->appends(Input::except('page'))->render()) !!}

    @endif
</div>


@if ($questions && count($questions))

@foreach ($questions as $question)
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 40px">

    <div class="title" style="padding:10px 10px 10px 0px"> 

        <a href="/question/{{$question->slug}}" title="{{$question->title}}">{{$question->title}}</a>
        - 
        {{$question->created_at}} &nbsp; 
        <a class="btn btn-primary btn-lg" style="padding:2px;border-radius:5px" href="/question/{{$question->slug}}">{{trans('messages.share')}}</a>
        <div class="pull-right">
            <div class="row" style="">


                @include('_partials.rating', ['object' => $question])

            </div>
        </div> 
    </div>

    <video src="{{$question->url}}" controls style="width:100%;max-height:500px;border: 1px dotted white;" class="vidos" data-id="{{$question->slug}}"></video>
    @if (count($question->answers->all())>0)
    <b>{{trans('messages.video-answers-up')}}:</b>
    <a name="video-answers"></a>
    @endif

    <div class="jumbotron"> 
        <div id="answers{{$question->id}}">
            @if (count($question->answers->all())>0)

            @foreach ($question->answers->take(3) as $answer)

            @include('_partials.answer', ['answer' => $answer])

            @endforeach


            @else
            <p>{{trans('messages.video-answers-no-yet')}}</p>

            @endif
        </div>

        @if (count($question->answers->all())>3)
        <br>
        <div class="row" id="moreAnswer{{$question->id}}">
            <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"></div>
            <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
                <a class="btn btn-primary btn-lg answers-more" data-offset="3" data-id="{{$question->id}}" title="{{trans('messages.video-answers-more-load')}}" alt="{{trans('messages.video-answers-more-load')}}">{{trans('messages.more')}}</a>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"></div>
            </div>
        </div>

        @endif


    </div>
    <a class="btn btn-primary btn-lg makeAnswer" data-id="{{$question->id}}" data-toggle="modal" data-target="#make-answer{{$question->id}}">{{trans('messages.make-answer-up')}}</a>
    <hr>
</div>

@include('_partials.modalBigAnswer', ['question'=>$question])

@endforeach


@else
<p>{{trans('messages.empty-yet')}}</p>

@endif

<div class="row"> 

    @if ($questions && count($questions))

    {!! str_replace('a href', 'a rel="nofollow" href', $questions->appends(Input::except('page'))->render()) !!}

    @endif
</div>
    



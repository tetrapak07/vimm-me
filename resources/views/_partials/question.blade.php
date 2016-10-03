<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="padding-bottom: 40px">

    <img src="{{$question->url_thumb}}"  width="0" height="0" alt="{{$question->title}}"> 
    <div class="title" style="padding:10px 10px 10px 0px"> 

        <span style="color: #df691a">{{$question->title}}</span>
        - 
        {{$question->created_at}}    
        <div class="pull-right">
            <div class="row" style="">


                @include('_partials.rating', ['object' => $question])

            </div>
        </div> 
    </div>
    <div class="row">
        <video id="playerid" src="/{{$question->url}}" controls style="width:100%;"></video>

    </div>
    <br>
    <div class="row">
        <span class='st_googleplus_large' displayText='Google +'></span>
        <span class='st_vkontakte_large' displayText='Vkontakte'></span>
        <span class='st_odnoklassniki_large' displayText='Odnoklassniki'></span>
        <span class='st_facebook_large' displayText='Facebook'></span>
        <span class='st_twitter_large' displayText='Tweet'></span>
        <span class='st_mail_ru_large' displayText='mail.ru'></span>
        <span class='st_reddit_large' displayText='Reddit'></span>
        <span class='st_tumblr_large' displayText='Tumblr'></span>
        <span class='st_myspace_large' displayText='MySpace'></span>
        <span class='st_digg_large' displayText='Digg'></span>
        <span class='st_livejournal_large' displayText='LiveJournal'></span>
        <span class='st_blogger_large' displayText='Blogger'></span>
        <span class='st_linkedin_large' displayText='LinkedIn'></span>
        <span class='st_pinterest_large' displayText='Pinterest'></span>
        <span class='st_email_large' displayText='Email'></span>
    </div>
    @if (count($question->answers->all())>0)
    <b>{{trans('messages.video-answers-up')}}:</b>
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
                <a style="cursor: pointer;text-decoration: none;border-bottom: 1px dashed" onclick="return false;" class="answers-more" data-offset="3" data-id="{{$question->id}}" title="{{trans('messages.video-answers-more-load')}}" alt="{{trans('messages.video-answers-more-load')}}">{{trans('messages.more')}}</a>
                <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5"></div>
            </div>
        </div>

        @endif

    </div>
    <a class="btn btn-primary btn-lg makeAnswer" data-id="{{$question->id}}" data-toggle="modal" data-target="#make-answer{{$question->id}}">{{trans('messages.make-answer-up')}}</a>
    <hr>

    <br><br>

</div>

@include('_partials.modalBigAnswer', ['question'=>$question])

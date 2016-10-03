<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="">

    <img src="{{$answer->url_thumb}}"  width="0" height="0" alt="{{$answer->title}}">
    <div class="title"> 

        <span style="color: #df691a">{{$answer->title}}</span>
        - {{$answer->created_at}} 
        <div class="pull-right">
            <div class="row">


                @include('_partials.rating', ['object' => $answer])

            </div>
        </div>
    </div>
    <video id="playerid" src="/{{$answer->url}}" controls style="width:100%;"></video>

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
    <br><br>

</div>

        



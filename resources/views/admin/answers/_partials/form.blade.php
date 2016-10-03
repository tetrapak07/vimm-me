<div class="col-lg-6 col-md-6 col-sm-6">
Question ID:    
<select class="form-control" name="question_id" type="text" style="" id="question_id">
        @foreach ($questions as $question)
        <option question-id="{{$question->id}}" value="{{$question->id}}" @if ($answer->question_id == $question->id) selected @endif>
                ID: {{$question->id}}; TITLE: {{$question->title}}
        </option>
        @endforeach
</select>    
User ID (Member ID):    
<select class="form-control" name="member_id" type="text" style="" id="member_id">
        @foreach ($members as $member)
        <option member-id="{{$member->id}}" value="{{$member->id}}" @if ($answer->member_id == $member->id) selected @endif>
                IP: {{$member->ip}}; ID: {{$member->id}}
        </option>
        @endforeach
</select> 
@if ($submit_text!='New Answer')
Rating ID (Rating ID):    
<select class="form-control" name="rating_id" type="text" style="" id="rating_id" disabled>
        @foreach ($ratings as $rating)
        <option rating-id="{{$rating->id}}" value="{{$rating->id}}" @if ($answer->rating_id == $rating->id) selected @endif>
                ID: {{$rating->id}}
        </option>
        @endforeach
</select> 
@endif
<div class="form-group">
    {!! Form::label('title', 'Title:') !!}

    <input class="form-control" name="title" type="text" value="{{!old('title') ? $answer->title : old('title')}}" id="title">

</div>
@if ($submit_text!='New Answer')
<div class="form-group">
    {!! Form::label('slug', 'Slug:') !!}

    <input class="form-control" name="slug" type="text" value="{{$answer->slug}}" id="slug" disabled>

</div>
@endif
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}

    <textarea class="form-control" name="description" cols="50" rows="2" id="description">{{$answer->description}}</textarea>

</div>


<div class="form-group">
    {!! Form::label('keywords', 'Keywords:') !!}

    <input class="form-control" name="keywords" type="text" value="{{$answer->keywords}}" id="keywords">


</div>
<div class="form-group">
    {!! Form::label('content', 'Content:') !!}
    <textarea class="form-control" name="content" cols="50" rows="4" id="content">{{$answer->content}}</textarea>
</div>

<div class="form-group">
    {!! Form::label('visible', 'On(1)/Off(0):') !!}

    <input class="form-control" name="visible" type="text" value="@if(isset($answer->visible)){{$answer->visible}}@else{{1}}@endif" id="visible">


</div>
<div class="form-group">
    <input class="form-control" name="page" type="hidden" value="<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" id="page">
    {!! Form::submit($submit_text, ['class'=>'btn primary']) !!}


    <input class="btn primary close_win" data-id="{{$answer->id}}" type="reset" value="Close">
</div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6">
<div class="form-group">
    {!! Form::label('thumb', 'Thumb:') !!}
    <input class="form-control" name="url_thumb" type="text" value="{{$answer->url_thumb}}" id="url_thumb">
    {!! Form::label('url', 'Url:') !!}
    <input class="form-control" name="url" type="text" value="{{$answer->url}}" id="url">
    
    <br>
    <div class="picture-preview">
        <video src="/{{$answer->url}}" controls height="auto" width="100%" scrolling="no"></video>
    </div>
</div>
    
<div class="form-group">
    {!! Form::label('rem', 'Video ID:') !!}

    <input class="form-control" name="rem" type="text" value="{{$answer->rem}}" id="rem">

</div>  
   

</div>   


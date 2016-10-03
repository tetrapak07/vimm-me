<div class="col-lg-6 col-md-6 col-sm-6">
<div class="form-group">    
Answer ID:    
<select class="form-control" name="answer_id" type="text" style="" id="answer_id" disabled>
        @foreach ($answers as $answer)
        <option answer-id="{{$answer->id}}" value="{{$answer->id}}" @if ($qrating->answer_id == $answer->id) selected @endif>
                ID: {{$answer->id}}; TITLE: {{$answer->title}}
        </option>
        @endforeach
</select>    
User ID (Member ID):    
<select class="form-control" name="member_id" type="text" style="" id="member_id">
        @foreach ($members as $member)
        <option member-id="{{$member->id}}" value="{{$member->id}}" @if ($qrating->member_id == $member->id) selected @endif>
                IP: {{$member->ip}}; ID: {{$member->id}}
        </option>
        @endforeach
</select>  
</div>

 <div class="form-group">
    {!! Form::label('summary', 'Summary Rating:') !!}

    <input class="form-control" name="summary" type="text" value="{{!old('summary') ? $qrating->summary : old('summary')}}" id="summary">

</div> 


<div class="form-group">
    <input class="form-control" name="page" type="hidden" value="<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" id="page">
    {!! Form::submit($submit_text, ['class'=>'btn primary']) !!}


    <input class="btn primary close_win" data-id="{{$qrating->id}}" type="reset" value="Close">
</div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6">
 <div class="form-group">
    {!! Form::label('rating_plus', 'Rating Plus:') !!}

    <input class="form-control" name="rating_plus" type="text" value="{{!old('rating_plus') ? $qrating->rating_plus : old('rating_plus')}}" id="rating_plus">

</div>

<div class="form-group">
    {!! Form::label('rating_minus', 'Rating Minus:') !!}

    <input class="form-control" name="rating_minus" type="text" value="{{!old('rating_minus') ? $qrating->rating_minus : old('rating_minus')}}" id="rating_minus">

</div>


</div>


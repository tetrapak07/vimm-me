<div class="col-lg-6 col-md-6 col-sm-6">
     
<div class="form-group">
    {!! Form::label('ip', 'IP:') !!}

    <input class="form-control" name="ip" type="text" value="{{!old('ip') ? $member->ip : old('ip')}}" id="ip">

</div>

<div class="form-group">
    {!! Form::label('questions_restrict', 'Questions Restrict:') !!}

    <input class="form-control" name="questions_restrict" type="text" value="{{!old('questions_restrict') ? $member->questions_restrict : old('questions_restrict')}}" id="questions_restrict">

</div>
    
<div class="form-group">
    {!! Form::label('questions_restrict_day', 'Questions Restrict Day:') !!}

    <input class="form-control" name="questions_restrict_day" type="text" value="{{!old('questions_restrict_day') ? $member->questions_restrict_day: old('questions_restrict_day')}}" id="questions_restrict_day">

</div> 
    
<div class="form-group">
    {!! Form::label('answers_restrict', 'Answers Restrict:') !!}

    <input class="form-control" name="answers_restrict" type="text" value="{{!old('answers_restrict') ? $member->answers_restrict : old('answers_restrict')}}" id="answers_restrict">

</div>
    
<div class="form-group">
    {!! Form::label('answers_restrict_day', 'Answers Restrict Day:') !!}

    <input class="form-control" name="answers_restrict_day" type="text" value="{{!old('answers_restrict_day') ? $member->answers_restrict_day: old('answers_restrict_day')}}" id="answers_restrict_day">

</div> 
   
<div class="form-group">
    <input class="form-control" name="page" type="hidden" value="<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" id="page">
    {!! Form::submit($submit_text, ['class'=>'btn primary']) !!}


    <input class="btn primary close_win" data-id="{{$member->id}}" type="reset" value="Close">
</div>
</div>


<div class="col-lg-6 col-md-6 col-sm-6">    
<div class="form-group">
    {!! Form::label('restrict_flag', 'Restrict Flag:') !!}

    <input class="form-control" name="restrict_flag" type="text" value="{{!old('restrict_flag') ? $member->restrict_flag: old('restrict_flag')}}" id="restrict_flag">

</div>       

<div class="form-group">
    {!! Form::label('visible', 'On(1)/Off(0):') !!}

    <input class="form-control" name="visible" type="text" value="{{ !$member->visible ? '1' : $member->visible}}" id="visible">


</div>
    
<div class="form-group">
    {!! Form::label('rem', 'Rem:') !!}

    <input class="form-control" name="rem" type="text" value="{{$member->rem}}" id="rem">

</div> 
</div> 




     
   




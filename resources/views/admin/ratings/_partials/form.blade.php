<div class="col-lg-6 col-md-6 col-sm-6">
<div class="form-group">    
   
  
</div>

 <div class="form-group">
    {!! Form::label('summary', 'Summary Rating:') !!}

    <input class="form-control" name="summary" type="text" value="{{!old('summary') ? $rating->summary : old('summary')}}" id="summary">

</div> 


<div class="form-group">
    <input class="form-control" name="page" type="hidden" value="<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" id="page">
    {!! Form::submit($submit_text, ['class'=>'btn primary']) !!}


    <input class="btn primary close_win" data-id="{{$rating->id}}" type="reset" value="Close">
</div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6">
 <div class="form-group">
    {!! Form::label('rating_plus', 'Rating Plus:') !!}

    <input class="form-control" name="rating_plus" type="text" value="{{!old('rating_plus') ? $rating->rating_plus : old('rating_plus')}}" id="rating_plus">

</div>

<div class="form-group">
    {!! Form::label('rating_minus', 'Rating Minus:') !!}

    <input class="form-control" name="rating_minus" type="text" value="{{!old('rating_minus') ? $rating->rating_minus : old('rating_minus')}}" id="rating_minus">

</div>


</div>

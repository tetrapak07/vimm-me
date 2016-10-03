<div class="form-group">
    {!! Form::label('queue', 'Queue:') !!}

     <input class="form-control" name="queue" type="text" value="{{$queue->queue}}" id="queue" readonly>

</div>
<div class="form-group">
    {!! Form::label('payload', 'Payload:') !!}
  
     <textarea class="form-control" name="payload" cols="50" rows="10" id="payload" readonly>{{$queue->payload}}</textarea>

</div>

<div class="form-group">
    {!! Form::label('attempts', 'Attempts:') !!}
   
      <input class="form-control" name="attempts" type="text" value="{{$queue->attempts}}" id="attempts" readonly>
      
</div>
<div class="form-group">
    {!! Form::label('reserved', 'Reserved:') !!}
   
      <input class="form-control" name="reserved" type="text" value="{{$queue->reserved}}" id="reserved" readonly>
      
</div>
<div class="form-group">
    {!! Form::label('reserved_at', 'reserved_at:') !!}
   
      <input class="form-control" name="reserved_at" type="text" value="{{ date('F d, Y h:i:s', strtotime($queue->reserved_at)) }}" id="reserved_at" readonly>
      
</div>
<div class="form-group">
    {!! Form::label('available_at', 'available_at:') !!}
   
      <input class="form-control" name="available_at" type="text" value="{{ date('F d, Y h:i:s', strtotime($queue->available_at)) }}" id="available_at" readonly>
      
</div>
<div class="form-group">
    {!! Form::label('created_at', 'created_at:') !!}
   
      <input class="form-control" name="created_at" type="text" value="{{ date('F d, Y h:i:s', strtotime($queue->created_at)) }}" id="created_at" readonly>
      
</div>
<div class="form-group">
     <input class="form-control" name="page" type="hidden" value="<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" id="page">
    {!! Form::submit($submit_text, ['class'=>'btn primary']) !!}
    
  
    <input class="btn primary close_win" data-id="{{$queue->id}}" type="reset" value="Close">
</div>



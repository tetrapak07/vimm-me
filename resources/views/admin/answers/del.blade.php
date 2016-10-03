@extends('part_app')

@section('content')
<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Delete answer with id "{{$id}}"</h4>
      </div>
      <div class="modal-body">
        <p>Are you shure?</p>
      </div>
      <div class="modal-footer">
          {!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('admin.answers.destroy', $id))) !!}
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
         <input class="form-control" name="page" type="hidden" value="<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" id="page">
         <input class="form-control" name="memberSel" type="hidden" value="<?php if (isset($_GET['memberSel'])) echo $_GET['memberSel']; ?>" id="memberSel">
          <input class="form-control" name="questionSel" type="hidden" value="<?php if (isset($_GET['questionSel'])) echo $_GET['questionSel']; ?>" id="questionSel">
           <input class="form-control" name="ratingSel" type="hidden" value="<?php if (isset($_GET['ratingSel'])) echo $_GET['ratingSel']; ?>" id="ratingSel">
         {!! Form::submit('Yes', array('class' => 'btn btn-primary')) !!}
                     
             {!! Form::close() !!}
      </div>
@stop


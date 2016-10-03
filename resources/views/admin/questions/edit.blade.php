@extends('part_app')

@section('content')
<div class="container" style='width:auto;  margin-top: 20px;'>

    <div class="col-md-13 col-md-offset">
        
        <div class="panel panel-default">
            
            <div class="panel-heading">Edit Question "{{ $question->title }}" with ID "{{ $question->id }}" </div>

            <div class="panel-body">

                {!! Form::model($question, ['method' => 'PATCH', 'route' => ['admin.questions.update', $question->id],'role'=>'form','class'=>'block small center login','style'=>'height:80%', 'files'=>true]) !!}
                @include('admin/questions/_partials/form', ['submit_text' => 'Edit Question', 'members' => $members])
                {!! Form::close() !!}  

            </div>

        </div>

    </div>

</div>
@stop



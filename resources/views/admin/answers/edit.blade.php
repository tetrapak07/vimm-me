@extends('part_app')

@section('content')
<div class="container" style='width:auto;  margin-top: 20px;'>

    <div class="col-md-13 col-md-offset">
        
        <div class="panel panel-default">
            
            <div class="panel-heading">Edit Answer "{{ $answer->title }}" with ID "{{ $answer->id }}" </div>

            <div class="panel-body">

                {!! Form::model($answer, ['method' => 'PATCH', 'route' => ['admin.answers.update', $answer->id],'role'=>'form','class'=>'block small center login','style'=>'height:80%', 'files'=>true]) !!}
                @include('admin/answers/_partials/form', ['submit_text' => 'Edit Answer', 'members' => $members, 'questions' => $questions,'ratings' => $ratings])
                {!! Form::close() !!}  

            </div>

        </div>

    </div>

</div>
@stop


@extends('part_app')

@section('content')

<div class="container" style='width:auto;  margin-top: 20px;'>

    <div class="col-md-13 col-md-offset">
        <div class="panel panel-default">
            <div class="panel-heading">Create New Question </div>

            <div class="panel-body">

                {!! Form::model(new App\Models\Question, ['route' => ['admin.questions.store'],'role'=>'form','class'=>'block small center login','style'=>'height:80%']) !!}
                @include('admin/questions/_partials/form', ['submit_text' => 'New Question', 'members' => $members])
                {!! Form::close() !!}  

            </div>

        </div>

    </div>

</div>
@stop

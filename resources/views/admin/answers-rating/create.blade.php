@extends('part_app')

@section('content')

<div class="container" style='width:auto;  margin-top: 20px;'>

    <div class="col-md-13 col-md-offset">
        <div class="panel panel-default">
            <div class="panel-heading">Create New Answer Rating </div>

            <div class="panel-body">

                {!! Form::model(new App\Models\AnswerRating, ['route' => ['admin.answers-rating.store'],'role'=>'form','class'=>'block small center login','style'=>'height:80%']) !!}
                @include('admin/answers-rating/_partials/form', ['submit_text' => 'New Answer Rating'])
                {!! Form::close() !!}  

            </div>

        </div>

    </div>

</div>
@stop


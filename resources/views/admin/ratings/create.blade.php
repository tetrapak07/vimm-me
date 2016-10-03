@extends('part_app')

@section('content')

<div class="container" style='width:auto;  margin-top: 20px;'>

    <div class="col-md-13 col-md-offset">
        <div class="panel panel-default">
            <div class="panel-heading">Create New Rating </div>

            <div class="panel-body">

                {!! Form::model(new App\Models\Rating, ['route' => ['admin.ratings.store'],'role'=>'form','class'=>'block small center login','style'=>'height:80%']) !!}
                @include('admin/ratings/_partials/form', ['submit_text' => 'New Rating'])
                {!! Form::close() !!}  

            </div>

        </div>

    </div>

</div>
@stop


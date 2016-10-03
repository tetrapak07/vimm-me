@extends('part_app')

@section('content')
<div class="container" style='width:auto;  margin-top: 20px;'>

    <div class="col-md-13 col-md-offset">
        
        <div class="panel panel-default">
            
            <div class="panel-heading">Edit Rating "{{ $rating->title }}" with ID "{{ $rating->id }}" </div>

            <div class="panel-body">

                {!! Form::model($rating, ['method' => 'PATCH', 'route' => ['admin.ratings.update', $rating->id],'role'=>'form','class'=>'block small center login','style'=>'height:80%', 'files'=>true]) !!}
                @include('admin/ratings/_partials/form', ['submit_text' => 'Edit Rating'])
                {!! Form::close() !!}  

            </div>

        </div>

    </div>

</div>
@stop


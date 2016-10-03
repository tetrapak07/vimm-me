@extends('part_app')

@section('content')
<div class="container" style='width:auto;  margin-top: 20px;'>

    <div class="col-md-13 col-md-offset">
        
        <div class="panel panel-default">
            
            <div class="panel-heading">Edit Question Rating "{{ $qrating->title }}" with ID "{{ $qrating->id }}" </div>

            <div class="panel-body">

                {!! Form::model($qrating, ['method' => 'PATCH', 'route' => ['admin.questions-rating.update', $qrating->id],'role'=>'form','class'=>'block small center login','style'=>'height:80%', 'files'=>true]) !!}
                @include('admin/questions-rating/_partials/form', ['submit_text' => 'Edit Question Rating', 'members' => $members, 'questions' => $questions])
                {!! Form::close() !!}  

            </div>

        </div>

    </div>

</div>
@stop


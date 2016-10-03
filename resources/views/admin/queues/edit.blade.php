@extends('part_app')

@section('content')
<div class="container" style='width:auto;  margin-top: 20px;'>

    <div class="col-md-13 col-md-offset">
        
        <div class="panel panel-default">
            
            <div class="panel-heading">Edit Queue "{{ $queue->id }}"</div>

            <div class="panel-body">

                {!! Form::model($queue, ['method' => 'PATCH', 'route' => ['admin.queues.update', $queue->id],'role'=>'form','class'=>'block small center login','style'=>'height:80%']) !!}
                @include('admin/queues/_partials/form', ['submit_text' => 'Edit Queue'])
                {!! Form::close() !!}  

            </div>

        </div>

    </div>

</div>
@stop


@extends('part_app')

@section('content')
<div class="container" style='width:auto;  margin-top: 20px;'>

    <div class="col-md-13 col-md-offset">
        
        <div class="panel panel-default">
            
            <div class="panel-heading">Edit Member "{{ $member->title }}" with ID "{{ $member->id }}" </div>

            <div class="panel-body">

                {!! Form::model($member, ['method' => 'PATCH', 'route' => ['admin.members.update', $member->id],'role'=>'form','class'=>'block small center login','style'=>'height:80%', 'files'=>true]) !!}
                @include('admin/members/_partials/form', ['submit_text' => 'Edit Member'])
                {!! Form::close() !!}  

            </div>

        </div>

    </div>

</div>
@stop


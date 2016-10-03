<div class="container">
    
    @if (Session::has('message'))
            <div class="flash alert-info">
                <p>{!! Session::get('message') !!}</p>
            </div>
    @endif
   @if (Session::has('error'))
            <div class="flash alert-danger">
                <p>{!! Session::get('error') !!}</p>
            </div>
   @endif        
            @if ($errors->any())
            <div class='flash alert-danger'>
                @foreach ( $errors->all() as $error )
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif
  
	<div class="col-md-10 col-md-offset">
   <div class="panel panel-default">
				<div class="panel-heading">Administration Panel - Очереди</div>

				<div class="panel-body">
          
             <div class="table-responsive"> 
                 <table class="table table-bordered" data-search="true" data-url="">
                     <thead>
                         <tr>
                             <th data-sortable="true">ID</th>
                             <th>queue</th>
                             <th>payload</th>
                             <th>attempts</th>
                             <th>reserved</th>
                             <th>created</th>
                             <th>Operations</th>
                         </tr>
                     </thead>
                     <tbody>
                         @foreach ($queues as $queue)
                         <tr>
                             <td >{{$queue->id}}</td>
                             <td>{{$queue->queue}}</td>
                             <td>
                                 <div data-toggle="tooltip" data-placement="bottom" title="{{$queue->payload}}">
                                     {!!mb_strimwidth($queue->payload, 0, 20, "...")!!}
                                 </div>
                             </td>
                             <td>{{$queue->attempts}}</td>
                             <td>{{$queue->reserved}}</td>
                             <td>{{ date('F d, Y h:i:s', strtotime($queue->created_at)) }}</td>
                             <td>
                                 
                                 <a href="/admin/queues/{{$queue->id}}/edit?page=<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" class="btn btn-info" data-toggle="modal" data-target="#editQueue{{$queue->id}}">Edit</a>
                                 <div id="editQueue{{$queue->id}}" class="modal fade" >
                                     <div class="modal-dialog">
                                         <div class="modal-content">

                                         </div>
                                     </div>
                                 </div>

                                 <a href="/admin/queues/del/{{$queue->id}}?page=<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" class="btn btn-danger" data-toggle="modal" data-target="#delQueue{{$queue->id}}">Delete</a>

                                 <div id="delQueue{{$queue->id}}"  class="modal fade">
                                     <div class="modal-dialog">
                                         <div class="modal-content">

                                         </div>
                                     </div>
                                 </div>

                             </td>
                         </tr>
                         @endforeach
                     </tbody>
                 </table>

                 @if ($queues && count($queues) && (!isset($all)))

                 {!! $queues->appends(Input::except('page'))->render() !!}
                 <ul class="pagination">
                 <li><a href="/admin/queues/all">All</a></li>
                 </ul>
                 @else
                 <ul class="pagination">
                 <li><a href="/admin/queues">1</a></li>
                 </ul>
                 @endif
                 
             </div>
        </div>
        
    </div> 
</div>
    
</div>    

@include('admin._partials.modal', ['elementId' => 'createQueue']) 

@include('admin.js')
    

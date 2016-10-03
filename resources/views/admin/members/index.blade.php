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
				<div class="panel-heading">Administration Panel - Пользователи</div>

				<div class="panel-body">
            
            <a class="delete_selected btn btn-danger disabled">Delete Selected</a>

            <div class="col-md-13 col-md-offset" style="float:right;">
            {!! Form::open(array('class' => 'form-inline', 'method' => 'POST', 'url' => '/admin/members/filter')) !!}

             <input class="form-control" name="limit" type="text" value="{{isset($limit) ? $limit : '10'}}" placeholder="макс. кол-во (шт.)">
             
             <input type="checkbox" name="sort" @if(isset($sort)&&($sort == 'desc')) checked @endif />desc(set)/asc
             {!! Form::submit('apply', array('class' => 'btn btn-info')) !!}
             {!! Form::close() !!}
             </div>
            {!! link_to_route('admin.members.create', 'Create New Member', array() ,array('class' => '', 'data-toggle'=>'modal', 'data-target'=>'#createMember') ) !!}<br><br>
             <div class="table-responsive"> 
                 <table class="table table-bordered" data-search="true" data-url="">
                     <thead>
                         <tr>
                             <th>
                              <div>
                                  <input type="checkbox" id="SelectAll">
                                  Select All
                              </div>
                             </th>
                             <th data-sortable="true">ID</th>
                             <th>IP</th>
                             <th>Q-R</th>
                             <th>Q-R day</th>
                             <th>A-R</th>
                             <th>A-R day</th>
                             <th>Flag</th>
                             <th>Date</th>
                             <th>
                              <div>
                                  <input type="checkbox" id="SelectAllVisible" data-url="members"> Visible
                              </div>
                             </th>
                             <th>Operations</th>
                         </tr>
                     </thead>
                     <tbody>
                         @foreach ($members as $member)
                         <tr>
                              <td>
                                 <div>
                                     <input type="checkbox" class="members-delete" id="id{{$member->id}}" data-id="{{$member->id}}">
                                 </div>
                             </td>
                             <td class="delete_toggler">{{$member->id}}</td>
                             
                             <td>{{$member->ip}}</td>
                             <td>
                               {{$member->questions_restrict}} 
                             </td>
                             <td>
                                {{$member->questions_restrict_day}}  
                             </td>
                              <td>
                                {{$member->answers_restrict}}  
                              </td>
                              <td>
                                {{$member->answers_restrict_day}}  
                              </td>
                              <td>
                                {{$member->restrict_flag}}  
                              </td>
                              <td>
                                {{$member->created_at}}  
                              </td>
                              <td>
                               <input type="checkbox" class="visible-change" data-url="members" name="visible" @if($member->visible==1) checked @endif data-id="{{$member->id}}">
                               </td>
                               
                               <td>
                              <a href="/admin/members/{{$member->id}}/edit?page=<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" class="btn btn-info" data-toggle="modal" data-target="#editMember{{$member->id}}">Edit</a>
                                 <div id="editMember{{$member->id}}" class="modal fade" >
                                     <div class="modal-dialog">
                                         <div class="modal-content">

                                         </div>
                                     </div>
                                 </div>

                                 <a href="/admin/members/del/{{$member->id}}?page=<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" class="btn btn-danger" data-toggle="modal" data-target="#delMember{{$member->id}}">Delete</a>

                                 <div id="delMember{{$member->id}}"  class="modal fade">
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

                
                 
                  @if ($members && (!isset($all)))

                    @if (!isset($limit))
                    {!! $members->appends(Input::except('page'))->render() !!}
                    <ul class="pagination">
                    <li><a href="/admin/members/all">All</a></li>
                    </ul>
                    @else
                    <ul class="pagination">
                    <li><a href="/admin/members">1</a></li>
                    </ul>
                    @endif

                 @else
                 <ul class="pagination">
                 <li><a href="/admin/members">1</a></li>
                 </ul>
                 @endif
                 <input type="hidden" id="pageNumb" value="<?php if (isset($_GET['page'])) echo $_GET['page']; else echo '1'; ?>">
                 
             </div>
        </div>
        
    </div> 
</div>
    
</div>    

@include('admin._partials.modal', ['elementId' => 'createMember']) 

@include('admin._partials.del-modal', ['url' => '/admin/members/del_many'])

@include('admin.js')

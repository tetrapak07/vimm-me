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
				<div class="panel-heading">Administration Panel - Рейтиг видео-вопросов</div>

				<div class="panel-body">
            
            <a class="delete_selected btn btn-danger disabled">Delete Selected</a>

            <div class="col-md-13 col-md-offset" style="float:right;">
            {!! Form::open(array('class' => 'form-inline', 'method' => 'POST', 'url' => '/admin/questions-rating/filter')) !!}

             <input class="form-control" name="limit" type="text" value="{{isset($limit) ? $limit : '10'}}" placeholder="макс. кол-во (шт.)" style="width:70px">
             <select class="form-control memberus" name="memberSel" style="width:200px">
                                         <option value="">Любой Юзер</option>
                                         @foreach ($members as $member)
                                         <option  value="{{$member->id}}"  @if(isset($memberSel)&&($member->id == $memberSel)) selected @endif>
                                                  IP: {{$member->ip}}; ID: {{$member->id}}
                                         </option>
                                         @endforeach
             </select>
             <select class="form-control questionus" name="questionSel" style="width:200px">
                                         <option value="">Любой Вопрос</option>
                                         @foreach ($questions as $question)
                                         <option  value="{{$question->id}}"  @if(isset($questionSel)&&($question->id == $questionSel)) selected @endif>
                                                  ID: {{$question->id}}; TITLE: {{$question->title}}
                                         </option>
                                         @endforeach
             </select>
             <input type="checkbox" name="sort" @if(isset($sort)&&($sort == 'desc')) checked @endif />desc(set)/asc
             {!! Form::submit('apply', array('class' => 'btn btn-info')) !!}
             {!! Form::close() !!}
             </div>
            {!! link_to_route('admin.questions-rating.create', 'Create New Q-R', array() ,array('class' => '', 'data-toggle'=>'modal', 'data-target'=>'#createQuestionRating') ) !!}<br><br>
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
                             <th>Question</th>
                             <th>Member</th>
                             <th>R+</th>
                             <th>R-</th>
                             <th>R sum</th>
                             
                             <th>Operations</th>
                         </tr>
                     </thead>
                     <tbody>
                         @foreach ($questionRatings as $qrating)
                         <tr>
                              <td>
                                 <div>
                                     <input type="checkbox" class="qrating-delete" id="id{{$qrating->id}}" data-id="{{$qrating->id}}">
                                 </div>
                             </td>
                             <td class="delete_toggler">{{$qrating->id}}</td>
                             <td>{{$qrating->question_id}}</td>
                             <td>{{$qrating->member_id}}</td>
                             <td>{{$qrating->rating_plus}}</td>
                             <td>{{$qrating->rating_minus}}</td>
                             <td>{{$qrating->summary}}</td>  
                               <td>
                              <a href="/admin/questions-rating/{{$qrating->id}}/edit?page=<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" class="btn btn-info" data-toggle="modal" data-target="#editQuestionRating{{$qrating->id}}">Edit</a>
                                 <div id="editQuestionRating{{$qrating->id}}" class="modal fade" >
                                     <div class="modal-dialog">
                                         <div class="modal-content">

                                         </div>
                                     </div>
                                 </div>

                                 <a href="/admin/questions-rating/del/{{$qrating->id}}?page=<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" class="btn btn-danger" data-toggle="modal" data-target="#delQuestionRating{{$qrating->id}}">Delete</a>

                                 <div id="delQuestionRating{{$qrating->id}}"  class="modal fade">
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

                
                 
                  @if ($questionRatings && (!isset($all)))

                    @if (!isset($limit))
                    {!! $questionRatings->appends(Input::except('page'))->render() !!}
                    <ul class="pagination">
                    <li><a href="/admin/questions-rating/all">All</a></li>
                    </ul>
                    @else
                    <ul class="pagination">
                    <li><a href="/admin/questions-rating">1</a></li>
                    </ul>
                    @endif

                 @else
                 <ul class="pagination">
                 <li><a href="/admin/questions-rating">1</a></li>
                 </ul>
                 @endif
                 <input type="hidden" id="pageNumb" value="<?php if (isset($_GET['page'])) echo $_GET['page']; else echo '1'; ?>">
                 
             </div>
        </div>
        
    </div> 
</div>
    
</div>    

@include('admin._partials.modal', ['elementId' => 'createQuestionRating']) 

@include('admin._partials.del-modal', ['url' => '/admin/questions-rating/del_many'])

@include('admin.js')


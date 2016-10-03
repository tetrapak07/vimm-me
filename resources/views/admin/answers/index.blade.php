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
				<div class="panel-heading">Administration Panel - Видео-Ответы</div>

				<div class="panel-body">
            
            <a class="delete_selected btn btn-danger disabled">Delete Selected</a>

            <div class="col-md-13 col-md-offset" style="float:right;">
            {!! Form::open(array('class' => 'form-inline', 'method' => 'POST', 'url' => '/admin/answers/filter')) !!}

             <input class="form-control" name="limit" type="text" value="{{isset($limit) ? $limit : '10'}}" placeholder="макс. кол-во (шт.)" style="width:70px">
             <select class="form-control memberus" name="memberSel" style="width:100px">
                                         <option value="">Юзер</option>
                                         @foreach ($members as $member)
                                         <option  value="{{$member->id}}"  @if(isset($memberSel)&&($member->id == $memberSel)) selected @endif>
                                                  IP: {{$member->ip}}; ID: {{$member->id}}
                                         </option>
                                         @endforeach
             </select>
             <select class="form-control questionus" name="questionSel" style="width:100px">
                                         <option value="">Вопрос</option>
                                         @foreach ($questions as $question)
                                         <option  value="{{$question->id}}"  @if(isset($questionSel)&&($question->id == $questionSel)) selected @endif>
                                                  ID: {{$question->id}}; TITLE: {{$question->title}}
                                         </option>
                                         @endforeach
             </select>
             <select class="form-control" name="ratingSel" type="text" style="width:100px" >
                    <option value="">Рейтинг</option>
                    @foreach ($ratings as $rating)
                    <option value="{{$rating->id}}" @if(isset($ratingSel)&&($rating->id == $ratingSel)) selected @endif>
                            ID: {{$rating->id}}
                    </option>
                    @endforeach
            </select>
             <input type="checkbox" name="sort" @if(isset($sort)&&($sort == 'desc')) checked @endif />desc(set)/asc
             {!! Form::submit('apply', array('class' => 'btn btn-info')) !!}
             {!! Form::close() !!}
             </div>
            {!! link_to_route('admin.answers.create', 'Create New Answer', array() ,array('class' => '', 'data-toggle'=>'modal', 'data-target'=>'#createAnswer') ) !!}<br><br>
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
                              <th>R.ID</th>
                             <th>Title</th>
                             <th>Description</th>
                             <th>Mob</th>
                             <th>Thumb</th>
                             <th>
                              <div>
                                  <input type="checkbox" id="SelectAllVisible" data-url="answers"> Visible
                              </div>
                             </th>
                             <th>Operations</th>
                         </tr>
                     </thead>
                     <tbody>
                         @foreach ($answers as $answer)
                         <tr>
                              <td>
                                 <div>
                                     <input type="checkbox" class="answers-delete" id="id{{$answer->id}}" data-id="{{$answer->id}}">
                                 </div>
                             </td>
                             <td class="delete_toggler">{{$answer->id}}</td>
                             <td>{{$answer->question_id}}</td>
                             <td>{{$answer->member_id}}</td>
                             <td>{{$answer->rating_id}}</td>
                             <td>
                                <div data-toggle="tooltip" data-placement="bottom" title="{{$answer->title}}">
                                     {!!mb_strimwidth($answer->title, 0, 10, "...")!!}
                                 </div>
                             </td>
                             <td>
                                 <div data-toggle="tooltip" data-placement="bottom" title="{{$answer->description}}">
                                     {!!mb_strimwidth($answer->description, 0, 10, "...")!!}
                                 </div>
                             </td>
                             <td>{{$answer->ext}}</td>
                               <td style="height:130px;width:130px"><img src="{{$answer->url_thumb}}" style="height:130px;width:130px"></td>
                               
                               <td>
                               <input type="checkbox" class="visible-change" data-url="answers" name="visible" @if($answer->visible==1) checked @endif data-id="{{$answer->id}}">
                               </td>
                               
                               <td>
                              <a href="/admin/answers/{{$answer->id}}/edit?page=<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" class="btn btn-info" data-toggle="modal" data-target="#editAnswer{{$answer->id}}">Edit</a>
                                 <div id="editAnswer{{$answer->id}}" class="modal fade" >
                                     <div class="modal-dialog">
                                         <div class="modal-content">

                                         </div>
                                     </div>
                                 </div>

                                 <a href="/admin/answers/del/{{$answer->id}}?page=<?php if (isset($_GET['page'])) echo $_GET['page']; ?>" class="btn btn-danger" data-toggle="modal" data-target="#delAnswer{{$answer->id}}">Delete</a>

                                 <div id="delAnswer{{$answer->id}}"  class="modal fade">
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

                
                 
                  @if ($answers && (!isset($all)))

                    @if (!isset($limit))
                    {!! $answers->appends(Input::except('page'))->render() !!}
                    <ul class="pagination">
                    <li><a href="/admin/answers/all">All</a></li>
                    </ul>
                    @else
                    <ul class="pagination">
                    <li><a href="/admin/answers">1</a></li>
                    </ul>
                    @endif

                 @else
                 <ul class="pagination">
                 <li><a href="/admin/answers">1</a></li>
                 </ul>
                 @endif
                 <input type="hidden" id="pageNumb" value="<?php if (isset($_GET['page'])) echo $_GET['page']; else echo '1'; ?>">
                 
             </div>
        </div>
        
    </div> 
</div>
    
</div>    

@include('admin._partials.modal', ['elementId' => 'createAnswer']) 

@include('admin._partials.del-modal', ['url' => '/admin/answers/del_many'])

@include('admin.js')

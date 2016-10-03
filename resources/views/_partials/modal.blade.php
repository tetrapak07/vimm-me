<div class="modal" id="{{$modalId}}" tabindex="-1" role="dialog" aria-labelledby="{{$modalId}}">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">{{$title}}</h4>
      </div>
      <div class="modal-body">
        <p style="text-align: justify">{{$text}}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('messages.close')}}</button>
      </div>
    </div>
  </div>
</div>


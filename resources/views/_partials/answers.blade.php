@if (count($answers->all())>0)
 
         @foreach ($answers as $answer)
         
           @include('_partials.answer', ['answer' => $answer])
              
         @endforeach
          
@else
        <p>{{trans('messages.no-answers')}}</p>
        
@endif


<div class="row">

    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>


    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">

        <div class="title"> 

            <a href="/answer/{{$answer->slug}}" title="{{$answer->title}}">{{$answer->title}}</a>
            - {{$answer->created_at}} &nbsp; 
            <a class="btn btn-primary btn-lg" style="padding:2px;border-radius:5px;margin-bottom:5px " href="/answer/{{$answer->slug}}">{{trans('messages.share')}}</a>

            <div class="pull-right">
                <div class="row">


                    @include('_partials.rating', ['object' => $answer])

                </div>
            </div>
        </div>
        <video src="/{{$answer->url}}" controls style="width:100%;margin-bottom: 25px;border: 1px dotted white;" class="vidos" data-id="{{$answer->id}}"></video> 

    </div>


    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>  
</div>



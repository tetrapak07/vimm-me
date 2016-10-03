<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google-site-verification" content="ykEBLhfXVFsx2PLH68j--mKR_FoR-_CLyJwvVANjLK8" />
        <meta name="yandex-verification" content="614dd07e9db22fb6" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
		<link rel="shortcut icon" type="image/x-icon" href="{{ asset('/css/favicon.ico') }}">
        @yield('meta')
        <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

      
        <link href="{{ asset('/css/jquery.fancybox.css?v=2.1.5') }}" rel="stylesheet">
        <link href="{{ asset('/css/helpers/jquery.fancybox-thumbs.css?v=1.0.7') }}" rel="stylesheet">
        <link href="{{ asset('/css/helpers/jquery.fancybox-buttons.css?v=1.0.5') }}" rel="stylesheet">
        <link href="{{ asset('/css/bootstrap-switch.css') }}" rel="stylesheet">
        <link href="{{ asset('/css/toastr/toastr.min.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!--noindex-->
<script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
<script type="text/javascript">stLight.options({publisher: "2af92873-3ba4-43b3-8d00-70f41b545716", doNotHash: true, doNotCopy: false, hashAddressBar: false});</script>
    
        <!--/noindex-->
    </head>
    <body>
       <div class="bs-component">
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                      <span class="sr-only">Toggle navigation</span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </button>

                    @if (isset($site_name))
                    <a class="navbar-brand" href="/">{{$site_name}} (18+)</a>

                    @else
                    <a class="navbar-brand" href="/">{!!Request::server ('HTTP_HOST')!!} (18+)</a>
                    @endif

                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">


                        @if ((isset($questions))AND($questions->currentPage() > 1))
                       <li><a class="btn btn-primary btn-lg makeQuestion" data-toggle="modal" data-target="#make-question">{{trans('messages.make-question-up')}}</a></li>
                       @elseif (!isset($questions))
                       <li><a class="btn btn-primary btn-lg makeQuestion" data-toggle="modal" data-target="#make-question">{{trans('messages.make-question-up')}}</a></li>
                       @endif
                   </ul>
                    <ul class="nav navbar-nav navbar-right">
                       <li><a data-toggle="modal" data-target="#about">{{trans('messages.about')}} {!!Request::server ('HTTP_HOST')!!}</a></li>
                               <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{trans('messages.language')}} ({{App::getLocale()}}<img src="{{ asset('/css/'.App::getLocale().'.png') }}" width="20" height="20">)<span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                  <li><a href="/locale/ru">{{trans('messages.russhian')}} (RU) <img src="{{ asset('/css/ru.png') }}" width="20" height="20"></a></li>
                                  <li><a href="/locale/en">{{trans('messages.english')}} (EN) <img src="{{ asset('/css/en.png') }}" width="20" height="20"></a></li>
                                  <li><a href="/locale/ua">{{trans('messages.ukrainian')}} (UA) <img src="{{ asset('/css/ua.png') }}" width="20" height="20"></a></li>
                                 
                                </ul>
                              </li>
                    </ul>
          
                </div>
            </div>
        </nav>
       </div>
        <div class="container">
            @if (Session::has('message'))
            <div class="flash alert-info">
                <p>{!! Session::get('message') !!}</p>
            </div>
            @endif
            @if ($errors->any())
            <div class='flash alert-danger'>
                @foreach ( $errors->all() as $error )
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif
        </div>

        @yield('content')

        @include('_partials.modal', ['modalId' => 'about','title' => trans('messages.about').' '.Request::server ('HTTP_HOST'), 'text' => '"'.Request::server ('HTTP_HOST').'" - '.trans('messages.text-modal')])
        
        @include('_partials.modalBigQuestion')
        
        @include('_partials.footer')

    </body>
</html>



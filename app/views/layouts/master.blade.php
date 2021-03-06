<html>
<head>
    <link href="{{URL::to('/')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{URL::to('/')}}/css/footable.core.css" rel="stylesheet " type="text/css">
    <link href="{{URL::to('/')}}/css/custom.css" rel="stylesheet">
    <link href='{{URL::to('/')}}/img/favicon.png' rel='icon'>
    <link href='http://fonts.googleapis.com/css?family=Oswald:700' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="{{URL::to('/')}}/js/jquery-1.9.0.js"></script>
    <script src="{{URL::to('/')}}/js/jquery-ui.min.js"></script>
    <script src="{{URL::to('/')}}/js/jquery.timepicker.min.js"></script>
    <script src="{{URL::to('/')}}/js/bootstrap.min.js"></script>
    <script src="{{URL::to('/')}}/js/footable.min.js"></script>
    <script src="{{URL::to('/')}}/js/footable.paginate.min.js"></script>
    <script src="{{URL::to('/')}}/js/footable.filter.min.js"></script>
    <script src="{{URL::to('/')}}/js/footable.sort.min.js"></script>
    <script src="{{URL::to('/')}}/js/propel_script.js"></script>

    <script type="text/javascript">
        $(function () {
            $('.footable').footable({
                breakpoints: {
                    phone: 555,
                    tablet: 1024
                }
            });
        });
    </script>
    <title>Propel | MADApp</title>
    @yield('head')
</head>

<body class="blue-red">
<nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        @section('navbar-header')
            <!--<a class="navbar-brand" href="{{URL::to('/')}}/../../../madapp/index.php/dashboard/dashboard_view">MADApp</a>-->
          @if(Route::currentRouteName() != "home")
            <a class="navbar-brand" href="javascript:history.back()"><span class="glyphicon glyphicon-chevron-left"></span></a>
          @endif
          <a class="navbar-brand" href="{{URL::to('/')}}"><span class="glyphicon glyphicon-home"></span>&nbsp;Propel</a>

        @show

        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @section('navbar-links')
                <!--<li><a href="{{URL::to('/')}}/calendar">Calendar</a></li>
                <li><a href="{{URL::to('/')}}/attendance">Attendance</a></li>
                <li><a href="{{URL::to('/')}}/wingman-journal">Wingman Journal</a></li>-->

                @if(!empty($_SESSION['original_id']) && $_SESSION['user_id'] != $_SESSION['original_id'])
                    <li class=""><a href="{{URL::to('/city-change/back-to-national')}}">Back to National</a></li>
                @endif
                <li class=""><a>
                <?php
                    $i = 0;
                    $id = $_SESSION['user_id'];
                    $home = new HomeController;
                    $name = DB::table('User')->select('name')->where('id',$id)->first();
                    echo $name->name.' (';
                    $groups = DB::table('UserGroup')->join('Group','Group.id','=','UserGroup.group_id')->select('Group.name')->where('user_id',$id)->where('year',$home->get_year())->get();
                    $result = array();
                    foreach ($groups as $group){
                        $result[$i]=$group->name;
                        $i++;
                    }
                    $value = join(',',$result);
                    echo $value.')';
                ?></a>
                </li>

                    <li class=""><a href="{{URL::to('/logout')}}">Logout</a></li>


                @show
            </ul>


        </div>
    </div>
    </div>
</nav>


@if(Session::has('success'))
    <div class="center-block alert alert-success" role="alert" style="width:20%;">{{ Session::get('success') }}</div>
@endif

@if(Session::has('error'))
<div class="center-block alert alert-danger" role="alert" style="width:20%;">{{ Session::get('error') }}</div>
@endif

@yield('body')

<script>
  window.intercomSettings = {
    app_id: "xnngu157"
  };
</script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/xnngu157';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>

</body>
</html>

<html>
<head>
    <link href="{{{URL::to('/')}}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{{URL::to('/')}}}/css/footable.core.css" rel="stylesheet " type="text/css">
    <link href="{{{URL::to('/')}}}/css/custom.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Oswald:700' rel='stylesheet' type='text/css'>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <script src="{{{URL::to('/')}}}/js/jquery-1.9.0.js"></script>
    <script src="{{{URL::to('/')}}}/js/bootstrap.min.js"></script>
    <script src="{{{URL::to('/')}}}/js/footable.min.js"></script>
    <script src="{{{URL::to('/')}}}/js/footable.filter.min.js"></script>
    <script src="{{{URL::to('/')}}}/js/footable.paginate.min.js"></script>
    <script src="{{{URL::to('/')}}}/js/footable.sort.min.js"></script>
    <script src="{{{URL::to('/')}}}/js/uservoice.js"></script>
    <script type="text/javascript">
        $(function () {
            $('.footable').footable({
                breakpoints: {
                    phone: 555
                }
            });
        });
    </script>
    <title>Propel App | MADApp</title>
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
            <!--<a class="navbar-brand" href="{{{URL::to('/')}}}/../../../madapp/index.php/dashboard/dashboard_view">MADApp</a>-->
        <a class="navbar-brand" href="{{{URL::to('/')}}}">Propel</a>
        @show
        </div>
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @section('navbar-links')
                <!--<li><a href="{{{URL::to('/')}}}/calendar">Calendar</a></li>
                <li><a href="{{{URL::to('/')}}}/attendance">Attendance</a></li>
                <li><a href="{{{URL::to('/')}}}/wingman-journal">Wingman Journal</a></li>-->
                <li class=""><a href="{{{URL::to('/')}}}/../../../madapp/index.php/auth/logout">Logout</a></li>
                @show
            </ul>

        </div>
    </div>
</nav>


@if(Session::has('success'))
    <div class="center-block alert alert-success" role="alert" style="width:20%;">{{{ Session::get('success') }}}</div>
@endif

@if(Session::has('error'))
<div class="center-block alert alert-danger" role="alert" style="width:20%;">{{{ Session::get('error') }}}</div>
@endif

@yield('body')

</body>
</html>
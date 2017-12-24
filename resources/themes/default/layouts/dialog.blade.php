<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $page_title or "Page Title" }}</title>
    <link rel="icon"
          type="image/ico"
          href="{{ asset('/assets/images/favicon.ico') }}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="{{ asset("/bower_components/admin-lte/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons 4.4.0 -->
    <link href="{{ asset("/bower_components/admin-lte/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Ionicons 2.0.1 -->
    <link href="{{ asset("/bower_components/admin-lte/ionicons/css/ionicons.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="{{ asset("/bower_components/admin-lte/dist/css/AdminLTE.min.css") }}" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="{{ asset("/bower_components/admin-lte/plugins/iCheck/square/blue.css") }}" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        body {
            background-color: #d2d6de;
            background-image: url('{{ asset('/assets/backgrounds/dialog.png') }}');
            background-repeat: repeat-x;
            background-position: bottom;
            background-size: 15%;
        }
    </style>
</head>
<body>

<div class="box-body">
    @include('flash::message')
    @include('partials._errors')
</div>

<div class="login-box">
    <div class="login-logo">
        <b>MLC</b> System <br /> {{ $page_title or "Page Title" }}
    </div><!-- /.login-logo -->
    <div class="login-box-body" style="box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;">

        <!-- Your Page Content Here -->
        @yield('content')

    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="{{ asset("/bower_components/admin-lte/plugins/jQuery/jQuery-2.1.4.min.js") }}"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="{{ asset("/bower_components/admin-lte/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
<!-- iCheck -->
<script src="{{ asset("/bower_components/admin-lte/plugins/iCheck/icheck.min.js") }}" type="text/javascript"></script>

@yield('jsvalidator')

<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>

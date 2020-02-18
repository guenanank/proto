<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="author" content="http://guenanank.com" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title itemprop="name">Gateway - @yield('title', 'Home') </title>

    <!-- Custom fonts for this template-->
    <link href="{{ mix('/css/fontawesome.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ mix('/css/sb-admin-2.css') }}" rel="stylesheet">
    <style>
      .input-group-append button {
        border: 1px solid lightgrey;
        border-left: none;
        border-radius: 10rem;
      }
    </style>

</head>

<body class="bg-gradient-primary">

    <div class="container">
        @yield('content')
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ mix('/js/jquery.js') }}"></script>
    <script src="{{ mix('/js/bootstrap.bundle.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ mix('/js/jquery.easing.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ mix('/js/sb-admin-2.js') }}"></script>
    <script>
      var password = $("#password");
      password.css('border-right', 'none');
      $('body').on('click', '#password-addon', function() {
          $(this).find('i').toggleClass('fa-eye fa-eye-slash');
          password.attr('type') === 'password' ? password.attr('type','text') : password.attr('type','password')
      });
    </script>
</body>

</html>

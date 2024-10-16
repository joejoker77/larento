<!doctype html>
<html lang="ru" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Вход в админ панель</title>
    @vite('resources/scss/admin.scss')
</head>
<body class="h-100" style="background-color: #f7f4f2">
    <div class="container h-100 d-flex align-items-center">
        @include('layouts.partials.flash')
        @yield('content')
    </div>
</body>
</html>


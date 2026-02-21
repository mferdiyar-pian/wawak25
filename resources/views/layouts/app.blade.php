<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', 'Happy Birthday')</title>
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
  <main class="wrap">
    <div class="console">
      <div class="screen">
        @yield('content')
      </div>
      <div class="brand"></div>
    </div>
  </main>
</body>
</html>
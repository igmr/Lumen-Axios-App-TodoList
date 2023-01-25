<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ url('public/assets/bootstrap-5.3.0/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ url('public/assets/fontawesome-6.2.1/css/all.css') }}">
    {{ $styles ?? '' }}
    <style>
        main > .container {
            padding: 60px 15px 0;
        }
        main {
            margin-bottom: 65px;
        }
    </style>
    <title> {{ $title ?? '' }} - Lista de tareas</title>
</head>
<body  class="d-flex flex-column h-100"  data-bs-theme="dark">
    @include('layouts.header', ['title' => $title ?? '' ])
    <main class="flex-shrink-0">
        <div class="container">
            {{ $slot }}
        </div>
    </main>
    @include('layouts.footer')
    <script src="{{ url('public/assets/bootstrap-5.3.0/js/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="{{ url('public/assets/fontawesome-6.2.1/js/all.js') }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    {{ $script }}
</body>
</html>
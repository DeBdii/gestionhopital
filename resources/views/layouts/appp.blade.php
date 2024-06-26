@vite(['resources/css/app.css', 'resources/js/app.js'])
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Application</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('fullcalendar/core/main.css') }}" rel="stylesheet">
    <link href="{{ asset('fullcalendar/timegrid/main.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('fullcalendar/core/main.js') }}" defer></script>
    <script src="{{ asset('fullcalendar/timegrid/main.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @yield('scripts')
</head>
<body>
<div id="app">
    @include('layouts.doctornav')
    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>

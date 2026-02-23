<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MnE System')</title>
    @vite('resources/css/app.css') 
</head>
<body class="bg-[#f8f9fc] font-sans antialiased text-gray-900">

    <div class="flex">
        @include('sidebar')

        <main class="ml-64 flex-1 min-h-screen p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>
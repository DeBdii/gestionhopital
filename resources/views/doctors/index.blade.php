<!-- resources/views/users/index.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
@include('layouts.navigation')

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Doctors List</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($doctors as $doctor)
            <div class="bg-white shadow-md rounded-md p-4">
                <h2 class="text-lg font-semibold mb-2">{{ $doctor->name }}</h2>
                <p class="text-gray-600 mb-2">{{ $doctor->specialty }}</p>
                <p class="text-gray-600">{{ $doctor->email }}</p>
            </div>
        @endforeach
    </div>
</div>

</body>
</html>

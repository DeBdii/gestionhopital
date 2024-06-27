<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon1.png') }}">
</head>
<body class="bg-gray-100">
@include('layouts.fakenavdebdii')

<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Create Staff</h1>

    <!-- Display form validation errors if any -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.staff.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter name" value="{{ old('name') }}">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter email" value="{{ old('email') }}">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter password">
        </div>

        <div class="mb-4">
            <label for="user_type" class="block text-gray-700 text-sm font-bold mb-2">Role</label>
            <select name="user_type" id="user_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="Receptionist">Receptionist</option>
                <option value="Nurse">Nurse</option>
                <option value="SupportStaff">Support Staff</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="salary" class="block text-gray-700 text-sm font-bold mb-2">Salary</label>
            <input type="number" id="salary" name="salary" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter salary" value="{{ old('salary') }}">
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create</button>
        </div>
    </form>
</div>

</body>
</html>

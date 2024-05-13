<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
@include('layouts.fakenavdebdii')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Doctors List</h1>
        <!-- Add Doctor Button -->
        <a href="{{ route('admin.doctors.create') }}" class="bg-green-500 text-white py-2 px-4 rounded-md">Add Doctor</a>
    </div>

    <div class="overflow-x-auto">
        <table class="table-auto w-full">
            <thead>
            <tr>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Specialty</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($doctors as $doctor)
                <tr>
                    <td class="border px-4 py-2">{{ $doctor->name }}</td>
                    <td class="border px-4 py-2">{{ $doctor->specialty }}</td>
                    <td class="border px-4 py-2">{{ $doctor->email }}</td>
                    <td class="border px-4 py-2">
                        <!-- Edit Button -->

                        <!-- Delete Button -->
                        <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded-md">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

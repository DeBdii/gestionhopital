<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors List</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS (for layout and utility classes) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
@include('layouts.fakenavdebdii')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Doctors List</h1>
            <!-- Add Doctor Button -->
            <a href="{{ route('admin.doctors.create') }}" class="btn btn-success">Add Doctor</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="thead-dark">
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
                        <td class="px-4 py-2">{{ $doctor->name }}</td>
                        <td class="px-4 py-2">{{ $doctor->specialty }}</td>
                        <td class="px-4 py-2">{{ $doctor->email }}</td>
                        <td class="px-4 py-2">
                            <!-- Edit Button -->
                            <a href="#" class="btn btn-sm btn-info">Edit</a>

                            <!-- Delete Button -->
                            <form action="{{ route('admin.doctors.destroy', $doctor->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap JS, jQuery, and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

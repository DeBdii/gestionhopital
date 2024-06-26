<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Patients List</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS (optional for additional styles) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        .table-container {
            overflow-x: auto;
        }

        .table {
            width: 100%;
        }

        /* Center the table content */
        .table-responsive {
            display: flex;
            justify-content: center;
        }
    </style>
</head>

<body class="bg-gray-100">
<!-- Navbar -->

@extends('layouts.appp')

@section('content')
    <div class="container mx-auto py-6">
        <h2 class="text-2xl font-semibold mb-4">Stock Items for Department: {{ $doctor->department->department_name }}</h2>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="py-2 px-4">Item Name</th>
                        <th class="py-2 px-4">Description</th>
                        <th class="py-2 px-4">Quantity</th>
                        <th class="py-2 px-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr class="border-t">
                            <td class="py-2 px-4">{{ $item->name }}</td>
                            <td class="py-2 px-4">{{ $item->description }}</td>
                            <td class="py-2 px-4">{{ $item->quantity }}</td>
                            <td class="py-2 px-4">
                                <button 
                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                                    onclick="openModal('{{ $item->id }}', '{{ $item->name }}')"
                                >
                                    Demander
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Demander <span id="modal-item-name"></span></h2>
            <form method="POST" action="{{ route('doctor.demand.item') }}">
                @csrf
                <input type="hidden" name="item_id" id="modal-item-id">
                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input 
                        type="number" 
                        id="quantity" 
                        name="quantity" 
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required 
                        min="1"
                    >
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded mr-2" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(id, name) {
            document.getElementById('modal-item-id').value = id;
            document.getElementById('modal-item-name').innerText = name;
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
@endsection


</body>

</html>

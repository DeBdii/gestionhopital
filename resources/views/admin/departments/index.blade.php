<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des departements</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS (for layout and utility classes) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon1.png') }}">
</head>
<body class="bg-gray-100">
@include('layouts.fakenavdebdii')

<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Departements</h1>
            <!-- Add New Department Button -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createDepartmentModal">
                Ajouter un departement
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th scope="col" class="px-4 py-2">Nom</th>
                    <th scope="col" class="px-4 py-2">Docteurs</th>
                    <th scope="col" class="px-4 py-2">Stock </th>
                    <th scope="col" class="px-4 py-2">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($departments as $department)
                    <tr>
                        <td class="px-4 py-2">{{ $department->department_name }}</td>
                        <td class="px-4 py-2">
                            @if ($department->users->isNotEmpty())
                                {{ $department->users->implode('name', ', ') }}
                            @else
                                <span class="text-muted">Aucun docteur</span>
                            @endif
                        </td>
                        <td class="px-4 py-2">
                            @foreach ($department->items as $item)
                                {{ $item->name }}
                            @endforeach
                        </td>
                        <td class="px-4 py-2">
                            <a href="#" class="btn btn-sm btn-info edit-department" data-toggle="modal" data-target="#editDepartmentModal{{ $department->id }}">Edit</a>
                            <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Aucun departement.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for creating a new department -->
<div class="modal fade" id="createDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="createDepartmentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDepartmentModalLabel">Create New Department</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for creating a new department -->
                <form action="{{ route('admin.departments.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="department_name" class="block text-sm font-medium text-gray-700">Department Name</label>
                        <input type="text" name="department_name" id="department_name" class="form-input mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Select Doctor(s)</label>
                        <div class="space-y-2">
                            @foreach ($doctors as $doctor)
                                <div class="flex items-center">
                                    <input class="form-checkbox h-4 w-4 text-indigo-600" type="checkbox" name="doctors[]" id="doctor{{ $doctor->id }}" value="{{ $doctor->id }}">
                                    <label class="ml-2 block text-sm text-gray-900" for="doctor{{ $doctor->id }}">
                                        {{ $doctor->name }} - {{ $doctor->specialty }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Select Stock Item(s)</label>
                        <div class="space-y-2">
                            @foreach ($items as $item)
                                <div class="flex items-center">
                                    <input class="form-checkbox h-4 w-4 text-indigo-600" type="checkbox" name="items[]" id="item{{ $item->id }}" value="{{ $item->id }}">
                                    <label class="ml-2 block text-sm text-gray-900" for="item{{ $item->id }}">
                                        {{ $item->name }} - Quantity: {{ $item->quantity }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Create Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modals for editing departments -->
@foreach ($departments as $department)
    <div class="modal fade" id="editDepartmentModal{{ $department->id }}" tabindex="-1" role="dialog" aria-labelledby="editDepartmentModalLabel{{ $department->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="editDepartmentForm{{ $department->id }}" action="{{ route('admin.departments.update', $department->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDepartmentModalLabel{{ $department->id }}">Edit Department</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_department_id" name="department_id" value="{{ $department->id }}">

                        <div class="mb-4">
                            <label for="edit_department_name" class="block text-sm font-medium text-gray-700">Department Name</label>
                            <input type="text" class="form-input mt-1 block w-full" id="edit_department_name{{ $department->id }}" name="department_name"
                                   value="{{ $department->department_name }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="edit_doctors{{ $department->id }}" class="block text-sm font-medium text-gray-700">Select Doctor(s)</label>
                            <select name="edit_doctors[]" id="edit_doctors{{ $department->id }}" multiple class="form-multiselect block w-full mt-1">
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $department->users->contains('id', $doctor->id) ? 'selected' : '' }}>
                                        {{ $doctor->name }} - {{ $doctor->specialty }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="edit_items{{ $department->id }}" class="block text-sm font-medium text-gray-700">Select Stock Item(s)</label>
                            <select name="edit_items[]" id="edit_items{{ $department->id }}" multiple class="form-multiselect block w-full mt-1">
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" {{ $department->items->contains('id', $item->id) ? 'selected' : '' }}>
                                        {{ $item->name }} - Quantity: {{ $item->quantity }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Bootstrap JS, jQuery, and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

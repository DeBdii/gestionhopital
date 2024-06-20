<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
@include('layouts.fakenavdebdii')

<div class="container py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-semibold mb-4">Departments</h2>

                <div class="flex justify-between mb-4">
                    <div>
                        <!-- Button to add a new department -->
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createDepartmentModal">
                            Add New Department
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Doctor</th>
                            <th scope="col">Stock Items</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($departments as $department)
                            <tr>
                                <td>{{ $department->department_name }}</td>
                                <td>
                                    @if ($department->users->isNotEmpty())
                                        {{ $department->users->implode('name', ', ') }}
                                    @else
                                        <span class="text-muted">No assigned doctor</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach ($department->items as $item)
                                        {{ $item->name }}
                                    @endforeach
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info edit-department" data-toggle="modal" data-target="#editDepartmentModal{{ $department->id }}">Edit</a>
                                    <form action="{{ route('admin.departments.destroy', $department->id) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No departments found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
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

                    <div class="form-group">
                        <label for="department_name">Department Name</label>
                        <input type="text" name="department_name" id="department_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Select Doctor(s)</label>
                        <div class="form-check">
                            @foreach ($doctors as $doctor)
                                <input class="form-check-input" type="checkbox" name="doctors[]" id="doctor{{ $doctor->id }}" value="{{ $doctor->id }}">
                                <label class="form-check-label" for="doctor{{ $doctor->id }}">
                                    {{ $doctor->name }} - {{ $doctor->specialty }}
                                </label><br>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Select Stock Item(s)</label>
                        <div class="form-check">
                            @foreach ($items as $item)
                                <input class="form-check-input" type="checkbox" name="items[]" id="item{{ $item->id }}" value="{{ $item->id }}">
                                <label class="form-check-label" for="item{{ $item->id }}">
                                    {{ $item->name }} - Quantity: {{ $item->quantity }}
                                </label><br>
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

                        <div class="form-group">
                            <label for="edit_department_name">Department Name</label>
                            <input type="text" class="form-control" id="edit_department_name{{ $department->id }}" name="department_name"
                                   value="{{ $department->department_name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_doctors{{ $department->id }}">Select Doctor(s)</label>
                            <select name="edit_doctors[]" id="edit_doctors{{ $department->id }}" multiple class="form-control">
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor->id }}" {{ $department->users->contains('id', $doctor->id) ? 'selected' : '' }}>
                                        {{ $doctor->name }} - {{ $doctor->specialty }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_items{{ $department->id }}">Select Stock Item(s)</label>
                            <select name="edit_items[]" id="edit_items{{ $department->id }}" multiple class="form-control">
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

<!-- Custom JavaScript -->
<script>
    $(document).ready(function() {
        $('.edit-department').click(function() {
            // This script is placeholder for handling edit click events if needed
        });
    });
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS (for layout and utility classes) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon1.png') }}">

</head>
<body class="bg-gray-100">

@include('layouts.fakenavdebdii')

<div class="container mx-auto py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-semibold mb-4">Shifts</h2>

                <div class="flex justify-between mb-4">
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createShiftModal">
                            Add New Shift
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border-gray-200 shadow-sm rounded-lg">
                        <thead class="bg-gray-200">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shift Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctors</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employees</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                        @forelse ($shifts as $shift)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">{{ $shift->shift_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $shift->start_datetime ? \Carbon\Carbon::parse($shift->start_datetime)->format('Y-m-d') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $shift->start_datetime ? \Carbon\Carbon::parse($shift->start_datetime)->format('H:i') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $shift->end_datetime ? \Carbon\Carbon::parse($shift->end_datetime)->format('H:i') : 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @forelse ($shift->doctors as $doctor)
                                        {{ $doctor->name }}<br>
                                    @empty
                                        Not assigned
                                    @endforelse
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @forelse ($shift->employees as $employee)
                                        {{ $employee->name }} ({{ $employee->user_type }})<br>
                                    @empty
                                        Not assigned
                                    @endforelse
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="space-x-2">
                                        <a href="#" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editShiftModal{{ $shift->id }}">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.shifts.destroy', ['id' => $shift->id]) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">No shifts found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for creating a new shift -->
<div class="modal fade" id="createShiftModal" tabindex="-1" role="dialog" aria-labelledby="createShiftModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createShiftModalLabel">Create New Shift</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.shifts.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="shift_name" class="block text-sm font-medium text-gray-700">Shift Name</label>
                        <input type="text" name="shift_name" id="shift_name" class="form-input mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label for="start_datetime" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                        <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-input mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label for="end_datetime" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                        <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-input mt-1 block w-full" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Select Doctors</label>
                        <div class="space-y-2">
                            @foreach ($doctors as $doctor)
                                <div class="flex items-center">
                                    <input class="form-checkbox h-4 w-4 text-indigo-600" type="checkbox" name="doctor_ids[]" id="doctor{{ $doctor->id }}" value="{{ $doctor->id }}">
                                    <label class="ml-2 block text-sm text-gray-900" for="doctor{{ $doctor->id }}">
                                        {{ $doctor->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Select Employees</label>
                        <div class="space-y-2">
                            @foreach ($employees as $employee)
                                <div class="flex items-center">
                                    <input class="form-checkbox h-4 w-4 text-indigo-600" type="checkbox" name="employee_ids[]" id="employee{{ $employee->id }}" value="{{ $employee->id }}">
                                    <label class="ml-2 block text-sm text-gray-900" for="employee{{ $employee->id }}">
                                        {{ $employee->name }} ({{ $employee->user_type }})
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Create Shift</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modals for editing shifts -->
@foreach ($shifts as $shift)
    <div class="modal fade" id="editShiftModal{{ $shift->id }}" tabindex="-1" role="dialog" aria-labelledby="editShiftModalLabel{{ $shift->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.shifts.update', $shift->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editShiftModalLabel{{ $shift->id }}">Edit Shift</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label for="shift_name{{ $shift->id }}" class="block text-sm font-medium text-gray-700">Shift Name</label>
                            <input type="text" name="shift_name" id="shift_name{{ $shift->id }}" class="form-input mt-1 block w-full" value="{{ $shift->shift_name }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="start_datetime{{ $shift->id }}" class="block text-sm font-medium text-gray-700">Start Date & Time</label>
                            <input type="datetime-local" name="start_datetime" id="start_datetime{{ $shift->id }}" class="form-input mt-1 block w-full" value="{{ $shift->start_datetime ? \Carbon\Carbon::parse($shift->start_datetime)->format('Y-m-d\TH:i') : '' }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="end_datetime{{ $shift->id }}" class="block text-sm font-medium text-gray-700">End Date & Time</label>
                            <input type="datetime-local" name="end_datetime" id="end_datetime{{ $shift->id }}" class="form-input mt-1 block w-full" value="{{ $shift->end_datetime ? \Carbon\Carbon::parse($shift->end_datetime)->format('Y-m-d\TH:i') : '' }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Select Doctors</label>
                            <div class="space-y-2">
                                @foreach ($doctors as $doctor)
                                    <div class="flex items-center">
                                        <input class="form-checkbox h-4 w-4 text-indigo-600" type="checkbox" name="doctor_ids[]" id="editDoctor{{ $shift->id }}_{{ $doctor->id }}" value="{{ $doctor->id }}" {{ $shift->doctors->contains($doctor->id) ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-900" for="editDoctor{{ $shift->id }}_{{ $doctor->id }}">
                                            {{ $doctor->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Select Employees</label>
                            <div class="space-y-2">
                                @foreach ($employees as $employee)
                                    <div class="flex items-center">
                                        <input class="form-checkbox h-4 w-4 text-indigo-600" type="checkbox" name="employee_ids[]" id="editEmployee{{ $shift->id }}_{{ $employee->id }}" value="{{ $employee->id }}" {{ $shift->employees->contains($employee->id) ? 'checked' : '' }}>
                                        <label class="ml-2 block text-sm text-gray-900" for="editEmployee{{ $shift->id }}_{{ $employee->id }}">
                                            {{ $employee->name }} ({{ $employee->user_type }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
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

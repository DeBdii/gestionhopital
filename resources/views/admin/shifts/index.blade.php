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
                <h2 class="text-2xl font-semibold mb-4">Shifts</h2>

                <div class="flex justify-between mb-4">
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createShiftModal">
                            Add New Shift
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Shift Name</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">Start Time</th>
                            <th scope="col">End Time</th>
                            <th scope="col">Doctors</th>
                            <th scope="col">Employees</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($shifts as $shift)
                            <tr data-id="{{ $shift->id }}">
                                <td>{{ $shift->shift_name }}</td>
                                <td>{{ $shift->start_datetime ? \Carbon\Carbon::parse($shift->start_datetime)->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ $shift->start_datetime ? \Carbon\Carbon::parse($shift->start_datetime)->format('H:i') : 'N/A' }}</td>
                                <td>{{ $shift->end_datetime ? \Carbon\Carbon::parse($shift->end_datetime)->format('H:i') : 'N/A' }}</td>
                                <td>
                                    @forelse ($shift->doctors as $doctor)
                                        {{ $doctor->name }}<br>
                                    @empty
                                        Not assigned
                                    @endforelse
                                </td>
                                <td>
                                    @forelse ($shift->employees as $employee)
                                        {{ $employee->name }} ({{ $employee->user_type }})<br>
                                    @empty
                                        Not assigned
                                    @endforelse
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Shift Actions">
                                        <a href="#" class="btn btn-sm btn-info edit-shift" data-toggle="modal" data-target="#editShiftModal{{ $shift->id }}"
                                           data-id="{{ $shift->id }}"
                                           data-shift_name="{{ $shift->shift_name }}"
                                           data-start_datetime="{{ $shift->start_datetime }}"
                                           data-end_datetime="{{ $shift->end_datetime }}"
                                           data-doctor_id="{{ $shift->doctors->first()->id ?? '' }}"
                                           data-employee_id="{{ $shift->employees->first()->id ?? '' }}">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.shifts.delete', ['id' => $shift->id]) }}" method="POST" class="delete-shift-form ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No shifts found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Modal -->
<!-- Modal -->
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
                <form action="{{ route('admin.shifts.store') }}" method="POST" id="createShiftForm">
                    @csrf

                    <div class="form-group">
                        <label for="create_shift_name">Shift Name</label>
                        <input type="text" name="shift_name" id="create_shift_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="create_shift_start_datetime">Start Date & Time :</label>
                        <input type="datetime-local" name="start_datetime" id="create_shift_start_datetime" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="create_shift_end_datetime">End Date & Time :</label>
                        <input type="datetime-local" name="end_datetime" id="create_shift_end_datetime" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Select Doctors:</label>
                        <div class="form-check">
                            @foreach ($doctors as $doctor)
                                <input class="form-check-input" type="checkbox" name="doctor_ids[]" id="doctor{{ $doctor->id }}" value="{{ $doctor->id }}">
                                <label class="form-check-label" for="doctor{{ $doctor->id }}">
                                    {{ $doctor->name }}
                                </label><br>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Select Employees:</label>
                        <div class="form-check">
                            @foreach ($employees as $employee)
                                <input class="form-check-input" type="checkbox" name="employee_ids[]" id="employee{{ $employee->id }}" value="{{ $employee->id }}">
                                <label class="form-check-label" for="employee{{ $employee->id }}">
                                    {{ $employee->name }} ({{ $employee->user_type }})
                                </label><br>
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
                <div class="modal-header">
                    <h5 class="modal-title" id="editShiftModalLabel{{ $shift->id }}">Edit Shift</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for editing a shift -->
                    <form action="{{ route('admin.shifts.update', $shift->id) }}" method="POST" id="editShiftForm{{ $shift->id }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="edit_shift_name{{ $shift->id }}">Shift Name</label>
                            <input type="text" name="shift_name" id="edit_shift_name{{ $shift->id }}" class="form-control"
                                   value="{{ $shift->shift_name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_shift_start_datetime{{ $shift->id }}">Start Date & Time</label>
                            <input type="datetime-local" name="start_datetime" id="edit_shift_start_datetime{{ $shift->id }}" class="form-control"
                                   value="{{ $shift->start_datetime ? \Carbon\Carbon::parse($shift->start_datetime)->format('Y-m-d\TH:i') : '' }}" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_shift_end_datetime{{ $shift->id }}">End Date & Time</label>
                            <input type="datetime-local" name="end_datetime" id="edit_shift_end_datetime{{ $shift->id }}" class="form-control"
                                   value="{{ $shift->end_datetime ? \Carbon\Carbon::parse($shift->end_datetime)->format('Y-m-d\TH:i') : '' }}" required>
                        </div>

                        <div class="form-group">
                            <label for="editDoctorIds{{ $shift->id }}">Select Doctors:</label>
                            <div class="form-check">
                                @foreach ($doctors as $doctor)
                                    <input class="form-check-input" type="checkbox" name="doctor_ids[]" id="editDoctor{{ $shift->id }}_{{ $doctor->id }}" value="{{ $doctor->id }}" {{ $shift->doctors->contains($doctor->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="editDoctor{{ $shift->id }}_{{ $doctor->id }}">
                                        {{ $doctor->name }}
                                    </label><br>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="editEmployeeIds{{ $shift->id }}">Select Employees:</label>
                            <div class="form-check">
                                @foreach ($employees as $employee)
                                    <input class="form-check-input" type="checkbox" name="employee_ids[]" id="editEmployee{{ $shift->id }}_{{ $employee->id }}" value="{{ $employee->id }}" {{ $shift->employees->contains($employee->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="editEmployee{{ $shift->id }}_{{ $employee->id }}">
                                        {{ $employee->name }} ({{ $employee->user_type }})
                                    </label><br>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="editShiftForm{{ $shift->id }}">Save changes</button>
                </div>
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
        // Delete Shift AJAX call
        $('.delete-shift').click(function(e) {
            e.preventDefault();

            var shiftId = $(this).data('id');

            if (confirm('Are you sure you want to delete this shift?')) {
                $.ajax({
                    url: '/admin/shifts/' + shiftId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Check if the delete was successful
                        if (response == 'success') {
                            // Remove the row from the table
                            $('tr[data-id="' + shiftId + '"]').remove();
                            alert('Shift deleted successfully.');
                        } else {
                            alert('Failed to delete shift.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error deleting shift:', error);
                        alert('Error deleting shift. Please try again later.');
                    }
                });
            }
        });
    });

</script>

</body>
</html>

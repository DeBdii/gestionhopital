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
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon1.png') }}">
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
                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editDoctorModal" data-id="{{ $doctor->id }}" data-name="{{ $doctor->name }}" data-specialty="{{ $doctor->specialty }}" data-email="{{ $doctor->email }}" data-salary="{{ $doctor->salary }}">Edit</button>

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

<!-- Edit Doctor Modal -->
<div class="modal fade" id="editDoctorModal" tabindex="-1" aria-labelledby="editDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDoctorModalLabel">Edit Doctor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editDoctorForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="doctor_id" name="id">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="specialty">Specialty</label>
                        <input type="text" class="form-control" id="specialty" name="specialty" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="salary">Salary</label>
                        <input type="number" class="form-control" id="salary" name="salary" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password (leave blank to keep current password)</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
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

<!-- Bootstrap JS, jQuery, and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $('#editDoctorModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var id = button.data('id');
        var name = button.data('name');
        var specialty = button.data('specialty');
        var email = button.data('email');
        var salary = button.data('salary');

        var modal = $(this);
        modal.find('.modal-body #doctor_id').val(id);
        modal.find('.modal-body #name').val(name);
        modal.find('.modal-body #specialty').val(specialty);
        modal.find('.modal-body #email').val(email);
        modal.find('.modal-body #salary').val(salary);
        modal.find('#editDoctorForm').attr('action', '/admin/doctors/' + id);
    });
});
</script>
</body>
</html>

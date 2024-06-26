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
@include('layouts.doc')

<!-- Content -->
<div class="container mx-auto mt-4">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-pink-600 mb-4">My Patients List</h2>

        <!-- Filter Dropdown -->
        <div class="mb-4">
            <label for="patient-filter" class="block text-sm font-medium text-gray-700">Filter Patients:</label>
            <select id="patient-filter" name="patient-filter"
                    class="form-control mt-1 w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="all">All Patients</option>
                <option value="with-appointment">Patients with Appointments</option>
            </select>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <!-- Patients List Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col">Date of Birth</th>
                        <th scope="col">Gender</th>
                        <th scope="col">Contact Number</th>
                        <th scope="col">Address</th>
                        <th scope="col">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($patients as $patient)
                        <tr class="patient-row {{ in_array($patient->id, $patientt->pluck('id')->toArray()) ? 'with-appointment' : '' }}">
                            <td>{{ $patient->name }}</td>
                            <td>{{ $patient->dob }}</td>
                            <td>{{ $patient->gender }}</td>
                            <td>{{ $patient->contact_number }}</td>
                            <td>{{ $patient->address }}</td>
                            <td>
                                <div class="d-flex justify-content-start align-items-center">
                                    <!-- Edit Button -->
                                    <div class="mr-1">
                                        <button type="button" class="btn btn-sm btn-info btn-icon" data-toggle="modal" data-target="#editPatientModal{{ $patient->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M12 5v-1m-3 3h1m9 9l-2 2a3 3 0 0 1 -4 -4l2 -2"></path>
                                                <path d="M16 5l3 3"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Delete Button -->
                                    <div class="mr-1">
                                        <form class="d-inline" action="{{ route('doctor.patients.destroy', ['patient' => $patient->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger btn-icon" onclick="return confirm('Are you sure you want to delete this patient?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                    <line x1="4" y1="7" x2="20" y2="7" />
                                                    <line x1="10" y1="11" x2="10" y2="17" />
                                                    <line x1="14" y1="11" x2="14" y2="17" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12m-9 -3v-1a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v1"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Details Button with Magnifying Glass Icon -->
                                    <div>
                                        <a href="{{ route('doctor.patients.show', ['patient' => $patient->id]) }}" class="btn btn-sm btn-primary btn-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-search" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <circle cx="10" cy="10" r="7" />
                                                <line x1="21" y1="21" x2="15" y2="15" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                            </td>
                        </tr>

                        <!-- Edit Patient Modal -->
                        <div class="modal fade" id="editPatientModal{{ $patient->id }}" tabindex="-1" role="dialog" aria-labelledby="editPatientModalLabel{{ $patient->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editPatientModalLabel{{ $patient->id }}">Edit Patient: {{ $patient->name }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form to edit patient -->
                                        <form id="editPatientForm{{ $patient->id }}" method="POST" action="{{ route('doctor.patients.update', ['patient' => $patient->id]) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="edit_name{{ $patient->id }}">Name</label>
                                                <input type="text" class="form-control" id="edit_name{{ $patient->id }}" name="name" placeholder="Enter name" value="{{ $patient->name }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_dob{{ $patient->id }}">Date of Birth</label>
                                                <input type="date" class="form-control" id="edit_dob{{ $patient->id }}" name="dob" value="{{ $patient->dob }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_gender{{ $patient->id }}">Gender</label>
                                                <select class="form-control" id="edit_gender{{ $patient->id }}" name="gender">
                                                    <option value="Male" @if($patient->gender == 'Male') selected @endif>Male</option>
                                                    <option value="Female" @if($patient->gender == 'Female') selected @endif>Female</option>
                                                    <option value="Other" @if($patient->gender == 'Other') selected @endif>Other</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_contact_number{{ $patient->id }}">Contact Number</label>
                                                <input type="text" class="form-control" id="edit_contact_number{{ $patient->id }}" name="contact_number" placeholder="Enter contact number" value="{{ $patient->contact_number }}">
                                            </div>
                                            <div class="form-group">
                                                <label for="edit_address{{ $patient->id }}">Address</label>
                                                <input type="text" class="form-control" id="edit_address{{ $patient->id }}" name="address" placeholder="Enter address" value="{{ $patient->address }}">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd'
        });

        // Filter patients based on the selected option
        $('#patient-filter').on('change', function() {
            var filterValue = $(this).val();
            if (filterValue === 'all') {
                $('.patient-row').show();
            } else if (filterValue === 'with-appointment') {
                $('.patient-row').hide();
                $('.with-appointment').show();
            }
        });
    });
</script>

</body>

</html>

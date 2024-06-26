<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .container {
            max-width: 800px;
            margin-top: 20px;
        }
        .medical-record-card {
            border: 1px solid #000;
            margin-bottom: 15px;

        }
        /* Updated card header styles */
        .medical-record-card .card-header {
            background-color: #465054; /* Dark matte black */
            color: #fff;
            border-bottom: 1px solid #000; /* Dark border for separation */
            padding: 10px 20px; /* Increased padding for better spacing */
        }
        /* Updated 'Edit' button styles */
        .medical-record-card .edit-buttons .edit-button {
            background-color: #fff; /* White background */
            color: #000; /* Black text */
            border-color: #fff; /* White border color */
        }
    </style>
</head>
<body>
@include('layouts.doc')

<!-- Main Content -->
<div class="container">

    <!-- Patient Information Section -->
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title">Basic Information</h4>
        </div>
        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" value="{{ $patient->name }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="dob" class="col-sm-3 col-form-label">Date of Birth</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="dob" value="{{ $patient->dob }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="gender" class="col-sm-3 col-form-label">Gender</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="gender" value="{{ $patient->gender }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="contact_number" class="col-sm-3 col-form-label">Contact Number</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="contact_number" value="{{ $patient->contact_number }}" readonly>
                </div>
            </div>
            <div class="form-group row">
                <label for="address" class="col-sm-3 col-form-label">Address</label>
                <div class="col-sm-9">
                    <textarea class="form-control" id="address" rows="3" readonly>{{ $patient->address }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('doctor.patients') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to List</a>
        </div>
    </div>

    <!-- Medical Record Section -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title d-flex align-items-center">
                Medical Records
                <button type="button" class="btn btn-primary ml-auto" data-toggle="modal" data-target="#addMedicalRecordModal">
                    Add Medical Record
                </button>
            </h4>
        </div>
        <div class="card-body">
            @if ($patient->medicalRecords->isEmpty())
                <p>No medical records found for this patient.</p>
            @else
                @foreach ($patient->medicalRecords as $index => $record)
                    <div class="card medical-record-card" id="medicalRecordCard{{ $record->id }}">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Diagnosis {{ $index + 1 }}: {{ $record->diagnosis }}</h5>
                            <div class="edit-buttons">
                                <button type="button" class="btn btn-sm btn-outline-primary edit-button" data-record-id="{{ $record->id }}">Edit</button>

                                <!-- Form for Delete Action -->
                                <form action="{{ route('doctor.medicalrecords.destroy', $record->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this medical record?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>

                                <button type="button" class="btn btn-sm btn-success save-button" data-record-id="{{ $record->id }}" style="display: none;">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="medicalRecordForm{{ $record->id }}" method="POST" action="{{ route('doctor.medicalrecords.update', $record->id) }}" class="medical-record-form">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="diagnosis{{ $record->id }}">Diagnosis</label>
                                    <input type="text" class="form-control" id="diagnosis{{ $record->id }}" name="diagnosis" value="{{ $record->diagnosis }}" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="treatment_history{{ $record->id }}">Treatment History</label>
                                    <textarea class="form-control" id="treatment_history{{ $record->id }}" name="treatment_history" rows="3" readonly>{{ $record->treatment_history }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="test_results{{ $record->id }}">Test Results</label>
                                    <textarea class="form-control" id="test_results{{ $record->id }}" name="test_results" rows="3" readonly>{{ $record->test_results }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="prescriptions{{ $record->id }}">Prescriptions</label>
                                    <textarea class="form-control" id="prescriptions{{ $record->id }}" name="prescriptions" rows="3" readonly>{{ $record->prescriptions }}</textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<!-- Add Medical Record Modal -->
<div class="modal fade" id="addMedicalRecordModal" tabindex="-1" role="dialog" aria-labelledby="addMedicalRecordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMedicalRecordModalLabel">Add Medical Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="addMedicalRecordForm" method="POST" action="{{ route('doctor.medicalrecords.store') }}">
                @csrf

                <div class="modal-body">
                    <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                    <div class="form-group">
                        <label for="diagnosis">Diagnosis</label>
                        <input type="text" class="form-control" id="diagnosis" name="diagnosis" required>
                    </div>

                    <div class="form-group">
                        <label for="treatment_history">Treatment History</label>
                        <textarea class="form-control" id="treatment_history" name="treatment_history"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="test_results">Test Results</label>
                        <textarea class="form-control" id="test_results" name="test_results"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="prescriptions">Prescriptions</label>
                        <textarea class="form-control" id="prescriptions" name="prescriptions"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        // Edit medical record
        $('.edit-button').click(function() {
            var recordId = $(this).data('record-id');
            $('#medicalRecordCard' + recordId).addClass('edit-mode');
            $('#medicalRecordCard' + recordId + ' .form-control').prop('readonly', false);
            toggleEditButtons(recordId, true);
        });

        // Save medical record
        $('.save-button').click(function() {
            var recordId = $(this).data('record-id');
            $('#medicalRecordForm' + recordId).submit();
        });

        // Toggle edit buttons
        function toggleEditButtons(recordId, isEditMode) {
            var card = $('#medicalRecordCard' + recordId);
            card.find('.edit-button, .delete-button').toggle(!isEditMode);
            card.find('.save-button').toggle(isEditMode);
        }
    });
</script>
</body>
</html>

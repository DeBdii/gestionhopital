<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rendez-vous Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS (optional for additional styles) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Bootstrap Datepicker CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        /* Custom styles */
        .container {
            max-width: 800px;
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .appointment-card {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
        }

        .modal-content {
            border-radius: 0;
        }

        /* Style for schedule icon */
        .schedule-icon {
            font-size: 1rem;
            margin-left: 5px;
            color: #6b7280; /* Adjust color as needed */
            cursor: pointer;
        }

        /* Adjust checkbox and label styles */
        .custom-control-input {
            margin-top: 3px;
        }

        /* Style for search input */
        .search-input {
            width: calc(100% - 110px); /* Adjust width to leave space for the button */
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem; /* Adjust font size */
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        /* Style for search results */
        .search-results {
            position: absolute;
            z-index: 1000;
            width: calc(100% - 2px); /* Adjust for border width */
            max-height: 300px;
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-top: none;
            border-radius: 0 0 0.25rem 0.25rem;
            display: none; /* Initially hide search results */
        }

        /* Style for each search result */
        .search-item {
            padding: 0.75rem 1.25rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-item:hover {
            background-color: #f8f9fa;
        }

        /* Style for schedule icon in search result */
        .search-item .schedule-icon {
            margin-left: 5px;
            color: #6b7280;
        }
    </style>

</head>
<body>

<!-- Navbar -->
@include('layouts.repnav')

<!-- Main Content -->
<div class="container">

    <!-- Patient Information -->
    @foreach ($patients as $patient)
        <div class="card appointment-card" id="patientCard{{ $patient->id }}" style="display: none;">
            <div class="card-header">
                <h5 class="card-title">Patient Information: {{ $patient->name }}</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="patient_name">Patient Name</label>
                    <input type="text" class="form-control" id="patient_name_{{ $patient->id }}" value="{{ $patient->name }}" readonly>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="text" class="form-control" id="dob_{{ $patient->id }}" value="{{ $patient->dob }}" readonly>
                </div>
                <!-- Add other patient details as needed -->
            </div>
        </div>
    @endforeach

    <!-- Appointment Form -->
    <form id="appointmentForm" action="{{ route('receptionist.appointments.store') }}" method="POST">
        @csrf
        <div class="card appointment-card mt-4">
            <div class="card-header">
                <h5 class="card-title">Rendez-vous Details</h5>
            </div>
            <div class="card-body">
                <!-- Reason for Rendez-vous -->
                <div class="form-group">
                    <label for="reason">Reason for Rendez-vous</label>
                    <input type="text" class="form-control" id="reason" name="reason" required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>

                <!-- Search for Doctor -->
                <!-- Search for Doctor -->
                <div class="form-group row">
                    <label for="searchDoctor" class="col-sm-3 col-form-label">Search for Doctor:</label>
                    <div class="col-sm-9">
                        <div class="input-group">
                            <input type="text" class="form-control search-input" id="searchDoctor" placeholder="Enter doctor's name or specialty">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="searchDoctors()">Search</button>
                            </div>
                        </div>
                        <div class="search-results" id="searchResults"></div>
                    </div>
                </div>


                <!-- Selected Doctors -->
                <div class="form-group" id="selectedDoctors">
                    <label>Select Doctors</label><br>
                    <!-- Selected doctors will be dynamically added here -->
                </div>

                <!-- Date and Time Selection -->
                <div class="form-group">
                    <label for="appointment_datetime">Select Appointment Date and Time</label>
                    <input type="text" class="form-control datepicker" id="appointment_datetime" name="appointment_datetime" required>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-success">Save Appointment</button>
        </div>
    </form>

    <!-- Doctor's Shifts Modal -->
    <div class="modal fade" id="doctorShiftsModal" tabindex="-1" role="dialog" aria-labelledby="doctorShiftsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="doctorShiftsModalLabel">Doctor's Shifts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="doctorShiftsBody">
                    <!-- Shifts will be dynamically added here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Include Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize datepicker
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });

        // Retrieve doctors data from PHP
        var doctors = @json($doctors);

        // Function to display search results
        function displaySearchResults(results) {
            var resultsContainer = $('#searchResults');
            resultsContainer.empty();

            results.forEach(function(doctor) {
                var resultHtml = '<div class="search-item" onclick="selectDoctor(' + doctor.id + ', \'' + doctor.name + '\', \'' + doctor.specialty + '\')">' +
                    '<span>' + doctor.name + ' - ' + doctor.specialty + '</span>' +
                    '<i class="fas fa-calendar-alt schedule-icon" onclick="showDoctorShifts(' + doctor.id + '); event.stopPropagation();"></i>' +
                    '</div>';
                resultsContainer.append(resultHtml);
            });

            resultsContainer.show();
        }

        // Function to handle search input
        $('#searchDoctor').on('input', function() {
            var searchText = $(this).val().trim().toLowerCase();
            var filteredDoctors = doctors.filter(function(doctor) {
                return doctor.name.toLowerCase().includes(searchText) || doctor.specialty.toLowerCase().includes(searchText);
            });

            displaySearchResults(filteredDoctors);
        });

        // Function to select a doctor
        window.selectDoctor = function(id, name, specialty) {
            var selectedDoctorsContainer = $('#selectedDoctors');
            var doctorHtml = '<div class="custom-control custom-checkbox">' +
                '<input type="checkbox" class="custom-control-input" id="doctor' + id + '" name="doctors[]" value="' + id + '">' +
                '<label class="custom-control-label" for="doctor' + id + '">' + name + ' - ' + specialty + '</label>' +
                '<i class="fas fa-calendar-alt schedule-icon" onclick="showDoctorShifts(' + id + ')"></i>' +
                '</div>';

            selectedDoctorsContainer.append(doctorHtml);

            // Clear search input and hide results
            $('#searchDoctor').val('');
            $('#searchResults').empty().hide();
        }

        // Function to show doctor's shifts modal
        window.showDoctorShifts = function(doctorId) {
            var selectedDoctor = doctors.find(function(doctor) {
                return doctor.id === doctorId;
            });

            if (!selectedDoctor) return;

            var selectedDoctorName = selectedDoctor.name;
            var selectedDoctorSpecialty = selectedDoctor.specialty;

            // Example shifts data, replace this with actual data fetching logic
            var shifts = [
                { shift_name: "Morning Shift", start_datetime: "2024-06-24 08:00", end_datetime: "2024-06-24 12:00" },
                { shift_name: "Evening Shift", start_datetime: "2024-06-24 14:00", end_datetime: "2024-06-24 18:00" }
            ];

            // Update modal title with doctor's name and specialty
            $('#doctorShiftsModalLabel').text(selectedDoctorName + ' - ' + selectedDoctorSpecialty + "'s Shifts");

            // Clear previous shifts
            $('#doctorShiftsBody').empty();

            // Add shifts to modal body
            if (shifts && shifts.length > 0) {
                shifts.forEach(function(shift) {
                    var shiftHtml = '<h5>' + shift.shift_name + '</h5>' +
                        '<p>Start Time: ' + shift.start_datetime + '</p>' +
                        '<p>End Time: ' + shift.end_datetime + '</p>' +
                        '<hr>';
                    $('#doctorShiftsBody').append(shiftHtml);
                });
            } else {
                $('#doctorShiftsBody').html('<p>No shifts available for this doctor.</p>');
            }

            // Show the modal
            $('#doctorShiftsModal').modal('show');
        }

        // Handle form submission
        $('#appointmentForm').submit(function(event) {
            // Prevent default form submission
            event.preventDefault();

            // Implement form submission logic here
            var formData = $(this).serialize();
            console.log(formData);
            // Example: Uncomment and adjust for actual form submission via AJAX
            // $.ajax({
            //     type: 'POST',
            //     url: $(this).attr('action'),
            //     data: formData,
            //     success: function(response) {
            //         console.log('Form submitted successfully');
            //         // Handle success response
            //     },
            //     error: function(error) {
            //         console.error('Error submitting form');
            //         // Handle error response
            //     }
            // });
        });

        // Close search results when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.form-group').length) {
                $('#searchResults').hide();
            }
        });

        // Prevent hiding when clicking inside search input
        $('#searchDoctor').on('click', function(e) {
            e.stopPropagation();
        });
    });
</script>
</body>
</html>

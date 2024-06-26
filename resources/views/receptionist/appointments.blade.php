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
            border-radius: 0.5rem;
            border: 1px solid rgba(0, 0, 0, 0.125);
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: none;
        }

        .modal-footer {
            border-top: none;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 9999; /* Increased z-index */
            color: #000; /* Adjust color as needed */
            opacity: 0.5; /* Adjust opacity for hover effect */
        }






        .search-input {
            width: 100%; /* Adjust width to match */
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .search-results {
            position: absolute;
            z-index: 1000;
            width: calc(100% - 60px); /* Adjust width */
            max-width: calc(100% - 2px); /* Ensure max width */
            max-height: 300px;
            overflow-y: auto;
            background-color: #fff;
            border: 1px solid rgba(0, 0, 0, 0.125);
            border-top: none;
            border-radius: 0 0 0.25rem 0.25rem;
            display: none;
        }

        .search-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1.25rem;
            cursor: pointer;
            transition: background-color 0.3s;
            position: relative; /* Ensure proper positioning of schedule icon */
        }

        .search-item:hover {
            background-color: #f8f9fa;
        }

        .search-item .schedule-icon {
            font-size: 1rem;
            color: #6b7280;
            cursor: pointer;
        }
    </style>
</head>
<body>
@include('layouts.repnav')

<!-- Main Content -->
<div class="container">
    <!-- Appointment Form -->
    <form id="appointmentForm" action="{{ route('receptionist.appointments.store') }}" method="POST">
        @csrf
        <div class="card appointment-card mt-4">
            <div class="card-header">
                <h5 class="card-title">Rendez-vous Details</h5>
            </div>
            <div class="card-body">
                <!-- Search for Patient -->
                <div class="form-group">
                    <label for="searchPatient">Search for Patient:</label>
                    <input type="text" class="form-control search-input" id="searchPatient" placeholder="Enter patient's name or ID" onkeyup="searchPatients()">
                    <div class="search-results" id="searchResultsPatient"></div>
                </div>

                <!-- Patient Information (inside Rendez-vous Details card) -->
                <div id="patientInfo" style="display: none;">
                    <div class="form-group">
                        <label for="patient_name">Patient Name</label>
                        <input type="text" class="form-control" id="patient_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="text" class="form-control" id="dob" readonly>
                    </div>
                    <!-- Add other patient details as needed -->
                </div>

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
                <div class="form-group">
                    <label for="searchDoctor">Search for Doctor:</label>
                    <input type="text" class="form-control search-input" id="searchDoctor" placeholder="Enter doctor's name or specialty" onkeyup="searchDoctors()">
                    <div class="search-results" id="searchResultsDoctor"></div>
                </div>

                <!-- Doctor Information (inside Rendez-vous Details card) -->
                <div id="doctorInfo" style="display: none;">
                    <div class="form-group">
                        <label for="doctor_name">Doctor Name</label>
                        <input type="text" class="form-control" id="doctor_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="specialty">Specialty</label>
                        <input type="text" class="form-control" id="specialty" readonly>
                    </div>
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
</div>
<!-- Doctor's Calendar Modal -->
<div class="modal fade" id="doctorCalendarModal" tabindex="-1" role="dialog" aria-labelledby="doctorCalendarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content rounded-3 border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="doctorCalendarModalLabel">Doctor's Calendar</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Close button within modal body -->
                <button type="button" class="close close-btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <!-- Calendar content will be loaded here dynamically -->
                <!-- Leave this empty to be populated dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <!-- Additional Close Button -->
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



<!-- Include jQuery and Bootstrap JS -->
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

        // Retrieve patients and doctors data from PHP
        var patients = @json($patients);
        var doctors = @json($doctors);

        // Function to search and display patients
        function searchPatients() {
            var searchTerm = $('#searchPatient').val().toLowerCase();
            if (searchTerm === '') {
                $('#searchResultsPatient').hide();
                return;
            }
            var results = patients.filter(patient =>
                patient.name.toLowerCase().startsWith(searchTerm) ||
                patient.id.toString().includes(searchTerm)
            );
            displaySearchResults(results, '#searchResultsPatient', 'patient');
        }

        // Function to search and display doctors
        function searchDoctors() {
            var searchTerm = $('#searchDoctor').val().toLowerCase();
            if (searchTerm === '') {
                $('#searchResultsDoctor').hide();
                return;
            }
            var results = doctors.filter(doctor =>
                doctor.name.toLowerCase().startsWith(searchTerm) ||
                doctor.specialty.toLowerCase().startsWith(searchTerm)
            );
            displaySearchResults(results, '#searchResultsDoctor', 'doctor');
        }

        // Function to display search results
        function displaySearchResults(results, resultsContainerId, type) {
            var resultsContainer = $(resultsContainerId);
            resultsContainer.empty();
            if (results.length > 0) {
                results.forEach(item => {
                    var resultItem = $('<div class="search-item"></div>');
                    if (type === 'patient') {
                        resultItem.text(item.name);
                        resultItem.data('patient', item);
                        resultItem.click(function() {
                            selectPatient($(this).data('patient'));
                            resultsContainer.hide();
                        });
                    } else if (type === 'doctor') {
                        var doctorInfo = $('<div class="d-flex justify-content-between align-items-center"></div>');
                        var doctorName = $('<span></span>').text(item.name + ' (' + item.specialty + ')');
                        var scheduleButton = $('<button class="btn btn-outline-secondary schedule-btn">View Calendar</button>');
                        // Pass the doctor object to the modal
                        scheduleButton.click(function(e) {
                            e.preventDefault(); // Prevent form submission
                            e.stopPropagation(); // Prevent hiding of resultsContainer
                            openDoctorShiftsModal(item);
                        });
                        doctorInfo.append(doctorName, scheduleButton);
                        resultItem.append(doctorInfo);
                        resultItem.data('doctor', item);
                        resultItem.click(function() {
                            selectDoctor($(this).data('doctor'));
                            resultsContainer.hide();
                        });
                    }
                    resultsContainer.append(resultItem);
                });
                resultsContainer.show();
            } else {
                resultsContainer.hide();
            }
        }

        // Function to select patient
        function selectPatient(patient) {
            $('#patient_name').val(patient.name);
            $('#dob').val(patient.dob);
            $('#patientInfo').show();
        }

        // Function to select doctor
        function selectDoctor(doctor) {
            $('#doctor_name').val(doctor.name);
            $('#specialty').val(doctor.specialty);
            $('#doctorInfo').show();
        }

        // Function to open doctor's shifts modal
        // Function to open doctor's shifts modal
        function openDoctorShiftsModal(doctor) {
            // AJAX request to fetch calendar data
            $.ajax({
                url: '{{ route('receptionist.doctorcalendar', ['doctor' => ':doctor_id']) }}'.replace(':doctor_id', doctor.id),
                method: 'GET',
                success: function(data) {
                    $('#doctorCalendarModal .modal-content').html(data); // Load calendar view into modal content
                    $('#doctorCalendarModal').modal('show'); // Show modal after content is loaded
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // Handle error case here
                }
            });
        }


        // Attach search functions to the window object to make them accessible
        window.searchPatients = searchPatients;
        window.searchDoctors = searchDoctors;
    });

</script>

</script>

</body>
</html>

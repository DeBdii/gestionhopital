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
            cursor: pointer;
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
                        <input type="hidden" id="patient_id" name="patient_id">
                        <input type="hidden" id="doctor_id" name="doctor_id">
                        <button type="button" class="btn btn-link" id="viewCalendarBtn">View Calendar</button>
                    </div>
                </div>

                <!-- Date Selection -->
                <div class="form-group">
                    <label for="appointment_date">Select Appointment Date</label>
                    <input type="text" class="form-control datepicker" id="appointment_date" name="appointment_date" required>
                </div>

                <!-- Time Selection -->
                <div class="form-group">
                    <label for="appointment_time">Select Appointment Time</label>
                    <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-success">Save Appointment</button>
        </div>
    </form>
</div>

<!-- Modal -->
<div id="calendarModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg w-full max-w-4xl modal-content">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 id="modalTitle" class="text-xl font-semibold text-gray-900 dark:text-gray-100">Doctor's Calendar</h2>
                <button id="closeModal" class="close-btn">&times;</button>
            </div>
            <iframe id="calendarFrame" src="" class="w-full h-96"></iframe>
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
        // Initialize datepicker for date selection
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
                patient.name.toLowerCase().includes(searchTerm) ||
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
                doctor.name.toLowerCase().includes(searchTerm)
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
                        resultItem.text(item.name);
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

        function selectPatient(patient) {
            $('#patient_name').val(patient.name);
            $('#dob').val(patient.dob);
            $('#patient_id').val(patient.id); // Update hidden input field with patient's ID
            $('#patientInfo').show();
        }

        function selectDoctor(doctor) {
            $('#doctor_name').val(doctor.name);
            $('#specialty').val(doctor.specialty);
            $('#doctor_id').val(doctor.id); // Update hidden input field with doctor's ID
            $('#doctorInfo').show();
        }


        // Attach search functions to the window object to make them accessible
        window.searchPatients = searchPatients;
        window.searchDoctors = searchDoctors;
        $('#viewCalendarBtn').click(function() {
            openDoctorCalendarModal();
        });

        // Function to open the doctor's calendar modal
        function openDoctorCalendarModal() {
            var doctorId = $('#doctor_id').val();
            var calendarUrl = `{{ route('receptionist.doctors.calendar', ['doctorId' => ':doctorId']) }}`;
            calendarUrl = calendarUrl.replace(':doctorId', doctorId);
            $('#calendarFrame').attr('src', calendarUrl);
            $('#calendarModal').removeClass('hidden').addClass('flex');
        }

        // Function to close the doctor's calendar modal
        $('#closeModal').click(function() {
            closeDoctorCalendarModal();
        });

        function closeDoctorCalendarModal() {
            $('#calendarModal').removeClass('flex').addClass('hidden');
            $('#calendarFrame').attr('src', '');
        }
    });
</script>
</body>
</html>

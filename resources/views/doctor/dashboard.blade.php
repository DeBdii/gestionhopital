<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .bg-custom {
            background-image: url('https://source.unsplash.com/random/1600x900?warm');
            background-size: cover;
            background-position: center;
        }
        .card {
            border-radius: 1rem;
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            background: rgba(255, 255, 255, 0.8);
            margin-top: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 10px -1px rgba(0, 0, 0, 0.2), 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .stat-number {
            font-size: 2.5rem;
        }
        .modal-content {
            border-radius: 2rem;
        }
        .text-light {
            color: #f0f0f0;
        }
    </style>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon1.png') }}">
</head>

<body class="bg-custom bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-200">
@include('layouts.doc')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">

            <!-- Welcome Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg card w-full md:w-80">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Bienvenue, {{ $doctor->name }}!</h2>
                    @if($shiftStartTime !== 'N/A')
                        <p class="text-gray-900 dark:text-gray-100 mt-2">
                            <a href="{{ route('doctor.schedule.index') }}" class="text-blue-500 hover:underline">
                                Votre shift aujourd'hui est de {{ $shiftStartTime }} à {{ $shiftEndTime }}.
                            </a>
                        </p>
                    @else
                        <p class="text-gray-900 dark:text-gray-100 mt-2">Vous n'avez pas de shift aujourd'hui.</p>
                    @endif
                </div>
            </div>

            <!-- Today's Appointments Card -->
            <div class="bg-blue-200 dark:bg-blue-700 overflow-hidden shadow-lg card w-full md:w-80">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Today's Appointments</h3>
                    @if($todayAppointments->isEmpty())
                        <p class="text-gray-900 dark:text-gray-100">Aucun rendez-vous aujourd'hui.</p>
                    @else
                        <ul class="list-disc pl-5 text-gray-900 dark:text-gray-100">
                            @foreach($todayAppointments as $appointment)
                                <li>
                                    <a href="{{ route('doctor.patients.show', ['patient' => $appointment->patient->id]) }}" class="text-blue-500 hover:underline">
                                        {{ $appointment->patient->name }}
                                    </a> à {{ $appointment->appointment_date }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Overall Patients Card -->
            <div class="bg-green-200 dark:bg-green-700 overflow-hidden shadow-lg card w-full md:w-80">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Overall Patients</h3>
                    <p class="text-3xl text-gray-900 dark:text-gray-100 stat-number">{{ $patientsCount }}</p>
                </div>
            </div>

            <!-- Department Items Card -->
            <div class="bg-yellow-200 dark:bg-yellow-700 overflow-hidden shadow-lg card w-full md:w-80">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Department Items and Quantities</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach($departmentItems as $item)
                            <li class="flex justify-between py-2 text-gray-900 dark:text-gray-100">
                                <span>{{ $item->name }}</span>
                                <span>{{ $item->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

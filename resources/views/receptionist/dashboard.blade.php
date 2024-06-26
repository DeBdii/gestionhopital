<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.7/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-200">
@include('layouts.repnav')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Available Doctors Card -->
            @foreach ($availableDoctorsCount as $department)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Available Doctors in {{ $department->department_name }}</h3>
                        <p class="text-gray-700 dark:text-gray-300">Count: {{ $department->users_count }}</p>
                    </div>
                </div>
            @endforeach

            <!-- Items Quantity Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Items Quantity</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach ($itemsQuantity as $item)
                            <li class="flex justify-between py-2">
                                <span class="text-gray-700 dark:text-gray-300">{{ $item->name }}</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $item->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Appointments Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Appointments</h3>
                    <p class="text-3xl text-gray-900 dark:text-gray-100">{{ $appointmentsCount }}</p>
                </div>
            </div>

            <!-- Patients Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Patients</h3>
                    <p class="text-3xl text-gray-900 dark:text-gray-100">{{ $patientsCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

</body>

</html>

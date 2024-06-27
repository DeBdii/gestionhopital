<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            background: rgba(255, 255, 255, 0.8); /* light background */
            margin-top: 2rem; /* to drop the cards a little lower */
        }
        .card:hover {
            transform: scale(1.05);
        }
        .modal-content {
            border-radius: 2rem;
        }
        .stat-number {
            font-size: 4rem;
        }
        .text-light {
            color: #f0f0f0;
        }
    </style>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon1.png') }}">
</head>

<body class="bg-custom bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-200">
@include('layouts.fakenavdebdii')

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">
            <!-- Available Doctors Card -->
            @foreach ($availableDoctorsCount as $department)
                <div class="bg-blue-200 dark:bg-blue-700 overflow-hidden shadow-lg card w-full md:w-80">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Available Doctors in {{ $department->department_name }}</h3>
                        <p class="text-gray-900 dark:text-gray-100 mb-2">Count: {{ $department->users_count }}</p>
                        <ul class="list-disc pl-5 text-gray-900 dark:text-gray-100">
                            @foreach ($department->users as $doctor)
                                <li>
                                    {{ $doctor->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach

            <!-- Items Quantity Card -->
            <div class="bg-green-200 dark:bg-green-700 overflow-hidden shadow-lg card w-full md:w-80">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Items Quantity</h3>
                    <ul class="divide-y divide-gray-200">
                        @foreach ($itemsQuantity as $item)
                            <li class="flex justify-between py-2 text-gray-900 dark:text-gray-100">
                                <span>{{ $item->name }}</span>
                                <span>{{ $item->quantity }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- Staff Available Card -->
            <!-- Staff Available Card -->
            <div class="bg-gray-200 dark:bg-gray-700 overflow-hidden shadow-lg card w-full md:w-80">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Staff Available</h3>
                    <ul class="divide-y divide-gray-200">
                        <li class="flex justify-between py-2 text-gray-900 dark:text-gray-100">

                            <!-- Receptionists Section -->
                            <div class="mb-4">
                                <h4 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Receptionists</h4>
                                <p class="text-gray-900 dark:text-gray-100 mb-2">Count: {{ $receptionistsCount }}</p>
                                <!-- List receptionists if needed -->
                            </div>
                        </li>
                        <li class="flex justify-between py-2 text-gray-900 dark:text-gray-100">
                            <!-- Nurses Section -->
                            <div class="mb-4">
                                <h4 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Nurses</h4>
                                <p class="text-gray-900 dark:text-gray-100 mb-2">Count: {{ $nursesCount }}</p>
                                <!-- List nurses if needed -->
                            </div>
                        </li>
                        <li class="flex justify-between py-2 text-gray-900 dark:text-gray-100">

                            <!-- Support Staff Section -->
                            <div>
                                <h4 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">Support Staff</h4>
                                <p class="text-gray-900 dark:text-gray-100 mb-2">Count: {{ $supportStaffCount }}</p>
                                <!-- List support staff if needed -->
                            </div>
                        </li>
                </div>
            </div>


            <!-- Patients Card -->
            <div class="bg-red-200 dark:bg-red-700 overflow-hidden shadow-lg card w-full md:w-80">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Patients</h3>
                    <p class="text-3xl text-gray-900 dark:text-gray-100 stat-number">{{ $patientsCount }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="calendarModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-lg w-full max-w-4xl modal-content">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 id="modalTitle" class="text-xl font-semibold text-gray-900 dark:text-gray-100">Doctor's Calendar</h2>
                <button id="closeModal" class="text-gray-900 dark:text-gray-100">&times;</button>
            </div>
            <iframe id="calendarFrame" src="" class="w-full h-96"></iframe>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('calendarModal');
        const calendarFrame = document.getElementById('calendarFrame');
        const closeModal = document.getElementById('closeModal');
        const modalTitle = document.getElementById('modalTitle');

        document.querySelectorAll('a[data-doctor-id]').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const doctorId = this.getAttribute('data-doctor-id');
                const doctorName = this.getAttribute('data-doctor-name');
                calendarFrame.src = `{{ url('receptionist/doctors') }}/${doctorId}/calendar`;
                modalTitle.textContent = `${doctorName}'s Calendar`;
                modal.classList.remove('hidden');
            });
        });

        closeModal.addEventListener('click', function () {
            modal.classList.add('hidden');
            calendarFrame.src = '';
        });

        window.addEventListener('click', function (e) {
            if (e.target === modal) {
                modal.classList.add('hidden');
                calendarFrame.src = '';
            }
        });
    });
</script>

</body>

</html>

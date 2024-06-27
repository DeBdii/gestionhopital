<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Items</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon1.png') }}">
</head>

<body class="bg-gray-100">
<!-- Navbar -->
@include('layouts.repnav')

<!-- Content -->
<div class="container mx-auto mt-4">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-blue-600 mb-4">Items List</h2>

        <!-- Table Container -->
        <div class="table-container">
            <!-- Items List Table -->
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Item Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Description</th>
                        <th scope="col">Dosage</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Example rows (Replace with dynamic content from your backend) -->
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->description ?? '-' }}</td>
                            <td>{{ $item->dosage ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">
                                <div class="alert alert-warning">No items found.</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS, jQuery, and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Custom JavaScript -->
<script>
    // Custom JS can be added here
</script>

</body>

</html>

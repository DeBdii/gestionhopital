<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
@include('layouts.fakenavdebdii')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Staff Management</h1>
            <!-- Add Staff Button -->
            <a href="{{ route('admin.staff.create') }}" class="btn btn-primary">Add Staff</a>
        </div>

        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-700">Filter by Role:</label>
            <select id="role" name="role"
                    class="form-control mt-1 w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                <option value="">All</option>
                <option value="Receptionist">Réceptionniste</option>
                <option value="Nurse">Infirmier/infirmière</option>
                <option value="SupportStaff">Staff de support</option>
            </select>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                @foreach($staffMembers as $staffMember)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6">{{ $staffMember->name }}</td>
                        <td class="py-3 px-6">{{ $staffMember->email }}</td>
                        <td class="py-3 px-6">{{ $staffMember->user_type }}</td>
                        <td class="py-3 px-6">
                            <a href="{{ route('admin.staff.edit', $staffMember->id) }}"
                               class="btn btn-sm btn-info mr-2">Edit</a>
                            <form action="{{ route('admin.staff.destroy', $staffMember->id) }}" method="POST"
                                  class="inline">
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

<!-- Bootstrap JS, jQuery, and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Custom JavaScript for filtering -->
<script>
    document.getElementById('role').addEventListener('change', function () {
        var role = this.value; // Get the selected role

        // Loop through table rows and show/hide based on selected role
        var rows = document.querySelectorAll('tbody tr');
        rows.forEach(function (row) {
            var cell = row.querySelector('td:nth-child(3)'); // Assuming user type is in the third column
            if (role === '' || cell.textContent === role) {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
</body>

</html>

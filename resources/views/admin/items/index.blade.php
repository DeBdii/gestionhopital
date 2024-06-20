<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Items</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

@include('layouts.fakenavdebdii')

<div class="container py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-semibold mb-4">Manage Items</h2>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="mb-4">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createItemModal">
                        Add New Item
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Description</th>
                            <th scope="col">Dosage</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->description ?? '-' }}</td>
                                <td>{{ $item->dosage ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Item Actions">
                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#editItemModal{{ $item->id }}">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.items.delete', ['item' => $item->id]) }}" method="POST" class="delete-item-form ml-2">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No items found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Item Modal -->
<div class="modal fade" id="createItemModal" tabindex="-1" role="dialog" aria-labelledby="createItemModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createItemModalLabel">Add New Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.items.store') }}" method="POST" id="createItemForm">
                    @csrf

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="dosage">Dosage</label>
                        <input type="text" name="dosage" id="dosage" class="form-control">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Create Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Item Modals -->
@foreach ($items as $item)
    <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.items.update', ['item' => $item->id]) }}" method="POST" id="editItemForm{{ $item->id }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="edit_name{{ $item->id }}">Name</label>
                            <input type="text" name="name" id="edit_name{{ $item->id }}" class="form-control" value="{{ $item->name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_quantity{{ $item->id }}">Quantity</label>
                            <input type="number" name="quantity" id="edit_quantity{{ $item->id }}" class="form-control" value="{{ $item->quantity }}" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_description{{ $item->id }}">Description</label>
                            <textarea name="description" id="edit_description{{ $item->id }}" class="form-control">{{ $item->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="edit_dosage{{ $item->id }}">Dosage</label>
                            <input type="text" name="dosage" id="edit_dosage{{ $item->id }}" class="form-control" value="{{ $item->dosage }}">
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

<!-- Bootstrap JS, jQuery, and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Custom JavaScript -->
<script>
    


</script>

</body>
</html>

@extends('layouts.master')

@section('title', 'Manage Lecture Halls')

@section('content')

<!-- Success & Error Messages -->
<div id="messageBox"></div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Lecture Halls</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New Hall</button>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="hallTable">
        @foreach ($lectureHalls as $hall)
        <tr id="row-{{ $hall->id }}">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $hall->name }}</td>
            <td>
                <button class="btn btn-warning btn-sm editBtn"
                    data-id="{{ $hall->id }}"
                    data-name="{{ $hall->name }}"
                    data-bs-toggle="modal"
                    data-bs-target="#editModal">Edit</button>
                
                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $hall->id }}">Delete</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Add Lecture Hall Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Lecture Hall</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="addName" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Hall</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Lecture Hall Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Lecture Hall</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Hall</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {

    function showMessage(type, message) {
        let messageBox = $('#messageBox');
        messageBox.html(`<div class="alert alert-${type}">${message}</div>`);
    }

    // Add Lecture Hall
    $('#addForm').submit(function(e) {
        e.preventDefault();
        $.post("{{ route('admin.lecturehalls.store') }}", $(this).serialize(), function(response) {
            if (response.success) {
                showMessage('success', response.message);
                location.reload();
            }
        }).fail(function() {
            showMessage('danger', 'Failed to add hall.');
        });
    });

    // Open Edit Modal
    $('.editBtn').on('click', function() {
        $('#editId').val($(this).data('id'));
        $('#editName').val($(this).data('name'));
    });

    // Edit Lecture Hall
    $('#editForm').submit(function(e) {
        e.preventDefault();
        let hallId = $('#editId').val();
        let formData = $(this).serialize();

        $.ajax({
            url: '/admin/lecturehalls/' + hallId,
            type: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.message);
                    location.reload();
                }
            },
            error: function() {
                showMessage('danger', 'Failed to update hall.');
            }
        });
    });

    // Delete Lecture Hall
    $('.deleteBtn').on('click', function() {
        if (confirm('Are you sure?')) {
            $.ajax({
                url: '/admin/lecturehalls/' + $(this).data('id'),
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    showMessage('success', 'Lecture Hall deleted successfully.');
                    location.reload();
                },
                error: function() {
                    showMessage('danger', 'Failed to delete hall.');
                }
            });
        }
    });

});
</script>
@endsection

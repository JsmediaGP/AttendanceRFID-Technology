{{-- @extends('layouts.master')

@section('title', 'Manage Courses')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif


<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Courses</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New Course</button>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>S/N</th>
            <th>Course Code</th>
            <th>Name</th>
            <th>Lecturer</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="courseTable">
        @foreach ($courses as $course)
        <tr id="row-{{ $course->id }}">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $course->course_code }}</td>
            <td>{{ $course->name }}</td>
            <td>{{ $course->lecturer->name }}</td>
            <td>
                <button class="btn btn-warning btn-sm editBtn" data-id="{{ $course->id }}" data-code="{{ $course->course_code }}" data-name="{{ $course->name }}" data-lecturer="{{ $course->lecturer_id }}" data-bs-toggle="modal" data-bs-target="#editModal">Edit</button>
                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $course->id }}">Delete</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Add Course Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="course_code" id="addCourseCode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="name" id="addName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lecturer</label>
                        <select name="lecturer_id" id="addLecturer" class="form-control" required>
                            <option value="">Select Lecturer</option>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="course_code" id="editCourseCode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lecturer</label>
                        <select name="lecturer_id" id="editLecturer" class="form-control" required>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Function to show messages
    function showMessage(type, message) {
        let messageBox = $('#messageBox');
        messageBox.html(`<div class="alert alert-${type}">${message}</div>`);
    }
    // Add Course
    $('#addForm').submit(function(e) {
        e.preventDefault();
        $.post("{{ route('admin.courses.store') }}", $(this).serialize(), function(response) {
            if (response.success) showMessage('success', response.message);
            location.reload();
        }).fail(function() { showMessage('danger', 'Failed to add course.'); });
    });

    // Open Edit Modal
    $('.editBtn').on('click', function() {
        $('#editId').val($(this).data('id'));
        $('#editCourseCode').val($(this).data('code'));
        $('#editName').val($(this).data('name'));
        $('#editLecturer').val($(this).data('lecturer'));
    });
    // Edit Course (AJAX)
    $('#editForm').submit(function(e) {
        e.preventDefault();
        let courseId = $('#editId').val();
        let formData = $(this).serialize();

        $.ajax({
            url: '/admin/courses/' + courseId,
            type: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.message);
                    location.reload();
                }
            },
            error: function() {
                showMessage('danger', 'Failed to update course.');
            }
        });
    });

    // Delete Course
    $('.deleteBtn').on('click', function() {
        if (confirm('Are you sure?')) {
            $.ajax({
                url: '/admin/courses/' + $(this).data('id') + '/delete',
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    showMessage('success', 'Course deleted successfully.');
                    location.reload();
                },
                error: function() {
                    showMessage('danger', 'Failed to delete course.');
                }
            });
        }
    });

});
</script>
@endsection --}}

@extends('layouts.master')

@section('title', 'Manage Courses')

@section('content')

<!-- Success & Error Messages -->
<div id="messageBox">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Courses</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New Course</button>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>S/N</th>
            <th>Course Code</th>
            <th>Name</th>
            <th>Lecturer</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="courseTable">
        @foreach ($courses as $course)
        <tr id="row-{{ $course->id }}">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $course->course_code }}</td>
            <td>{{ $course->name }}</td>
            <td>{{ $course->lecturer->name }}</td>
            <td>
                <button class="btn btn-warning btn-sm editBtn"
                    data-id="{{ $course->id }}"
                    data-code="{{ $course->course_code }}"
                    data-name="{{ $course->name }}"
                    data-lecturer="{{ $course->lecturer_id }}"
                    data-bs-toggle="modal"
                    data-bs-target="#editModal">Edit</button>
                
                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $course->id }}">Delete</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Add Course Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="course_code" id="addCourseCode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="name" id="addName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lecturer</label>
                        <select name="lecturer_id" id="addLecturer" class="form-control" required>
                            <option value="">Select Lecturer</option>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="course_code" id="editCourseCode" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lecturer</label>
                        <select name="lecturer_id" id="editLecturer" class="form-control" required>
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Course</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function() {

    // Function to show messages
    function showMessage(type, message) {
        let messageBox = $('#messageBox');
        messageBox.html(`<div class="alert alert-${type}">${message}</div>`);
    }

    // Add Course
    $('#addForm').submit(function(e) {
        e.preventDefault();
        $.post("{{ route('admin.courses.store') }}", $(this).serialize(), function(response) {
            if (response.success) {
                showMessage('success', response.message);
                location.reload();
            }
        }).fail(function() {
            showMessage('danger', 'Failed to add course.');
        });
    });

    // Open Edit Modal
    $('.editBtn').on('click', function() {
        $('#editId').val($(this).data('id'));
        $('#editCourseCode').val($(this).data('code'));
        $('#editName').val($(this).data('name'));
        $('#editLecturer').val($(this).data('lecturer'));
    });

    // Edit Course (AJAX)
    $('#editForm').submit(function(e) {
        e.preventDefault();
        let courseId = $('#editId').val();
        let formData = $(this).serialize();

        $.ajax({
            url: '/admin/courses/' + courseId,
            type: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    showMessage('success', response.message);
                    location.reload();
                }
            },
            error: function() {
                showMessage('danger', 'Failed to update course.');
            }
        });
    });

    // Delete Course
    $('.deleteBtn').on('click', function() {
        if (confirm('Are you sure?')) {
            $.ajax({
                url: '/admin/courses/' + $(this).data('id') + '/delete',
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    showMessage('success', 'Course deleted successfully.');
                    location.reload();
                },
                error: function() {
                    showMessage('danger', 'Failed to delete course.');
                }
            });
        }
    });

});
</script>
@endsection

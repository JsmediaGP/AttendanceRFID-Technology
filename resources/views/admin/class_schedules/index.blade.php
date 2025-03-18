@extends('layouts.master')

@section('title', 'Manage Class Schedules')

@section('content')

<!-- Success & Error Messages -->
<div id="messageBox"></div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Class Schedules</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add New Schedule</button>
</div>

<!-- Filters -->
<div class="mb-3">
    <form id="filterForm">
        <div class="row">
            <div class="col-md-2">
                <select class="form-control" name="course_id" id="filterCourse">
                    <option value="">Filter by Course</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="lecturer_id" id="filterLecturer">
                    <option value="">Filter by Lecturer</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->lecturer_id }}">{{ $course->lecturer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">  
                <select class="form-control" name="lecture_hall_id" id="filterHall">  
                    <option value="">Filter by Lecture Hall</option>  
                    @foreach ($lectureHalls as $hall)  
                        <option value="{{ $hall->id }}">{{ $hall->name }}</option>  
                    @endforeach  
                </select>  
            </div>  
            <div class="col-md-2">
                <select class="form-control" name="day" id="filterDay">
                    <option value="">Filter by Day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-dark">Apply Filters</button>
            </div>
        </div>
    </form>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Course</th>
            <th>Lecturer</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Day</th>
            <th>Lecture Hall</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="scheduleTable">
        @foreach ($schedules as $schedule)
        <tr id="row-{{ $schedule->id }}">
            <td>{{ $schedule->course->name }}</td>
            <td>{{ $schedule->course->lecturer->name }}</td>
            <td>{{ $schedule->start_time }}</td>
            <td>{{ $schedule->end_time }}</td>
            <td>{{ $schedule->day }}</td>
            <td>{{ $schedule->lectureHall->name }}</td>
            <td>
                <button class="btn btn-warning btn-sm editBtn"
                    data-id="{{ $schedule->id }}"
                    data-course="{{ $schedule->course_id }}"
                    data-start="{{ $schedule->start_time }}"
                    data-end="{{ $schedule->end_time }}"
                    data-day="{{ $schedule->day }}"
                    data-hall="{{ $schedule->lecture_hall_id }}"
                    data-bs-toggle="modal"
                    data-bs-target="#editModal">Edit</button>

                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $schedule->id }}">Delete</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>




<!-- Add Schedule Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Class Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addScheduleForm">
                    @csrf
                    <div class="mb-3">
                        <label>Course</label>
                        <select class="form-control" name="course_id" required>
                            <option value="">Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Start Time</label>
                        <input type="time" class="form-control" name="start_time" required>
                    </div>
                    <div class="mb-3">
                        <label>End Time</label>
                        <input type="time" class="form-control" name="end_time" required>
                    </div>
                    <div class="mb-3">
                        <label>Day</label>
                        <select class="form-control" name="day" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Lecture Hall</label>
                        <select class="form-control" name="lecture_hall_id" required>
                            <option value="">Select Hall</option>
                            @foreach ($lectureHalls as $hall)
                                <option value="{{ $hall->id }}">{{ $hall->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Schedule</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Schedule Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Class Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editScheduleForm">
                    @csrf
                    <input type="hidden" name="schedule_id">
                    <div class="mb-3">
                        <label>Course</label>
                        <select class="form-control" name="course_id" required>
                            <option value="">Select Course</option>
                            @foreach ($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Start Time</label>
                        <input type="time" class="form-control" name="start_time" required>
                    </div>
                    <div class="mb-3">
                        <label>End Time</label>
                        <input type="time" class="form-control" name="end_time" required>
                    </div>
                    <div class="mb-3">
                        <label>Day</label>
                        <select class="form-control" name="day" required>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Lecture Hall</label>
                        <select class="form-control" name="lecture_hall_id" required>
                            <option value="">Select Hall</option>
                            @foreach ($lectureHalls as $hall)
                                <option value="{{ $hall->id }}">{{ $hall->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Schedule</button>
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

    // Apply Filters
    $('#filterForm').submit(function(e) {
        e.preventDefault();
        window.location.href = "{{ route('admin.schedules.index') }}?" + $(this).serialize();
    });
// Add Schedule
    $('#addScheduleForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('admin.schedules.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(response) {
                showMessage('success', 'Class schedule added successfully.');
                location.reload();
            },
            error: function() {
                showMessage('danger', 'Failed to add schedule.');
            }
        });
    });

    // Edit Schedule - Load Data
    $('.editBtn').click(function() {
        let modal = $('#editModal');
        modal.find('[name="schedule_id"]').val($(this).data('id'));
        modal.find('[name="course_id"]').val($(this).data('course'));
        modal.find('[name="start_time"]').val($(this).data('start'));
        modal.find('[name="end_time"]').val($(this).data('end'));
        modal.find('[name="day"]').val($(this).data('day'));
        modal.find('[name="lecture_hall_id"]').val($(this).data('hall'));
    });

    // Update Schedule  
    $('#editScheduleForm').submit(function(e) {  
        e.preventDefault();  
        let scheduleId = $(this).find('[name="schedule_id"]').val();  
        $.ajax({  
            url: "/admin/schedules/" + scheduleId,  
            type: "PUT",  
            data: $(this).serialize() + '&_token={{ csrf_token() }}', // Include CSRF token  
            success: function(response) {  
                showMessage('success', 'Class schedule updated successfully.');  
                location.reload();  
            },  
            error: function(xhr) {  
                
                let errorMessage = xhr.responseJSON?.message || 'Failed to update schedule.';  
                showMessage('danger', errorMessage);  
            }  
        });  
    }); 

    // // Update Schedule
    // $('#editScheduleForm').submit(function(e) {
    //     e.preventDefault();
    //     let scheduleId = $(this).find('[name="schedule_id"]').val();
    //     $.ajax({
    //         url: "/admin/schedules/" + scheduleId,
    //         type: "PUT",
    //         data: $(this).serialize(),
    //         success: function(response) {
    //             showMessage('success', 'Class schedule updated successfully.');
    //             location.reload();
    //         },
    //         error: function() {
    //             showMessage('danger', 'Failed to update schedule.');
    //         }
    //     });
    // });

    // Delete Schedule
    $('.deleteBtn').click(function() {
        if (confirm('Are you sure?')) {
            let scheduleId = $(this).data('id');
            $.ajax({
                url: "/admin/schedules/" + scheduleId,
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    showMessage('success', 'Class schedule deleted.');
                    location.reload();
                },
                error: function() {
                    showMessage('danger', 'Failed to delete schedule.');
                }
            });
        }
    });

});
</script>
@endsection
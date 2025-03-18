{{-- @extends('layouts.master')
@section('title', 'Lecturer Dashboard')

@section('content')
<div class="container">
    <h2>Course & Class Schedule</h2>

    <!-- Courses List -->
    <div class="card mb-4">
        <div class="card-header">Your Courses</div>
        <div class="card-body">
            <ul class="list-group">
                @foreach($courses as $course)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $course->name }} ({{ $course->course_code }})
                        <a href="{{ route('lecturer.view.attendance', $course->id) }}" class="btn btn-info btn-sm">View Attendance</a>

                        
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Class Schedule Section -->
    <div class="card">
        <div class="card-header">Class Schedules</div>
        <div class="card-body">
            <!-- Filters -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="filterCourse" class="form-label">Course</label>
                    <select id="filterCourse" class="form-control">
                        <option value="">All Courses</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filterDay" class="form-label">Day</label>
                    <select id="filterDay" class="form-control">
                        <option value="">All Days</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button class="btn btn-primary w-100" id="applyFilters">Filter</button>
                </div>
            </div>

            <hr> <!-- Line separator -->

            <!-- Class Schedule Table -->
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="scheduleTableBody">
                        @foreach($classSchedules as $schedule)
                        <tr>
                            <td>{{ $schedule->course->name }} ({{ $schedule->course->course_code }})</td>
                            <td>{{ $schedule->day }}</td>
                            <td>{{ date('h:i A', strtotime($schedule->start_time)) }}</td>
                            <td>{{ date('h:i A', strtotime($schedule->end_time)) }}</td>
                            <td>
                                <span class="badge {{ $schedule->status == 'holding' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $schedule->status }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm editStatusBtn" data-id="{{ $schedule->id }}" data-status="{{ $schedule->status }}">
                                    Edit
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- JavaScript for Filtering -->
<script>
document.getElementById("applyFilters").addEventListener("click", function() {
    let courseId = document.getElementById("filterCourse").value;
    let day = document.getElementById("filterDay").value;

    fetch("{{ route('lecturer.filter.schedule') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ course_id: courseId, day: day })
    })
    .then(response => response.json())
    .then(data => {
        let tableBody = document.getElementById("scheduleTableBody");
        tableBody.innerHTML = "";

        if (data.length > 0) {
            data.forEach(schedule => {
                let row = `<tr>
                    <td>${schedule.course.name} (${schedule.course.course_code})</td>
                    <td>${schedule.day}</td>
                    <td>${formatTime(schedule.start_time)}</td>
                    <td>${formatTime(schedule.end_time)}</td>
                    <td><span class="badge ${schedule.status == 'holding' ? 'bg-success' : 'bg-danger'}">${schedule.status}</span></td>
                    <td><button class="btn btn-primary btn-sm editStatusBtn" data-id="${schedule.id}" data-status="${schedule.status}">Edit</button></td>
                </tr>`;
                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = "<tr><td colspan='6'>No matching records found.</td></tr>";
        }
    })
    .catch(error => console.error('Error:', error));
});

function formatTime(time) {
    let date = new Date("1970-01-01 " + time);
    let hours = date.getHours();
    let minutes = date.getMinutes();
    let ampm = hours >= 12 ? "PM" : "AM";
    hours = hours % 12 || 12;
    minutes = minutes < 10 ? "0" + minutes : minutes;
    return hours + ":" + minutes + " " + ampm;
}
</script>

@endsection --}}

@extends('layouts.master')  
@section('title', 'Lecturer Dashboard')  

@section('content')  
<div class="container">  
    <h2>Course & Class Schedule</h2>  

    <!-- Courses List -->  
    <div class="card mb-4">  
        <div class="card-header" style="background-color: #343a40; color: #fff;">Your Courses</div>  
        <div class="card-body">  
            <ul class="list-group">  
                @foreach($courses as $course)  
                    <li class="list-group-item d-flex justify-content-between align-items-center" >  
                        {{ $course->name }} ({{ $course->course_code }})  
                        <a href="{{ route('lecturer.view.attendance', $course->id) }}" class="btn btn-success btn-sm">View Attendance</a>  
                    </li>  
                @endforeach  
            </ul>  
        </div>  
    </div>  

    <!-- Class Schedule Section -->  
    <div class="card">  
        <div class="card-header" style="background-color: #343a40; color: #fff;">Class Schedules</div>  
        <div class="card-body">  
            <!-- Filters -->  
            <div class="row mb-3">  
                <div class="col-md-4">  
                    <label for="filterCourse" class="form-label">Course</label>  
                    <select id="filterCourse" class="form-select" onchange="this.form.submit()">  
                        <option value="">All Courses</option>  
                        @foreach($courses as $course)  
                            <option value="{{ $course->id }}">{{ $course->name }}</option>  
                        @endforeach  
                    </select>  
                </div>  
                <div class="col-md-3">  
                    <label for="filterDay" class="form-label">Day</label>  
                    <select id="filterDay" class="form-select" onchange="this.form.submit()">  
                        <option value="">All Days</option>  
                        <option value="Monday">Monday</option>  
                        <option value="Tuesday">Tuesday</option>  
                        <option value="Wednesday">Wednesday</option>  
                        <option value="Thursday">Thursday</option>  
                        <option value="Friday">Friday</option>  
                    </select>  
                </div>  
                <div class="col-md-1 d-flex align-items-end">  
                    <button class="btn btn-primary w-100" id="applyFilters">Filter</button>  
                </div>  
            </div>  

            <hr>  

            <!-- Class Schedule Table -->  
            <div class="table-responsive">  
                <table class="table table-striped table-bordered table-hover">  
                    <thead class="table-dark">  
                        <tr>  
                            <th>Course</th>  
                            <th>Day</th>  
                            <th>Start Time</th>  
                            <th>End Time</th>  
                            <th>Lecture Hall</th> 
                            <th>Status</th>  
                            <th>Action</th>  
                        </tr>  
                    </thead>  
                    <tbody id="scheduleTableBody">  
                        @foreach($classSchedules as $schedule)  
                        <tr style="background-color: #000; color: #fff;">  
                            <td>{{ $schedule->course->name }} ({{ $schedule->course->course_code }})</td>  
                            <td>{{ $schedule->day }}</td>  
                            <td>{{ date('h:i A', strtotime($schedule->start_time)) }}</td>  
                            <td>{{ date('h:i A', strtotime($schedule->end_time)) }}</td>  
                            <td>{{ $schedule->lectureHall->name }}</td>  
                            <td>  
                                <span class="badge {{ $schedule->status == 'holding' ? 'bg-success' : 'bg-danger' }}">  
                                    {{ ucfirst($schedule->status) }}  
                                </span>  
                            </td>  
                            <td>  
                                <button class="btn btn-danger btn-sm editStatusBtn" data-id="{{ $schedule->id }}" data-status="{{ $schedule->status }}">  
                                    <i class="fas fa-edit"></i> Edit  
                                </button>  
                            </td>  
                        </tr>  
                        @endforeach  
                    </tbody>  
                </table>  
            </div>  

        </div>  
    </div>  
</div>  


<!-- Edit Schedule Modal -->  
<div class="modal fade" id="editScheduleModal" tabindex="-1" aria-labelledby="editScheduleLabel" aria-hidden="true">  
    <div class="modal-dialog"> 
        <!-- Message Area for Feedback -->  
        <div id="messageArea" class="mb-3" style="display:none;"></div> 
        <div class="modal-content"> 
             
            <div class="modal-header">  
                <h5 class="modal-title" id="editScheduleLabel">Edit Schedule</h5>  
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>  
            </div>  
            <div class="modal-body">  
                <form id="editScheduleForm">  
                    <input type="hidden" name="schedule_id" id="scheduleId">  
                    <div class="mb-3">  
                        <label for="status" class="form-label">Status</label>  
                        <select id="status" name="status" class="form-select">  
                            <option value="holding">Holding</option>  
                            <option value="not holding">Cancelled</option>  
                            <!-- Add other statuses if needed -->  
                        </select>  
                    </div>  
                </form>  
            </div>  
            <div class="modal-footer">  
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>  
                <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>  
            </div>  
        </div>  
    </div>  
</div>  

<!-- JavaScript for Filtering -->  
<script>  

// Function to display a message  
function displayMessage(message, isSuccess) {  
    const messageArea = document.getElementById("messageArea");  
    messageArea.style.display = "block";  
    messageArea.className = isSuccess ? "alert alert-success" : "alert alert-danger";  
    messageArea.innerText = message;  

    // Hide the message after a few seconds  
    setTimeout(() => {  
        messageArea.style.display = "none";  
    }, 3000);  
} 


// // Open edit modal and populate it with current data  
// document.addEventListener("click", function(event) {  
//     if (event.target.classList.contains("editStatusBtn")) {  
//         const scheduleId = event.target.getAttribute("data-id");  
//         const status = event.target.getAttribute("data-status");  
        
//         // Set the schedule ID and selected status in the modal  
//         document.getElementById("scheduleId").value = scheduleId;  
//         document.getElementById("status").value = status;  

//         // Show the modal  
//         var myModal = new bootstrap.Modal(document.getElementById('editScheduleModal'), {});  
//         myModal.show();  
//     }  
// });  

// // Saving the changes  
// document.getElementById("saveChanges").addEventListener("click", function() {  
//     const formData = new FormData(document.getElementById("editScheduleForm"));  

//     fetch("{{ route('lecturer.update.schedule', '') }}/" + formData.get('schedule_id'), {  
//         method: "POST",  
//         headers: {  
//             "X-CSRF-TOKEN": "{{ csrf_token() }}"  
//         },  
//         body: formData  
//     })  
//     .then(response => response.json())  
//     .then(data => {  
//         // Handle success or error as needed  
//         if(data.success) {  
//             // Update the UI accordingly  
//             // Optionally refresh the table or specific row  
//             location.reload(); // Reload the page to show updated data  
//         } else {  
//             // Display error message  
//             alert('Error updating schedule: ' + data.message);  
//         }  
//     })  
//     .catch(error => console.error('Error:', error));  
// });  
// Open edit modal and populate it with current data  
document.addEventListener("click", function(event) {  
    if (event.target.classList.contains("editStatusBtn")) {  
        const scheduleId = event.target.getAttribute("data-id");  
        const status = event.target.getAttribute("data-status");  
        
        // Set the schedule ID and selected status in the modal  
        document.getElementById("scheduleId").value = scheduleId;  
        document.getElementById("status").value = status;  

        // Show the modal  
        var myModal = new bootstrap.Modal(document.getElementById('editScheduleModal'), {});  
        myModal.show();  
    }  
});  

// Saving the changes  
document.getElementById("saveChanges").addEventListener("click", function() {  
    const formData = new FormData(document.getElementById("editScheduleForm"));  
    
    fetch("{{ route('lecturer.update.schedule', '') }}/" + formData.get('schedule_id'), {  
        method: "POST",  
        headers: {  
            "X-CSRF-TOKEN": "{{ csrf_token() }}"  
        },  
        body: formData  
    })  
    .then(response => response.json())  
    .then(data => {  
        if (data.success) {  
            // Update the corresponding row in the table without refreshing  
            const scheduleRow = document.querySelector(`.editStatusBtn[data-id='${formData.get('schedule_id')}']`).closest('tr');  
            const statusCell = scheduleRow.querySelector('td:nth-child(6)'); // Adjust index based on column  
            statusCell.querySelector('span').className = data.newStatus === 'holding' ? 'badge bg-success' : 'badge bg-danger';  
            statusCell.querySelector('span').textContent = data.newStatus.charAt(0).toUpperCase() + data.newStatus.slice(1);  

            // Close the modal  
            var myModal = bootstrap.Modal.getInstance(document.getElementById('editScheduleModal'));  
            myModal.hide();  

            // Display success message  
            displayMessage('Class status updated successfully', true);  
        } else {  
            // Display error message  
            displayMessage('Error updating schedule: ' + data.message, false);  
        }  
    })  
    .catch(error => {  
        console.error('Error:', error);  
        displayMessage('Error updating schedule.', false);  
    });  
});  





















document.getElementById("applyFilters").addEventListener("click", function() {  
    let courseId = document.getElementById("filterCourse").value;  
    let day = document.getElementById("filterDay").value;  

    fetch("{{ route('lecturer.filter.schedule') }}", {  
        method: "POST",  
        headers: {  
            "X-CSRF-TOKEN": "{{ csrf_token() }}",  
            "Content-Type": "application/json"  
        },  
        body: JSON.stringify({ course_id: courseId, day: day })  
    })  
    .then(response => response.json())  
    .then(data => {  
        let tableBody = document.getElementById("scheduleTableBody");  
        tableBody.innerHTML = "";  

        if (data.length > 0) {  
            data.forEach(schedule => {  
                let row = `<tr style="background-color: #000; color: #fff;">  
                    <td>${schedule.course.name} (${schedule.course.course_code})</td>  
                    <td>${schedule.day}</td>  
                    <td>${formatTime(schedule.start_time)}</td>  
                    <td>${formatTime(schedule.end_time)}</td>  
                    <td><span class="badge ${schedule.status == 'holding' ? 'bg-success' : 'bg-danger'}">${schedule.status}</span></td>  
                    <td><button class="btn btn-primary btn-sm editStatusBtn" data-id="${schedule.id}" data-status="${schedule.status}"><i class="fas fa-edit"></i> Edit</button></td>  
                </tr>`;  
                tableBody.innerHTML += row;  
            });  
        } else {  
            tableBody.innerHTML = "<tr><td colspan='6'>No matching records found.</td></tr>";  
        }  
    })  
    .catch(error => console.error('Error:', error));  
});  

function formatTime(time) {  
    let date = new Date("1970-01-01 " + time);  
    let hours = date.getHours();  
    let minutes = date.getMinutes();  
    let ampm = hours >= 12 ? "PM" : "AM";  
    hours = hours % 12 || 12;  
    minutes = minutes < 10 ? "0" + minutes : minutes;  
    return hours + ":" + minutes + " " + ampm;  
}  
</script>  

@endsection  
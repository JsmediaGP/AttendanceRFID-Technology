@extends('layouts.master')  

@section('content')  
<div class="container">  
    <h2>Attendance Records for {{ $course->name }} ({{ $course->course_code }})</h2>  

    <div class="mb-3 d-flex justify-content-between align-items-center">  
        <div>  
            <form method="GET" action="{{ route('lecturer.view.attendance', $course->id) }}">  
                <div class="row">  
                    <div class="col-md-4">  
                        <input type="date" name="date" class="form-control" placeholder="Filter by Date" value="{{ request('date') }}">  
                    </div>  
                    <div class="col-md-4">  
                        <select name="day" class="form-control">  
                            <option value="">Select Day</option>  
                            <option value="Monday" {{ request('day') == 'Monday' ? 'selected' : '' }}>Monday</option>  
                            <option value="Tuesday" {{ request('day') == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>  
                            <option value="Wednesday" {{ request('day') == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>  
                            <option value="Thursday" {{ request('day') == 'Thursday' ? 'selected' : '' }}>Thursday</option>  
                            <option value="Friday" {{ request('day') == 'Friday' ? 'selected' : '' }}>Friday</option>  
                            <option value="Saturday" {{ request('day') == 'Saturday' ? 'selected' : '' }}>Saturday</option>  
                            <option value="Sunday" {{ request('day') == 'Sunday' ? 'selected' : '' }}>Sunday</option>  
                        </select>  
                    </div>  
                    <div class="col-md-4">  
                        <input type="text" name="student_name" class="form-control" placeholder="Filter by Student Name" value="{{ request('student_name') }}">  
                    </div>  
                </div>  
                <div class="mt-3">  
                    <button type="submit" class="btn btn-primary">Filter</button>  
                    <a href="{{ route('lecturer.view.attendance', $course->id) }}" class="btn btn-secondary">Clear Filters</a>  
                </div>  
            </form>  
        </div>  
        <a href="{{ route('lecturer.view.attendance.summary', $course->id) }}" class="btn btn-outline-info btn-sm">View Attendance Summary</a>  
    </div>  

    <div class="card">  
        <div class="card-body">  
            <div class="mb-3">  
                <div class="d-flex justify-content-start">  
                    <a href="{{ route('lecturer.attendance.export.details', $course->id) }}" class="btn btn-success btn-sm me-2">Export Detailed Attendance to CSV</a>  
                    <a href="{{ route('lecturer.attendance.export.summary', $course->id) }}" class="btn btn-info btn-sm">Export Attendance Summary to CSV</a>  
                </div>  
            </div>  
            <table class="table table-bordered table-striped">  
                <thead class="table-dark">  
                    <tr>  
                        <th>Student Name</th>  
                        <th>Class Schedule</th>  
                        <th>Date & Time</th>  
                    </tr>  
                </thead>  
                <tbody>  
                    @forelse($attendanceRecords as $record)  
                        <tr>  
                            <td>{{ $record->student->name }}</td>  
                            <td>{{ $record->classSchedule->day }} - {{ date('h:i A', strtotime($record->classSchedule->start_time)) }} to {{ date('h:i A', strtotime($record->classSchedule->end_time)) }}</td>  
                            <td>{{ \Carbon\Carbon::parse($record->timestamp)->format('Y-m-d H:i A') }}</td>  
                        </tr>  
                    @empty  
                        <tr>  
                            <td colspan="3">No attendance records found.</td>  
                        </tr>  
                    @endforelse  
                </tbody>  
            </table>  

            {{ $attendanceRecords->links() }} <!-- Pagination links -->  
        </div>  
    </div>  
</div>  
@endsection  
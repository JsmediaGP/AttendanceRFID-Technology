@extends('layouts.master')  

@section('content')  
<div class="container">  
    <h2>Welcome, {{ Auth::user()->name }}</h2>  

    <form method="GET" action="{{ route('student.dashboard') }}" class="mb-3">  
        <div class="row g-2"> <!-- Reduced gutter spacing -->  
            <div class="col-auto">  
                <label for="course" class="visually-hidden">Filter by Course:</label>  
                <select name="course_id" id="course" class="form-select" onchange="this.form.submit()">  
                    <option value="">All Courses</option>  
                    @foreach($courses as $course)  
                        <option value="{{ $course->id }}" {{ (request('course_id') == $course->id) ? 'selected' : '' }}>  
                            {{ $course->name }} ({{ $course->course_code }})  
                        </option>  
                    @endforeach  
                </select>  
            </div>  
 
            <div class="col-auto">  
                <label for="date" class="visually-hidden">Filter by Date:</label>  
                <input type="date" name="date" id="date" class="form-control" value="{{ request('date') }}" onchange="this.form.submit()">  
            </div>  
        </div>  
    </form>  

    <h3>Attendance Records</h3>  
    <table class="table table-bordered table-striped">  
        <thead class="table-dark">  
            <tr>  
                <th>Date</th>  
                <th>Course</th>  
            </tr>  
        </thead>  
        <tbody>  
            @forelse($attendanceRecords as $record)  
                <tr>  
                    <td>{{ \Carbon\Carbon::parse($record->date)->format('Y-m-d') }}</td>  
                    <td>{{ $record->classSchedule->course->name }}</td>  
                </tr>  
            @empty  
                <tr>  
                    <td colspan="2">No attendance records found.</td>  
                </tr>  
            @endforelse  
        </tbody>  
    </table>  

    {{ $attendanceRecords->links() }} <!-- Pagination links -->  
</div>  
@endsection  
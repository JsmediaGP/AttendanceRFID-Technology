@extends('layouts.master')  

@section('content')  
<div class="container">  
    <h2>Attendance Summary for {{ $course->name }} ({{ $course->course_code }})</h2>  

    <div class="card">  
        <div class="card-body"> 
              
            <div class="mb-3">  
                <a href="{{ route('lecturer.attendance.export.summary', $course->id) }}" class="btn btn-success">Export Attendance Summary to CSV</a>  
                <a href="{{ route('lecturer.view.attendance', $course->id) }}" class="btn btn-outline-info btn-sm">View Detailed Attendance </a> <br>
            </div> 
             
            
            <table class="table table-bordered table-striped">  
                <thead class="table-dark">  
                    <tr>  
                        <th>Student Name</th>  
                        <th>Attendance Count</th>  
                    </tr>  
                </thead>  
                <tbody>  
                    @forelse($attendanceCounts as $record)  
                        <tr>  
                            <td>{{ $record->student->name }}</td>  
                            <td>{{ $record->count }}</td>  
                        </tr>  
                    @empty  
                        <tr>  
                            <td colspan="2">No attendance records found.</td>  
                        </tr>  
                    @endforelse  
                </tbody>  
            </table>  
        </div>  
    </div>  
</div>  
@endsection  
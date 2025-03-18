@extends('layouts.master')

@section('title', 'Admin Dashboard')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container mt-4">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-card shadow text-center">
                <h4>{{ $totalUsers }}</h4>
                <p>Total Users</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card shadow text-center bg-primary text-white">
                <h4>{{ $totalCourses }}</h4>
                <p>Total Courses</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card shadow text-center bg-success text-white">
                <h4>{{ $totalLectureHalls }}</h4>
                <p>Total Lecture Halls</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card shadow text-center bg-info text-white">
                <h4>{{ $totalSchedules }}</h4>
                <p>Total Class Schedules</p>
            </div>
        </div>
    </div>
</div>

@endsection

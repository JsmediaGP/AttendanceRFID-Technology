{{-- 
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.courses') }}">Courses</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.lecturehalls.index') }}">Lecture Halls</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.schedules.index') }}">Class Schedules</a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="{{ route('logout') }}">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav> --}}



    <nav class="navbar navbar-expand-lg">  
        <div class="container">  
            <a class="navbar-brand" href="{{ route(Auth::user()->role == 'admin' ? 'admin.dashboard' : (Auth::user()->role == 'lecturer' ? 'lecturer.dashboard' : 'student.dashboard')) }}">  
                {{ Auth::user()->role == 'admin' ? 'Admin Dashboard' : (Auth::user()->role == 'lecturer' ? 'Lecturer Dashboard' : 'Student Dashboard') }}  
            </a>  
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">  
                <span class="navbar-toggler-icon"></span>  
            </button>  
            <div class="collapse navbar-collapse" id="navbarNav">  
                <ul class="navbar-nav ms-auto">  
                    @if(Auth::user()->role == 'admin')  
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Users</a></li>  
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.courses') }}">Courses</a></li>  
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.lecturehalls.index') }}">Lecture Halls</a></li>  
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.schedules.index') }}">Class Schedules</a></li>  
                    @elseif(Auth::user()->role == 'lecturer')  
                        <li class="nav-item"><a class="nav-link" href="{{ route('lecturer.dashboard') }}">Dashboard</a></li>  
                    @elseif(Auth::user()->role == 'student')  
                        <li class="nav-item"><a class="nav-link" href="#">Course Registration</a></li>  
                        <li class="nav-item"><a class="nav-link" href="{{ route('student.dashboard') }}">View Attendance</a></li>  
                    @endif  
    
                    <!-- Logout Form -->  
                    <li class="nav-item">  
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">  
                            @csrf  
                        </form>  
                        <a href="#" class="nav-link text-danger"   
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>  
                    </li>  
                </ul>  
            </div>  
        </div>  
    </nav>  
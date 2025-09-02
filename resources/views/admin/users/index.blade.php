{{-- 

@extends('layouts.master')  

@section('title', 'Manage Users')  

@section('content')  

<!-- Success & Error Messages -->  
<div id="messageBox"></div>  

<div class="d-flex justify-content-between align-items-center mb-3">  
    <h3>Users</h3>  
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Onboard New User</button>  
</div>  

<!-- Moved Filter Dropdown Under the Button -->  
<div class="mb-3">  
    <select id="filterRole" class="form-select w-auto" style="width: 200px;">  
        <option value="">All Roles</option>  
        <option value="admin">Admin</option>  
        <option value="lecturer">Lecturer</option>  
        <option value="student">Student</option>  
    </select>  
</div>  

<table class="table table-bordered">  
    <thead class="table-dark">  
        <tr>  
            <th>S/N</th>  
            <th>Name</th>  
            <th>Email</th>  
            <th>Role</th>  
            <th>RFID</th> <!-- Added RFID Column -->  
            <th>Actions</th>  
        </tr>  
    </thead>  
    <tbody id="userTable">  
        @foreach ($users as $user)  
        <tr data-role="{{ $user->role }}" id="row-{{ $user->id }}">  
            <td>{{ $loop->iteration }}</td>  
            <td>{{ $user->name }}</td>  
            <td>{{ $user->email }}</td>  
            <td>{{ ucfirst($user->role) }}</td>  
            <td>{{ $user->rfid ?? 'N/A' }}</td> <!-- Display RFID if available -->  
            <td>  
                <button class="btn btn-warning btn-sm editBtn"  
                    data-id="{{ $user->id }}"  
                    data-name="{{ $user->name }}"  
                    data-email="{{ $user->email }}"  
                    data-role="{{ $user->role }}"  
                    data-rfid="{{ $user->rfid ?? '' }}"  
                    data-bs-toggle="modal"  
                    data-bs-target="#editModal">Edit</button>  
                
                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $user->id }}">Delete</button>  
            </td>  
        </tr>  
        @endforeach  
    </tbody>  
</table>  

<!-- Add User Modal -->  
<div class="modal fade" id="addModal" tabindex="-1">  
    <div class="modal-dialog">  
        <div class="modal-content">  
            <div class="modal-header">  
                <h5 class="modal-title">Onboard New User</h5>  
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>  
            </div>  
            <div class="modal-body">  
                <form id="addForm">  
                    @csrf  
                    <div class="mb-3">  
                        <label class="form-label">Name</label>  
                        <input type="text" name="name" id="addName" class="form-control" required>  
                    </div>  
                    <div class="mb-3">  
                        <label class="form-label">Email</label>  
                        <input type="email" name="email" id="addEmail" class="form-control" required>  
                    </div>  
                    <div class="mb-3">  
                        <label class="form-label">Role</label>  
                        <select name="role" id="addRole" class="form-select" required>  
                            <option value="admin">Admin</option>  
                            <option value="lecturer">Lecturer</option>  
                            <option value="student">Student</option>  
                        </select>  
                    </div>  
                    <div class="mb-3" id="addRfidField" style="display: none;">  
                        <label class="form-label">RFID</label>  
                        <input type="text" name="rfid" id="addRfid" class="form-control">  
                    </div>  
                    <button type="submit" class="btn btn-primary">Add User</button>  
                </form>  
            </div>  
        </div>  
    </div>  
</div>  

<!-- Edit User Modal -->  
<div class="modal fade" id="editModal" tabindex="-1">  
    <div class="modal-dialog">  
        <div class="modal-content">  
            <div class="modal-header">  
                <h5 class="modal-title">Edit User</h5>  
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
                    <div class="mb-3">  
                        <label class="form-label">Email</label>  
                        <input type="email" name="email" id="editEmail" class="form-control" required>  
                    </div>  
                    <div class="mb-3">  
                        <label class="form-label">Role</label>  
                        <select name="role" id="editRole" class="form-select" required>  
                            <option value="admin">Admin</option>  
                            <option value="lecturer">Lecturer</option>  
                            <option value="student">Student</option>  
                        </select>  
                    </div>  
                    <div class="mb-3" id="editRfidField" style="display: none;">  
                        <label class="form-label">RFID</label>  
                        <input type="text" name="rfid" id="editRfid" class="form-control">  
                    </div>  
                    <button type="submit" class="btn btn-warning">Update User</button>  
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
        $('#messageBox').html(`<div class="alert alert-${type}">${message}</div>`);  
    }
      
    
    $('#filterRole').on('change', function() {  
        const selectedRole = $(this).val();  
        $('#userTable tr').each(function() {  
            const role = $(this).data('role');  
            $(this).toggle(!selectedRole || role === selectedRole);  
        });  
    });  

    $('#addRole, #editRole').on('change', function() {  
        if ($(this).val() === 'student') {  
            $('#addRfidField, #editRfidField').show();  
        } else {  
            $('#addRfidField, #editRfidField').hide();  
        }  
    });  
     


    $('#addForm').submit(function(e) {  
        e.preventDefault();  
        $.post("{{ route('users.store') }}", $(this).serialize(), function(response) {  
            if (response.success) {  
                showMessage('success', response.message);  
                location.reload();  
            }  
        }).fail(() => showMessage('danger', 'Failed to add user.'));  
    }); 
    
    

    $('.editBtn').click(function() {  
        $('#editId').val($(this).data('id'));  
        $('#editName').val($(this).data('name'));  
        $('#editEmail').val($(this).data('email'));  
        $('#editRole').val($(this).data('role')).trigger('change');  
        $('#editRfid').val($(this).data('rfid'));  
        $('#editPassword').val(''); // Reset password field to not prepopulate  
    });  

    $('#editForm').submit(function(e) {  
        e.preventDefault();  
        let userId = $('#editId').val();  
        $.ajax({  
            url: '/admin/users/' + userId,  
            type: 'PUT',  
            data: $(this).serialize(),  
            success: function(response) {  
                if (response.success) {  
                    showMessage('success', response.message);  
                    location.reload();  // Reload the page to show updated user  
                }  
            },  
            error: function(xhr) {  
                let response = xhr.responseJSON;  
                if (xhr.status === 422 && response.errors) {  
                    let errorMessage = '';  
                    // Loop through each error and format it  
                    for (let field in response.errors) {  
                        errorMessage += response.errors[field].join(', ') + '<br>';  
                    }  
                    showMessage('danger', errorMessage); // Show the validation errors  
                } else {  
                    showMessage('danger', 'Failed to update user.');  
                }  
            }  
        });  
    });

   

    $('.deleteBtn').click(function() {  
        if (confirm('Are you sure?')) {  
            $.ajax({  
                url: '/admin/users/' + $(this).data('id'),  
                type: 'DELETE',  
                data: { _token: '{{ csrf_token() }}' },  
                success: () => {  
                    showMessage('success', 'User deleted successfully.');  
                    location.reload();  
                },  
                error: () => showMessage('danger', 'Failed to delete user.')  
            });  
        }  
    });

    // Function to check RFID  
    function pollForRFID() {  
        setInterval(function() {  
            fetch('/api/latest-rfid') // Laravel endpoint to get latest RFID  
                .then(response => response.json())  
                .then(data => {  
                    if (data.rfid) {  
                        document.getElementById('addRfid').value = data.rfid; // Populate RFID field  
                    }  
                });  
        }, 1000); // Poll every second  
    }  

    pollForRFID(); // Start polling  
  
    
    
    
});  
</script>  
@endsection   --}}


@extends('layouts.master')

@section('title', 'Manage Users')

@section('content')

<div id="messageBox"></div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Users</h3>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Onboard New User</button>
</div>

<div class="mb-3">
    <select id="filterRole" class="form-select w-auto" style="width: 200px;">
        <option value="">All Roles</option>
        <option value="admin">Admin</option>
        <option value="lecturer">Lecturer</option>
        <option value="student">Student</option>
    </select>
</div>

<table class="table table-bordered">
    <thead class="table-dark">
        <tr>
            <th>S/N</th>
            <th>Profile Picture</th> <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>RFID</th>
            <th>Matric No.</th> <th>Actions</th>
        </tr>
    </thead>
    <tbody id="userTable">
        @foreach ($users as $user)
        <tr data-role="{{ $user->role }}" id="row-{{ $user->id }}">
            <td>{{ $loop->iteration }}</td>
            <td>
                @if($user->profile_picture)
                {{-- <img src="{{ asset('storage/' . str_replace('public/', '', $user->profile_picture)) }}" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;"> --}}
                <img src="{{ $user->profile_picture }}" alt="Profile Picture" style="width: 50px; height: 50px; border-radius: 50%;">
                @else
                N/A
                @endif
            </td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>{{ $user->rfid ?? 'N/A' }}</td>
            <td>{{ $user->matric_number ?? 'N/A' }}</td> <td>
                <button class="btn btn-warning btn-sm editBtn"
                    data-id="{{ $user->id }}"
                    data-name="{{ $user->name }}"
                    data-email="{{ $user->email }}"
                    data-role="{{ $user->role }}"
                    data-rfid="{{ $user->rfid ?? '' }}"
                    data-matric="{{ $user->matric_number ?? '' }}"
                    data-profile-picture="{{ $user->profile_picture ?? '' }}"
                    data-bs-toggle="modal"
                    data-bs-target="#editModal">Edit</button>

                <button class="btn btn-danger btn-sm deleteBtn" data-id="{{ $user->id }}">Delete</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Onboard New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="addName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="addEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="addRole" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="lecturer">Lecturer</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div id="addStudentFields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">RFID</label>
                            <input type="text" name="rfid" id="addRfid" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Matric Number</label>
                            <input type="text" name="matric_number" id="addMatric" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_picture" id="addProfilePicture" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="editRole" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="lecturer">Lecturer</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div id="editStudentFields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">RFID</label>
                            <input type="text" name="rfid" id="editRfid" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Matric Number</label>
                            <input type="text" name="matric_number" id="editMatric" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_picture" id="editProfilePicture" class="form-control">
                            <small class="form-text text-muted">Upload a new picture to replace the old one.</small>
                        </div>
                        <div class="mb-3" id="currentProfilePic">
                            </div>
                    </div>
                    <button type="submit" class="btn btn-warning">Update User</button>
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
            $('#messageBox').html(`<div class="alert alert-${type}">${message}</div>`);
            setTimeout(() => $('#messageBox').empty(), 5000); // Clear after 5 seconds
        }

        // --- Conditional Field Display Logic ---
        function toggleStudentFields(role) {
            const isStudent = role === 'student';
            $('#addStudentFields, #editStudentFields').toggle(isStudent);
            
            // Set 'required' attribute for student-specific fields
            $('#addRfid, #addMatric, #addProfilePicture').prop('required', isStudent);
            // For edit modal, we'll handle this in the editBtn click event
            $('#editRfid, #editMatric').prop('required', isStudent);
            // For profile picture on edit, it's not required on update
        }

        $('#addRole').on('change', function() {
            toggleStudentFields($(this).val());
        });

        $('#editRole').on('change', function() {
            toggleStudentFields($(this).val());
        });

        // --- Form Submission Logic ---
        $('#addForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            url: "{{ route('users.store') }}",
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                // Check if the server-side operation was successful
                if (response.success) {
                    // Display the success message received from the server
                    showMessage('success', response.message);

                    // Wait a moment (1.5 seconds) before reloading the page
                    // This gives the user time to see the success message
                    setTimeout(function() {
                        location.reload();
                    }, 1500); 
                } else {
                    // This handles cases where the server returns a non-error status
                    // but indicates a logical failure (e.g., an unhandled case)
                    showMessage('danger', response.message);
                }
            },
            error: function(xhr) {
                const errors = xhr.responseJSON.errors;
                let errorMessage = '';

                if (errors) {
                    // Display validation errors from Laravel
                    for (const field in errors) {
                        errorMessage += errors[field].join(', ') + '<br>';
                    }
                } else {
                    // A generic message for other types of errors
                    errorMessage = 'Failed to add user. An unexpected error occurred.';
                }
                showMessage('danger', errorMessage);
            }
        });
    });       
        
    

       

        $('#editForm').submit(function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('_method', 'PUT'); // Required for PUT request with FormData
            const userId = $('#editId').val();

            $.ajax({
                url: `/admin/users/${userId}`,
                type: 'POST', // Use POST for FormData with method override
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    showMessage('success', response.message);
                    location.reload();
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    if (errors) {
                        for (const field in errors) {
                            errorMessage += errors[field].join(', ') + '<br>';
                        }
                    } else {
                        errorMessage = 'Failed to update user. Check your inputs.';
                    }
                    showMessage('danger', errorMessage);
                }
            });
        });

        // --- Dynamic Content & Actions ---
        $('.editBtn').click(function() {
            const userData = $(this).data();
            $('#editId').val(userData.id);
            $('#editName').val(userData.name);
            $('#editEmail').val(userData.email);
            $('#editRole').val(userData.role).trigger('change');
            $('#editRfid').val(userData.rfid);
            $('#editMatric').val(userData.matric);

            // Display current profile picture if it exists
            if (userData.profilePicture) {
                const imageUrl = `{{ asset('storage') }}/${userData.profilePicture.replace('public/', '')}`;
                $('#currentProfilePic').html(`
                    <label class="form-label">Current Picture</label><br>
                    <img src="${imageUrl}" alt="Current Profile Picture" style="max-width: 100px; max-height: 100px;">
                `);
            } else {
                $('#currentProfilePic').empty();
            }
        });

        $('.deleteBtn').click(function() {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                $.ajax({
                    url: `/admin/users/${$(this).data('id')}`,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: () => {
                        showMessage('success', 'User deleted successfully.');
                        location.reload();
                    },
                    error: () => showMessage('danger', 'Failed to delete user.')
                });
            }
        });

        // --- RFID Polling (if needed) ---
        function pollForRFID() {
            // Your existing polling logic...
        }
        // Uncomment below if you have the RFID hardware polling endpoint
        // pollForRFID();
    });
</script>
@endsection


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
@endsection  
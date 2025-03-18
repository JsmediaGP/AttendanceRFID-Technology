{{-- <!-- resources/views/register.blade.php -->  
<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Registration Form</title>  
    <script>  
        function handleRFIDScan(rfidCode) {  
            fetch('http://192.168.200.52:8000/api/rfid-scan', {  
                method: 'POST',  
                headers: {  
                    'Content-Type': 'application/json',  
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for Laravel  
                },  
                body: JSON.stringify({ rfid: rfidCode, hall_name: 'CLT' })  
            })  
            .then(response => response.json())  
            .then(data => {  
                if (!data.success) {  
                    document.getElementById('rfid').value = data.rfid; // Populate RFID field  
                    alert(data.message); // Notify the user they need to register  
                } else {  
                    alert('Attendance marked successfully.');  
                }  
            })  
            .catch(error => console.error('Error:', error));  
        }  

        // Polling mechanism to check for newly scanned RFID  
        setInterval(function() {  
            // You would need an endpoint to get the latest scanned RFID  
            // For example, here’s a placeholder function to get the latest scan  
            fetch('http://192.168.200.52:8000/api/latest-rfid')  // You would set this up on your Laravel backend  
                .then(response => response.json())  
                .then(data => {  
                    if (data.rfid) {  
                        handleRFIDScan(data.rfid); // Call handleRFIDScan whenever a new RFID is available  
                    }  
                });  
        }, 1000);  // Check every 1000 milliseconds (1 second)  
    </script>  
</head>  
<body>  
    <h1>Register</h1>  
    <form id="registrationForm" action="{{ route('register.submit') }}" method="POST">  
        @csrf  
        <label for="name">Name:</label>  
        <input type="text" name="name" required>  

        <label for="email">Email:</label>  
        <input type="email" name="email" required>  

        <label for="rfid">RFID:</label>  
        <input type="text" name="rfid" id="rfid" required>  

        <label for="hall_name">Hall Name:</label>  
        <input type="text" name="hall_name" required>  

        <button type="submit">Register</button>  
    </form>  
</body>  
</html>   --}}
<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Registration</title>  
    <script>  
        function pollForRFID() {  
            // Poll for the latest RFID  
            setInterval(function() {  
                fetch('/api/latest-rfid') // Hit the Laravel endpoint  
                    .then(response => response.json())  
                    .then(data => {  
                        if (data.rfid) {  
                            document.getElementById('rfid').value = data.rfid; // Update the RFID field  
                        }  
                    });  
            }, 1000); // Poll every second  
        }  

        window.onload = function() {  
            pollForRFID(); // Start polling when the page loads  
        };  
    </script>  
</head>  
<body>  
    <h1>Register</h1>  
    <form id="registrationForm" action="{{ route('register.submit') }}" method="POST">  
        @csrf  
        <label for="name">Name:</label>  
        <input type="text" name="name" required>  

        <label for="email">Email:</label>  
        <input type="email" name="email" required>  

        <label for="rfid">RFID:</label>  
        <input type="text" name="rfid" id="rfid" required>  

        <label for="hall_name">Hall Name:</label>  
        <input type="text" name="hall_name" required>  

        <button type="submit">Register</button>  
    </form>  

    <!-- Optionally, display messages to the user -->  
    <div id="message"></div>  

    <script>  
        function pollForRFID() {  
            // Poll for the latest RFID every second  
            setInterval(function() {  
                fetch('/api/latest-rfid') // Fetch the latest RFID from the Laravel backend  
                    .then(response => response.json())  
                    .then(data => {  
                        if (data.rfid) {  
                            document.getElementById('rfid').value = data.rfid; // Populate the RFID field  
                        }  
                    })  
                    .catch(error => {  
                        console.error('Error fetching RFID:', error);  
                    });  
            }, 1000); // Set interval to 1000 milliseconds (1 second)  
        }  

        window.onload = function() {  
            pollForRFID(); // Start polling for the RFID when the window loads  
        };  
    </script>  
</body>  
</html> 

<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Login</title>  
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">  
    <style>  
        body {  
            background-image: url('{{ asset('images/bg3.jpeg') }}'); /* Background image */  
            background-size: cover;  
            background-position: center;  
            height: 100vh;  
            display: flex;  
            justify-content: center;  
            align-items: center;  
            margin: 0;  
            font-family: Arial, sans-serif;  
            background-color: #f0f0f0; /* Fallback color */  
            position: relative;  
        }  

        /* Gradient overlay for background */  
        .gradient-overlay {  
            position: absolute;  
            top: 0;  
            left: 0;  
            right: 0;  
            bottom: 0;  
            background: rgba(255, 255, 255, 0.8); /* White gradient */  
        }  

        .login-container {  
            position: relative; /* Necessary for absolute positioning of the overlay */  
            z-index: 1; /* Ensure container appears above the gradient */  
            width: 90%;  
            max-width: 800px;  
            border-radius: 8px;  
            display: flex;  
            overflow: hidden; /* Ensure elements don't overflow the card */  
            background: white; /* Background for the main card */  
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Slight box shadow */  
        }  

        .logo-section {  
            flex: 1;  
            background: url('{{ asset('images/ui.png') }}') no-repeat center center; /* Logo image */  
            background-size: contain; /* Adjust logo to fit */  
            min-height: 400px; /* Fixed height for logo section */  
            display: flex;  
            justify-content: center;  
            align-items: center;  
            text-align: center;  
            color: white; /* Optional: set a color for the text overlay */  
        }  

        .form-wrapper {  
            flex: 1;  
            display: flex;  
            justify-content: center;  
            align-items: center;  
            padding: 20px; /* Padding around the form card */  
        }  

        .form-card {  
            width: 100%; /* Full width of the form section */  
            padding: 20px;  
            border-radius: 8px;  
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);  
            background: white; /* White background for form card */  
        }  

        .form-card h2 {  
            margin-bottom: 20px;  
            color: #333; /* Darker text for better readability */  
        }  

        .form-card label {  
            margin: 10px 0 5px;  
            font-weight: bold;  
        }  

        .form-card input {  
            width: 100%;  
            padding: 10px;  
            margin: 10px 0;  
            border: 1px solid #ccc;  
            border-radius: 5px;  
            font-size: 14px;  
        }  

        .form-card button {  
            padding: 10px;  
            background-color: #007BFF; /* Bootstrap primary color */  
            color: white;  
            border: none;  
            border-radius: 5px;  
            font-size: 16px;  
            cursor: pointer;  
            margin-top: 10px;  
            transition: background-color 0.3s;  
        }  

        .form-card button:hover {  
            background-color: #0056b3; /* Darken on hover */  
        }  

        .error-message {  
            color: red;  
            margin-bottom: 15px;  
        }  

        /* Media Query for Mobile Devices */  
        @media (max-width: 768px) {  
            .login-container {  
                flex-direction: column; /* Stack logo and form on smaller screens */  
            }  

            .logo-section {  
                min-height: 200px; /* Adjust height for mobile */  
            }  

            .form-wrapper {  
                padding: 20px;  
            }  

            .form-card {  
                padding: 15px; /* Less padding on mobile */  
            }  
        }  
    </style>  
</head>  
<body>  
    <div class="gradient-overlay"></div> <!-- Overlay for the background -->  
    <div class="login-container">  
        <div class="logo-section">  
            {{-- <h1>Your School Name</h1> <!-- Replace with your actual school name -->   --}}
        </div>  
        <div class="form-wrapper">  
            <div class="form-card">  
                @if(session('error'))  
                    <p class="error-message">{{ session('error') }}</p>  
                @endif  
                <h2>Login</h2>  
                <form action="{{ route('login') }}" method="POST">  
                    @csrf  
                    <label for="email">Email:</label>  
                    <input type="email" name="email" required id="email">  
                    <label for="password">Password:</label>  
                    <input type="password" name="password" required id="password">  
                    <button type="submit">Login</button>  
                </form>  
            </div>  
        </div>  
    </div>  
</body>  
</html>  
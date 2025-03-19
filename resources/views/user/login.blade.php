<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
       <!-- Include updated FontAwesome CDN -->
       <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">
<div class="bg-white shadow-md rounded px-8 pt-6 pb-8 w-full max-w-md">
    <form id="loginForm" method="post" action="{{route('login_insert')}}">
        @csrf
        <div class="flex justify-center mb-6">
            <img src="{{url('images/kalyan-mahadev.jpg')}}" alt="Logo" class="rounded-full w-24 h-24">
        </div>

        <h4 class="text-center text-xl font-semibold mb-6">Log in</h4>

        <div class="relative mb-4">
            <label for="number" class="block text-lg font-medium text-gray-700">Mobile Number </label>
            <input type="tel" name="number" id="mobile" placeholder="Enter Mobile Number" value="8003526761"
                class="block w-full mt-1 text-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-12"
                required>
            <div class="absolute right-3 top-[38px] flex items-center pr-3 pointer-events-none">
                <i class="fas fa-phone-alt text-gray-400"></i>
            </div>
        </div>

        <div class="relative mb-4">
            <label for="password" class="block text-lg font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter Password" value="12345678"
                class="block w-full mt-1 text-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-12"
                required>
            <div class="absolute right-3 top-[38px] flex items-center pr-3 cursor-pointer" id="password-toggle">
                <i class="fas fa-eye text-gray-400" id="password-toggle-icon"></i>
            </div>
        </div>

        <div class="flex items-center justify-between mb-6">
            <a href="#" class="text-sm text-blue-500 hover:text-blue-700" onclick="run();">Forget Password?</a>
        </div>

        <div class="mb-4">
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Login</button>
        </div>

        <div class="text-center">
            <a href="{{route('register')}}" class="text-sm text-blue-500 hover:text-blue-700">Create New Account? Sign Up</a>
        </div>
    </form>
</div>

<script>
    // Mobile number validation
    document.getElementById('mobile').addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, ''); // Allow only numeric input
        if (this.value.length > 10) {
            this.value = this.value.slice(0, 10); // Restrict to 10 digits
        }
    });

    // Form validation
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        let isValid = true;

        // Mobile Number Validation
        const mobileInput = document.getElementById('mobile');
        if (mobileInput.value.length !== 10) {
            showError('Invalid Mobile Number', 'Please enter a valid mobile number (10 digits).');
            isValid = false;
        }

        // Password Validation
        const passwordInput = document.getElementById('password');
        if (passwordInput.value.length < 6) {
            showError('Invalid Password', 'Password must be at least 6 characters long.');
            isValid = false;
        }

        // Prevent form submission if invalid
        if (!isValid) {
            e.preventDefault();
        }
    });

    // Forgot password handler
    function run() {
        showError('Forgot Password?', 'Please contact your admin for assistance.');
    }

    // Show error alert
    function showError(title, message) {
        Swal.fire({
            icon: 'error',
            title: `<h2 class="text-lg font-bold text-red-600">${title}</h2>`,
            html: `<p class="text-gray-700">${message}</p>`,
            background: '#fef2f2',
            color: '#b91c1c',
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: `<span class="text-white font-semibold">Got it</span>`,
            confirmButtonColor: '#ef4444',
        });
    }

    // Show success alert
    function showSuccess(title, message) {
        Swal.fire({
            icon: 'success',
            title: `<h2 class="text-lg font-bold text-green-600">${title}</h2>`,
            html: `<p class="text-gray-700">${message}</p>`,
            background: '#f0fdf4',
            color: '#15803d',
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: `<span class="text-white font-semibold">OK</span>`,
            confirmButtonColor: '#22c55e',
        });
    }

    // Display Laravel session messages
    @if(session('error'))
    document.addEventListener('DOMContentLoaded', function () {
        showError('Oops!', '{{ session('error') }}');
    });
    @endif

    @if(session('success'))
    document.addEventListener('DOMContentLoaded', function () {
        showSuccess('Success!', '{{ session('success') }}');
    });
    @endif

    // Toggle password visibility
    const passwordToggleIcon = document.getElementById('password-toggle-icon');
    const passwordInput = document.getElementById('password');

    passwordToggleIcon.addEventListener('click', function () {
        const isPasswordVisible = passwordInput.type === 'text';
        passwordInput.type = isPasswordVisible ? 'password' : 'text';
        passwordToggleIcon.classList.toggle('fa-eye-slash');
        passwordToggleIcon.classList.toggle('fa-eye');
    });
</script>

</body>
</html>

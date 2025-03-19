<!DOCTYPE html>
<html lang="en">
<head>
    <title>Registration</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include updated FontAwesome CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

<div class="w-full max-w-md p-6 bg-white rounded-lg shadow-md">
    <div class="text-center">
        <img src="{{url('images/kalyan-mahadev.jpg')}}" alt="Logo" class="w-20 h-20 mx-auto mb-4 rounded-full">
        <h2 class="text-xl font-semibold text-gray-800">Registration</h2>
    </div>
    
    <form id="registrationForm" action="{{route('register_insert')}}" method="post" class="space-y-4">
        @csrf

        <!-- Name Input -->
        <div class="relative">
            <label for="name" class="block text-lg font-medium text-gray-700">Name</label>
            <input type="text" name="name" id="name" placeholder="Enter Name"
                class="block w-full mt-1 text-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pl-10"
                required>
            <!-- Icon inside input field -->
            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500">
                <i class="fas fa-user"></i> <!-- User icon -->
            </span>
        </div>
        

        <!-- Mobile Number Input -->
        <div class="relative">
            <label for="mobile" class="block text-lg font-medium text-gray-700">Mobile Number</label>
            <input type="tel" name="mobile" id="mobile" placeholder="Enter Mobile Number"
                class="block w-full mt-1 text-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-12"
                required>
            <!-- FontAwesome Phone Icon -->
            <div class="absolute right-3 top-[38px] flex items-center pr-3 pointer-events-none">
                <i class="fas fa-phone-alt text-gray-400"></i>
            </div>
        </div>

        <!-- Password Input -->
        <div class="relative">
            <label for="password" class="block text-lg font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter Password"
                class="block w-full mt-1 text-lg border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pr-12"
                required>
            <!-- FontAwesome Eye Icon for Show/Hide Password -->
            <div class="absolute right-3 top-[38px] flex items-center pr-3 cursor-pointer" id="password-toggle">
                <i class="fas fa-eye text-gray-400" id="password-toggle-icon"></i>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-center">
            <button type="submit"
                class="w-full px-4 py-2 text-lg text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:ring-2 focus:ring-blue-300">
                Submit
            </button>
        </div>
    </form>

    <!-- Already Have Account -->
    <div class="mt-4 text-center">
        <a href="{{url('/')}}" class="text-lg text-blue-500 hover:text-blue-700">Already have an account? Login Here</a>
    </div>
</div>

<script>
    /**
     * Toggle password visibility
     */
    const passwordInput = document.getElementById('password');
    const passwordToggleIcon = document.getElementById('password-toggle-icon');

    passwordToggleIcon.addEventListener('click', function () {
        const isPasswordVisible = passwordInput.type === 'text';
        passwordInput.type = isPasswordVisible ? 'password' : 'text';
        passwordToggleIcon.classList.toggle('fa-eye-slash');
        passwordToggleIcon.classList.toggle('fa-eye');
    });
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymManagerOS - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-2xl font-bold text-center mb-6 text-blue-600">GymManagerOS</h1>
        <p class="text-gray-600 text-center mb-6">Sign in to your account</p>
        
        <form id="loginForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="demo@gymmanageros.com" 
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" value="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2 border" required>
            </div>
            
            <button type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                Sign In
            </button>
        </form>
        
        <div id="result" class="mt-4 p-4 rounded hidden"></div>
        
        <div class="mt-4 text-center text-sm text-gray-500">
            Demo: demo@gymmanageros.com / password
        </div>
    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const result = document.getElementById('result');
        
        result.className = 'mt-4 p-4 rounded bg-yellow-100';
        result.textContent = 'Logging in...';
        result.classList.remove('hidden');
        
        try {
            const response = await fetch('/login', {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            });
            
            if (response.ok || response.redirected) {
                result.className = 'mt-4 p-4 rounded bg-green-100';
                result.textContent = 'Login successful! Redirecting...';
                window.location.href = '/dashboard';
            } else {
                result.className = 'mt-4 p-4 rounded bg-red-100';
                result.textContent = 'Login failed. Please try again.';
            }
        } catch (err) {
            result.className = 'mt-4 p-4 rounded bg-red-100';
            result.textContent = 'Error: ' + err.message;
        }
    });
    </script>
</body>
</html>

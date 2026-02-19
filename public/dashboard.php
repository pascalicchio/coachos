<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GymManagerOS - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">GymManagerOS</h1>
            <a href="/logout" class="text-white hover:text-gray-200">Logout</a>
        </div>
    </nav>
    
    <div class="container mx-auto p-8">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Members</h3>
                <p class="text-3xl font-bold text-blue-600 mt-2">156</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Revenue</h3>
                <p class="text-3xl font-bold text-green-600 mt-2">$12,450</p>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700">Classes Today</h3>
                <p class="text-3xl font-bold text-purple-600 mt-2">8</p>
            </div>
        </div>
        
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
            <div class="flex gap-4">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Add Member</button>
                <button class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Schedule Class</button>
                <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700">Send Message</button>
            </div>
        </div>
    </div>
</body>
</html>

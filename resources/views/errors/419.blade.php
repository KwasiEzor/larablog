<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Expired - 419</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-yellow-50 to-orange-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Error Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <!-- Icon -->
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl font-bold text-gray-800 mb-2">419</h1>

            <!-- Error Message -->
            <h2 class="text-xl font-semibold text-gray-700 mb-3">Page Expired</h2>
            <p class="text-gray-500 mb-8 leading-relaxed">
                The page has expired due to inactivity. Please refresh the page and try again.
            </p>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <button onclick="window.location.reload()"
                    class="block w-full bg-yellow-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-yellow-700 transition-colors duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Refresh Page
                </button>

                <a href="/"
                    class="block w-full bg-gray-100 text-gray-700 font-medium py-3 px-6 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    Go Back Home
                </a>
            </div>

            <!-- Helpful Links -->
            <div class="mt-8 pt-6 border-t border-gray-100">
                <p class="text-sm text-gray-500 mb-3">Need help?</p>
                <div class="flex justify-center space-x-4 text-sm">
                    <a href="/contact" class="text-blue-600 hover:text-blue-800 font-medium">Contact Support</a>
                    <span class="text-gray-300">•</span>
                    <a href="/login" class="text-blue-600 hover:text-blue-800 font-medium">Login</a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-sm text-gray-500">
                © {{ date('Y') }} Larablog. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>

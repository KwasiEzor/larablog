<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $exception->getStatusCode() ?? 'Error' }} - {{ config('app.name', 'Laravel') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-blue-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Error Card -->
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center">
            <!-- Icon -->
            <div class="mb-6">
                <div class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
            </div>

            <!-- Error Code -->
            <h1 class="text-6xl font-bold text-gray-800 mb-2">{{ $exception->getStatusCode() ?? 'Error' }}</h1>

            <!-- Error Message -->
            <h2 class="text-xl font-semibold text-gray-700 mb-3">
                @switch($exception->getStatusCode())
                @case(401)
                Unauthorized
                @break
                @case(402)
                Payment Required
                @break
                @case(405)
                Method Not Allowed
                @break
                @case(408)
                Request Timeout
                @break
                @case(429)
                Too Many Requests
                @break
                @case(503)
                Service Unavailable
                @break
                @default
                Something Went Wrong
                @endswitch
            </h2>
            <p class="text-gray-500 mb-8 leading-relaxed">
                @switch($exception->getStatusCode())
                @case(401)
                Please log in to access this page.
                @break
                @case(402)
                Payment is required to access this feature.
                @break
                @case(405)
                The requested method is not allowed for this resource.
                @break
                @case(408)
                The request timed out. Please try again.
                @break
                @case(429)
                Too many requests. Please wait a moment and try again.
                @break
                @case(503)
                The service is temporarily unavailable. Please try again later.
                @break
                @default
                An unexpected error occurred. Please try again or contact support.
                @endswitch
            </p>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <a href="/"
                    class="block w-full bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Go Back Home
                </a>

                <a href="/posts"
                    class="block w-full bg-gray-100 text-gray-700 font-medium py-3 px-6 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                    Browse Blog Posts
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
                © {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>

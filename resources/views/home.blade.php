<x-app-layout>
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-white">
        <div class="flex flex-col items-center gap-12 px-4 py-20 mx-auto max-w-7xl md:flex-row">
            <div class="flex-1 animate-fade-in-up" x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)"
                x-show="show" x-transition>
                <h1 class="mb-6 text-4xl font-extrabold leading-tight text-gray-900 md:text-5xl">
                    Build Modern <span class="text-indigo-600">Blogs</span> with <br> <span
                        class="text-indigo-600 underline underline-offset-4">Larablog</span>
                </h1>
                <p class="max-w-xl mb-8 text-lg text-gray-600">
                    A clean, beautiful Laravel blog platform with a modern UI, built for creators and readers alike.
                    Fast, responsive, and easy to use.
                </p>
                <a wire:navigate href="{{ route('register') }}"
                    class="inline-block px-8 py-3 font-semibold text-white transition bg-indigo-600 rounded-lg shadow hover:bg-indigo-700">Get
                    Started</a>
            </div>
            <div class="flex justify-center flex-1 animate-fade-in" x-data="{ show: false }"
                x-init="setTimeout(() => show = true, 400)" x-show="show" x-transition>
                <img src="{{ asset('/images/blog-1.jpg') }}" alt="Hero"
                    class="object-cover w-full max-w-md shadow-lg rounded-xl">
            </div>
        </div>
        <div class="absolute top-0 left-0 w-full h-full rounded-full opacity-50 bg-indigo-50 -z-10 blur-2xl animate-pulse"
            style="transform: translate(-50%,-50%)"></div>
    </section>

    <!-- Featured and Popular Posts Section -->
    <section class="py-16 bg-white">
        <div class="px-4 mx-auto max-w-7xl">
            <livewire:featured-posts />
            <livewire:popular-posts />
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-6xl px-4 mx-auto">
            <div class="mb-16 text-center">
                <h2 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">Features</h2>
                <p class="max-w-2xl mx-auto text-gray-600">Everything you need to run a modern blog, with a focus on
                    speed, usability, and design.</p>
            </div>
            <div class="grid gap-10 md:grid-cols-3">
                <div class="p-4 transition bg-white shadow rounded-xl hover:shadow-lg animate-fade-in-up"
                    x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)" x-show="show" x-transition>
                    <img src="{{ asset('/images/bring-smile.jpg') }}" alt="Feature 1"
                        class="object-cover mx-auto mb-4 w-fit h-fit rounded-xl">
                    <h3 class="mb-2 text-xl font-semibold">Easy Publishing</h3>
                    <p class="text-gray-600">Write, edit, and publish posts with a beautiful, distraction-free editor.
                    </p>
                </div>
                <div class="p-4 transition bg-white shadow rounded-xl hover:shadow-lg animate-fade-in-up"
                    x-data="{ show: false }" x-init="setTimeout(() => show = true, 400)" x-show="show" x-transition>
                    <img src="{{ asset('/images/blog-2.jpg') }}" alt="Feature 2"
                        class="mx-auto mb-4 w-fit h-fit rounded-xl">
                    <h3 class="mb-2 text-xl font-semibold">Modern Design</h3>
                    <p class="text-gray-600">A clean, responsive interface that looks great on any device.</p>
                </div>
                <div class="p-4 transition bg-white shadow rounded-xl hover:shadow-lg animate-fade-in-up"
                    x-data="{ show: false }" x-init="setTimeout(() => show = true, 600)" x-show="show" x-transition>
                    <img src="{{ asset('/images/computer.jpg') }}" alt="Feature 3"
                        class="object-cover mx-auto mb-4 w-fit h-fit rounded-xl min-h-[12rem]">
                    <h3 class="mb-2 text-xl font-semibold">Community Features</h3>
                    <p class="text-gray-600">Comments, likes, and more to engage your audience and grow your community.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About/How It Works Section -->
    <section class="py-20 bg-white">
        <div class="flex flex-col items-center max-w-6xl gap-12 px-4 mx-auto md:flex-row">
            <div class="flex-1 animate-fade-in-up" x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)"
                x-show="show" x-transition>
                <img src="{{ asset('/images/blog-3.jpg') }}" alt="About"
                    class="object-cover w-full max-w-md shadow-lg rounded-xl">
            </div>
            <div class="flex-1 animate-fade-in-up" x-data="{ show: false }" x-init="setTimeout(() => show = true, 400)"
                x-show="show" x-transition>
                <h2 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">How It Works</h2>
                <p class="mb-6 text-gray-600">Start your blog in minutes. Register, create your first post, and share
                    your thoughts with the world. Our platform handles the rest, from user management to comments and
                    analytics.</p>
                <ul class="space-y-3">
                    <li class="flex items-center"><span class="w-3 h-3 mr-3 bg-indigo-500 rounded-full"></span> Register
                        and set up your profile</li>
                    <li class="flex items-center"><span class="w-3 h-3 mr-3 bg-indigo-500 rounded-full"></span> Create
                        and publish posts</li>
                    <li class="flex items-center"><span class="w-3 h-3 mr-3 bg-indigo-500 rounded-full"></span> Engage
                        with your audience</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Testimonials/Stats Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-6xl px-4 mx-auto">
            <div class="mb-16 text-center">
                <h2 class="mb-4 text-3xl font-bold text-gray-900 md:text-4xl">What Our Users Say</h2>
                <p class="max-w-2xl mx-auto text-gray-600">Loved by creators and readers worldwide.</p>
            </div>
            <div class="grid gap-10 md:grid-cols-3">
                <div class="p-8 bg-white shadow rounded-xl animate-fade-in-up" x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 200)" x-show="show" x-transition>
                    <p class="mb-4 text-gray-700">“The best blogging platform I’ve used. Clean, fast, and easy to use!”
                    </p>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('/images/profile-2.jpg') }}" alt="User 1"
                            class="object-cover w-10 h-10 rounded-full">
                        <span class="font-semibold">Jane Doe</span>
                    </div>
                </div>
                <div class="p-8 bg-white shadow rounded-xl animate-fade-in-up" x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 400)" x-show="show" x-transition>
                    <p class="mb-4 text-gray-700">“Beautiful design and great features. My readers love it!”</p>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('/images/profile-3.jpg') }}" alt="User 2"
                            class="object-cover w-10 h-10 rounded-full">
                        <span class="font-semibold">John Smith</span>
                    </div>
                </div>
                <div class="p-8 bg-white shadow rounded-xl animate-fade-in-up" x-data="{ show: false }"
                    x-init="setTimeout(() => show = true, 600)" x-show="show" x-transition>
                    <p class="mb-4 text-gray-700">“I started my blog in minutes. Highly recommended!”</p>
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('/images/profile-4.jpg') }}" alt="User 3"
                            class="object-cover w-10 h-10 rounded-full">
                        <span class="font-semibold">Alex Lee</span>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-8 mt-16 text-center md:grid-cols-3">
                <div>
                    <div class="mb-2 text-4xl font-bold text-indigo-600">10K+</div>
                    <div class="text-gray-600">Active Users</div>
                </div>
                <div>
                    <div class="mb-2 text-4xl font-bold text-indigo-600">500K+</div>
                    <div class="text-gray-600">Posts Published</div>
                </div>
                <div>
                    <div class="mb-2 text-4xl font-bold text-indigo-600">99.9%</div>
                    <div class="text-gray-600">Uptime</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-20 bg-indigo-600">
        <div class="max-w-4xl px-4 mx-auto text-center">
            <h2 class="mb-4 text-3xl font-bold text-white md:text-4xl">Ready to start your blog?</h2>
            <p class="mb-8 text-indigo-100">Join thousands of creators using our platform to share their stories.</p>
            <a wire:navigate href="{{ route('register') }}"
                class="inline-block px-8 py-3 font-semibold text-indigo-600 transition bg-white rounded-lg shadow hover:bg-indigo-50">Sign
                Up Free</a>
        </div>
    </section>

    <!-- Animations (Tailwind + Alpine.js) -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .animate-fade-in-up {
            opacity: 0;
            transform: translateY(40px);
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1), transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [x-show="show"].animate-fade-in-up {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }

        .animate-fade-in {
            opacity: 0;
            transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        [x-show="show"].animate-fade-in {
            opacity: 1 !important;
        }
    </style>
</x-app-layout>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .card-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
        .header-bg {
            background: #1f2937;
        }
        .footer-bg {
            background: #111827;
        }
        .hero-bg {
            background: linear-gradient(135deg, #ff7eb3 0%, #ff758c 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="header-bg p-6 text-white shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-4xl font-bold">E-Learn</h1>
            <nav class="space-x-6">
                <a href="#" class="hover:underline">Home</a>
                <a href="#" class="hover:underline">Courses</a>
                <a href="#" class="hover:underline">About</a>
                <a href="#" class="hover:underline">Contact</a>
                <a href="#" class="bg-gray-900 text-white px-4 py-2 rounded-full hover:bg-pink-700">Login</a>
                <a href="#" class="bg-pink-600 px-4 py-2 rounded-full hover:bg-pink-700">Sign Up</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-bg text-center py-20">
        <h2 class="text-6xl font-extrabold text-white">Transform Your Learning Experience</h2>
        <p class="text-gray-200 mt-4 max-w-2xl mx-auto text-lg">Discover thousands of courses from top instructors to help you achieve your goals. Join our community and start learning today!</p>
        <button class="mt-8 bg-white text-pink-600 px-8 py-4 rounded-full shadow-lg hover:bg-pink-100">Get Started</button>
    </section>

    <!-- Course Cards Section -->
    <section class="container mx-auto p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-lg shadow-md card-hover">
            <h3 class="text-2xl font-bold mt-4">Web Development</h3>
            <p class="text-gray-600 mt-2">Learn to build modern websites and applications using HTML, CSS, and JavaScript.</p>
            <button class="mt-4 bg-gray-900 text-white px-6 py-3 rounded-full hover:bg-pink-700">Enroll Now</button>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-lg shadow-md card-hover">
            <h3 class="text-2xl font-bold mt-4">Digital Marketing</h3>
            <p class="text-gray-600 mt-2">Master the art of digital marketing and grow your online presence.</p>
            <button class="mt-4 bg-gray-900 text-white px-6 py-3 rounded-full hover:bg-pink-700">Enroll Now</button>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-lg shadow-md card-hover">
            <h3 class="text-2xl font-bold mt-4">Python Programming</h3>
            <p class="text-gray-600 mt-2">Learn Python from scratch and build powerful applications.</p>
            <button class="mt-4 bg-gray-900 text-white px-6 py-3 rounded-full hover:bg-pink-700">Enroll Now</button>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-lg shadow-md card-hover">
            <h3 class="text-2xl font-bold mt-4">Graphic Design</h3>
            <p class="text-gray-600 mt-2">Create stunning visuals and improve your design skills.</p>
            <button class="mt-4 bg-gray-900 text-white px-6 py-3 rounded-full hover:bg-pink-700">Enroll Now</button>
        </div>

        <!-- Card 5 -->
        <div class="bg-white p-6 rounded-lg shadow-md card-hover">
            <h3 class="text-2xl font-bold mt-4">Business Strategy</h3>
            <p class="text-gray-600 mt-2">Learn how to create effective business strategies for success.</p>
            <button class="mt-4 bg-gray-900 text-white px-6 py-3 rounded-full hover:bg-pink-700">Enroll Now</button>
        </div>

        <!-- Card 6 -->
        <div class="bg-white p-6 rounded-lg shadow-md card-hover">
            <h3 class="text-2xl font-bold mt-4">Data Science</h3>
            <p class="text-gray-600 mt-2">Unlock the power of data and make informed decisions.</p>
            <button class="mt-4 bg-gray-900 text-white px-6 py-3 rounded-full hover:bg-pink-700">Enroll Now</button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-bg text-white py-10">
        <div class="container mx-auto text-center">
            <h3 class="text-lg font-bold">E-Learn</h3>
            <p class="mt-4">Your gateway to knowledge. Explore courses, learn skills, and achieve your goals.</p>
            <div class="mt-6 space-x-4">
                <a href="#" class="hover:underline">Privacy Policy</a>
                <a href="#" class="hover:underline">Terms of Service</a>
                <a href="#" class="hover:underline">Help Center</a>
            </div>
            <p class="mt-4">&copy; 2025 E-Learn. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>

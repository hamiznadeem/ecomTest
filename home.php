<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Ecom Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Navbar -->
    <header class="bg-white shadow">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-2xl font-bold text-indigo-600">EcomStore</h1>
            <nav class="hidden md:flex space-x-6">
                <a href="#" class="hover:text-indigo-600">Home</a>
                <a href="#" class="hover:text-indigo-600">Shop</a>
                <a href="#" class="hover:text-indigo-600">About</a>
                <a href="#" class="hover:text-indigo-600">Contact</a>
            </nav>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Cart (0)</button>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="bg-indigo-50 py-20">
        <div class="container mx-auto flex flex-col md:flex-row items-center px-6">
            <div class="flex-1">
                <h2 class="text-4xl font-bold mb-4">Discover the Best Products Online</h2>
                <p class="mb-6 text-lg">Shop the latest fashion, electronics, and more with amazing deals and fast delivery.</p>
                <a href="#" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">Shop Now</a>
            </div>
            <div class="flex-1 mt-10 md:mt-0">
                <img src="https://via.placeholder.com/500x350" alt="Hero Image" class="rounded-xl shadow-lg">
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 container mx-auto px-6">
        <h3 class="text-2xl font-bold mb-8 text-center">Featured Products</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">

            <!-- Product Card -->
            <div class="bg-white shadow rounded-xl overflow-hidden hover:shadow-lg transition">
                <img src="https://via.placeholder.com/300x200" alt="Product" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-semibold text-lg">Product Name</h4>
                    <p class="text-gray-500 mb-2">$49.99</p>
                    <button class="bg-indigo-600 text-white w-full py-2 rounded-lg hover:bg-indigo-700">Add to Cart</button>
                </div>
            </div>

            <!-- Repeat Product -->
            <div class="bg-white shadow rounded-xl overflow-hidden hover:shadow-lg transition">
                <img src="https://via.placeholder.com/300x200" alt="Product" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-semibold text-lg">Product Name</h4>
                    <p class="text-gray-500 mb-2">$59.99</p>
                    <button class="bg-indigo-600 text-white w-full py-2 rounded-lg hover:bg-indigo-700">Add to Cart</button>
                </div>
            </div>

            <!-- Add more product cards as needed -->

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-200 py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2025 EcomStore. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>
<!-- Footer -->
<footer class="mt-12 bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} StudEats Admin Panel. Built with Laravel {{ app()->version() }}</p>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span>System Operational</span>
            </div>
        </div>
    </div>
</footer>
<x-filament-widgets::widget>
    <x-filament::section>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Card 1: Categories -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 flex items-center justify-between">
                <div>
                    <div class="fi-wi-stats-overview-stat-label">{{ __('Total Categories') }}</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_categories'] }}</div>

                </div>

                <div class="h-14 w-14 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                    <x-heroicon-o-tag class="w-7 h-7 text-blue-600 dark:text-blue-400" />
                </div>
            </div>

            <!-- Card 2: Customers -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 flex items-center justify-between">
                <div>
                    <div class="fi-wi-stats-overview-stat-label">{{ __('Total Customers') }}</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_customers'] }}</div>

                </div>

                <div class="h-14 w-14 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                    <x-heroicon-o-users class="w-7 h-7 text-green-600 dark:text-green-400" />
                </div>
            </div>

            <!-- Card 3: Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 flex items-center justify-between">
                <div>
                    <div class="fi-wi-stats-overview-stat-label">{{ __('Total Orders') }}</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_orders'] }}</div>

                </div>

                <div class="h-14 w-14 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                    <x-heroicon-o-shopping-cart class="w-7 h-7 text-yellow-600 dark:text-yellow-400" />
                </div>
            </div>

            <!-- Card 4: Products -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-5 flex items-center justify-between">
                <div>
                    <div class="fi-wi-stats-overview-stat-label">{{ __('Total Products') }}</div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total_products'] }}</div>
                </div>

                <div class="h-14 w-14 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <x-heroicon-o-cube class="w-7 h-7 text-purple-600 dark:text-purple-400" />
                </div>
            </div>

        </div>
    </x-filament::section>
</x-filament-widgets::widget>

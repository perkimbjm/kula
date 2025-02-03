<x-filament-panels::page>
    <div class="space-y-6 p-6"> <!-- Menambahkan jarak vertikal antara widget -->
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md"> <!-- AccountWidget dengan padding, background, rounded corners, dan shadow -->
            <livewire:filament.widgets.account-widget />
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md"> <!-- StatsOverview dengan padding, background, rounded corners, dan shadow -->
            @livewire('filament.widgets.stats-overview')
        </div>

        {{-- <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md"> <!-- StatsOverview dengan padding, background, rounded corners, dan shadow -->
            <livewire:work-scoreboard /> 
        </div> --}}

        {{-- <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md"> <!-- StatsOverview dengan padding, background, rounded corners, dan shadow -->
            @livewire('filament.widgets.advanced-stats-overview')
        </div> --}}
    </div>
</x-filament-panels::page>
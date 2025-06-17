@php use App\Models\Worksheet; $worksheets = Worksheet::all(); @endphp

<x-filament-panels::page>
    <div class="p-6">
        <style>
            .worksheet-menu {
                transition: box-shadow 0.2s, background 0.2s, transform 0.2s;
            }
            .worksheet-menu:hover {
                box-shadow: 0 8px 24px 0 rgba(37, 99, 235, 0.15), 0 1.5px 4px 0 rgba(0,0,0,0.08);
                background: #e0e7ff; /* biru muda */
                transform: translateY(-4px) scale(1.03);
            }
            .dark .worksheet-menu:hover {
                background: #1e293b; /* biru gelap untuk dark mode */
            }
            .worksheet-menu span {
                transition: color 0.2s;
            }
            .worksheet-menu:hover span {
                color: #2563eb; /* teks biru saat hover */
            }
        </style>

        <div class="flex items-center justify-between mb-6">
            <a href="{{ route('filament.app.resources.worksheets.index') }}" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Edit Menu</a>
        </div>

        <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6">

            @foreach($worksheets as $worksheet)
            <a href="{{ $worksheet->url }}" class="flex flex-col items-center justify-center p-6 transition bg-gray-100 rounded-lg hover:bg-blue-100">
                <div class="mb-4">
                    <x-dynamic-component :component="$worksheet->icon" class="w-12 h-12 p-2 text-blue-400 bg-blue-100 rounded-lg" />
                </div>
                <div class="font-semibold text-center text-gray-800">{{ strtoupper($worksheet->menu) }}</div>
            </a>
            @endforeach

        </div>
    </div>
</x-filament-panels::page>

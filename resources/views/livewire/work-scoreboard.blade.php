<div class="p-4 bg-white rounded-lg shadow-md">
    <h2 class="mb-4 text-2xl font-bold">Progres Pekerjaan Fisik</h2>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Paket</th>
                    <th scope="col" class="px-6 py-3">RT</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($facilities as $facility)
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4">{{ $facility['work']['name'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $facility['rt'] ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $status = $facility['progress_status'] ?? 'berjalan';
                                $colorClass = match($status) {
                                    'selesai' => 'bg-green-100 text-green-800',
                                    'kritis' => 'bg-red-100 text-red-800',
                                    default => 'bg-yellow-100 text-yellow-800'
                                };
                                $statusLabel = match($status) {
                                    'selesai' => 'Selesai',
                                    'kritis' => 'Kritis',
                                    default => 'Berjalan'
                                };
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

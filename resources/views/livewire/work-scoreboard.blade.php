<div class="p-4 bg-white rounded-lg shadow-md">  
    <h2 class="text-2xl font-bold mb-4">Work Scoreboard</h2>  
  
    <div class="overflow-x-auto">  
        <table class="w-full text-sm text-left text-gray-500">  
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">  
                <tr>  
                    <th scope="col" class="px-6 py-3">Name</th>  
                    <th scope="col" class="px-6 py-3">Progress</th>  
                    <th scope="col" class="px-6 py-3">Status</th>  
                </tr>  
            </thead>  
            <tbody>  
                @foreach ($works as $work)  
                    <tr class="bg-white border-b">  
                        <td class="px-6 py-4">{{ $work['name'] }}</td>  
                        <td class="px-6 py-4">  
                            <div class="w-full bg-gray-200 rounded-full h-2.5">  
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $work['progress'] }}%"></div>  
                            </div>  
                            <span class="text-sm font-medium text-blue-700">{{ $work['progress'] }}%</span>  
                        </td>  
                        <td class="px-6 py-4">  
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $work['status'] === 'completed' ? 'green' : ($work['status'] === 'in_progress' ? 'yellow' : 'red') }}-100 text-{{ $work['status'] === 'completed' ? 'green' : ($work['status'] === 'in_progress' ? 'yellow' : 'red') }}-800">  
                                {{ ucfirst($work['status']) }}  
                            </span>  
                        </td>  
                    </tr>  
                @endforeach  
            </tbody>  
        </table>  
    </div>  
</div>  

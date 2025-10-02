<div>
    <div class="bg-white shadow rounded-lg p-6 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </thead>
            <tbody>
                @foreach($instruments as $instrument)
                <tr class="bg-white border-b">
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $instrument->name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $instrument->stock }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">${{ number_format($instrument->price, 2) }}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                        <button wire:click="edit({{ $instrument->id }})" class="text-blue-600 hover:text-blue-900 mr-2">Edit</button>
                        <button wire:click="delete({{ $instrument->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

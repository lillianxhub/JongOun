<div>
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <div class="bg-gradient-to-br from-purple-500 to-blue-400 rounded-xl p-3 shadow-lg">
                <i class="fas fa-guitar text-white text-2xl"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-3xl font-bold text-gray-800">Instruments</h2>
                <p class="text-gray-500 text-sm mt-1">Manage your music instruments inventory</p>
            </div>
        </div>
        <button wire:click="create"
            class="bg-gradient-to-r from-purple-500 to-blue-400 hover:from-purple-600 hover:to-blue-500 text-white px-6 py-3 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 font-semibold">
            <i class="fas fa-plus"></i>
            <span>Add New Instrument</span>
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Name
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Stock
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Price
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($instruments as $instrument)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-music text-blue-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $instrument->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $instrument->stock > 10 ? 'bg-green-100 text-green-800' : ($instrument->stock > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    <i class="fas fa-cube mr-1"></i>
                                    {{ $instrument->stock }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">
                                    ${{ number_format($instrument->price, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="openEdit({{ $instrument->id }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                                        <i class="fas fa-edit"></i>
                                        <span class="hidden sm:inline">Edit</span>
                                    </button>
                                    <button wire:click="confirmDelete({{ $instrument->id }})"
                                        class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg flex items-center gap-2">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="hidden sm:inline">Delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 rounded-full p-6 mb-4">
                                        <i class="fas fa-guitar text-gray-400 text-4xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-700 mb-2">No Instruments Found</h3>
                                    <p class="text-gray-500 mb-4">Start by adding your first instrument to the inventory
                                    </p>
                                    <button wire:click="create"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg transition-colors duration-200">
                                        <i class="fas fa-plus mr-2"></i>Add Instrument
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            wire:click.stop="closeModal">
            <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-xl relative mx-4" wire:click.stop>
                <!-- Close Button -->
                <button wire:click.stop="closeModal"
                    class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-xl transition-colors"
                    title="Close">
                    &times;
                </button>

                <!-- Modal Header -->
                <div class="mb-6">
                    <div class="flex items-center mb-2">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-{{ $isEditMode ? 'pen-to-square' : 'plus-circle' }} text-blue-600"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $isEditMode ? 'Edit Instrument' : 'Add New Instrument' }}</h2>
                    </div>
                    <p class="text-gray-600 text-sm">
                        {{ $isEditMode ? 'Update instrument details' : 'Add a new instrument to your inventory' }}</p>
                </div>

                <!-- Form -->
                <form wire:submit.prevent="save">
                    <div class="space-y-4">
                        <!-- Instrument Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="name">
                                Instrument Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" wire:model="name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                placeholder="e.g., Acoustic Guitar">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="stock">
                                Stock Quantity <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="stock" wire:model="stock" min="0"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stock') border-red-500 @enderror"
                                placeholder="0">
                            @error('stock')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2" for="price">
                                Price (฿) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="price" wire:model="price" step="0.01" min="0"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('price') border-red-500 @enderror"
                                placeholder="0.00">
                            @error('price')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 mt-8">
                        <button type="button" wire:click="closeModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-gray-300 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <i class="fas fa-{{ $isEditMode ? 'pen-to-square' : 'plus' }} mr-2"></i>
                            {{ $isEditMode ? 'Update Instrument' : 'Add Instrument' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>

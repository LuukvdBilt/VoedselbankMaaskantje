<x-layouts::app :title="__('Magazijn item toevoegen')">
    <div class="container mx-auto px-4 py-8 dark:bg-gray-900">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-green-400">{{ __('Magazijn item toevoegen') }}</h1>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <form action="{{ route('inventory.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="ProductName" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Product Naam') }}
                    </label>
                    <input type="text" name="ProductName" id="ProductName" value="{{ old('ProductName') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('ProductName')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="Barcode" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Barcode') }}
                    </label>
                    <input type="text" name="Barcode" id="Barcode" value="{{ old('Barcode') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('Barcode')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="Category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Categorie') }}
                    </label>
                    <input type="text" name="Category" id="Category" value="{{ old('Category') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('Category')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="Supplier" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Leverancier') }}
                    </label>
                    <input type="text" name="Supplier" id="Supplier" value="{{ old('Supplier') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('Supplier')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="Quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Aantal') }}
                    </label>
                    <input type="number" name="Quantity" id="Quantity" value="{{ old('Quantity') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('Quantity')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="ExpirationDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Verval Datum') }}
                    </label>
                    <input type="date" name="ExpirationDate" id="ExpirationDate" 
                           value="{{ old('ExpirationDate') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('ExpirationDate')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="InventoryNote" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Magazijn Notitie') }}
                    </label>
                    <textarea name="InventoryNote" id="InventoryNote" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('InventoryNote') }}</textarea>
                    @error('InventoryNote')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="ProductNote" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ __('Product Notitie') }}
                    </label>
                    <textarea name="ProductNote" id="ProductNote" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('ProductNote') }}</textarea>
                    @error('ProductNote')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md font-medium">
                        {{ __('Toevoegen') }}
                    </button>
                    <a href="{{ route('inventory.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md font-medium">
                        {{ __('Annuleren') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>

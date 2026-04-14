<x-layouts::app :title="__('Magazijn')">
    @if (session('success'))
        <div class="alert alert-success w-100 w-full rounded-0 mb-0 px-4 py-3 border border-green-300 bg-green-100 text-green-800" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="container mx-auto px-4 py-8 dark:bg-gray-900">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-800 dark:text-green-400">{{ __('Magazijn') }}</h1>
            <a href="{{ route('inventory.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm font-medium">
                {{ __('Product Toevoegen') }}
            </a>
        </div>



        @if ($inventory && count($inventory) > 0)
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b dark:border-gray-600">
                        <tr>
                            <th class="px-6 py-2 text-left text-sm font-semibold text-gray-700 dark:text-green-400">
                                {{ __('Product Naam') }}</th>
                            <th class="px-6 py-2 text-left text-sm font-semibold text-gray-700 dark:text-green-400">
                                {{ __('Barcode') }}</th>
                            <th class="px-6 py-2 text-left text-sm font-semibold text-gray-700 dark:text-green-400">
                                {{ __('Categorie') }}</th>
                            <th class="px-6 py-2 text-left text-sm font-semibold text-gray-700 dark:text-green-400">
                                {{ __('Leverancier') }}</th>
                            <th class="px-6 py-2 text-left text-sm font-semibold text-gray-700 dark:text-green-400">
                                {{ __('Aantal') }}</th>
                            <th class="px-6 py-2 text-left text-sm font-semibold text-gray-700 dark:text-green-400">
                                {{ __('Verval Datum') }}</th>
                            <th class="px-6 py-2 text-left text-sm font-semibold text-gray-700 dark:text-green-400">
                                {{ __('Magazijn Notitie') }}</th>
                            <th class="px-6 py-2 text-left text-sm font-semibold text-gray-700 dark:text-green-400">
                                {{ __('Product Notitie') }}</th>
                            <th class="px-6 py-2 text-left text-sm font-semibold text-gray-700 dark:text-green-400">
                                {{ __('wijzigen') }}</th>
                            <th class="px-6 py-2 text-left text-sm font-semibold text-gray-700 dark:text-green-400">
                                {{ __('Verwijderen') }}</th>

                        </tr>
                    </thead>
                    <tbody class="divide-y dark:divide-gray-600">
                        @foreach ($inventory as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->ProductName ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->Barcode ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->Category ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->Supplier ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->Quantity ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    @if ($item->ExpirationDate)
                                        {{ \Carbon\Carbon::parse($item->ExpirationDate)->format('d-m-Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->InventoryNote ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $item->ProductNote ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('inventory.edit', $item->InventoryId) }}" class="inline-flex items-center justify-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-xs font-medium">
                                        {{ __('wijzigen') }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <form id="deleteForm-{{ $item->InventoryId }}" action="{{ route('inventory.destroy', $item->InventoryId) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="promptDeleteCode('{{ $item->InventoryId }}', '{{ $deletecode }}')" class="inline-flex items-center justify-center px-3 py-1 bg-red-500 hover:bg-red-600 text-white rounded-md text-xs font-medium">
                                            {{ __('Verwijderen') }}
                                        </button>
                                    </form>
                                    <script>
                                        function promptDeleteCode(itemId, correctCode) {
                                            const enteredCode = prompt('{{ __('Voer de verwijderingscode in:') }}');
                                            if (enteredCode === correctCode) {
                                                document.getElementById('deleteForm-' + itemId).submit();
                                            } else if (enteredCode !== null) {
                                                alert('{{ __('Ongeldige code!') }}');
                                            }
                                        }
                                    </script>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4">
                <p class="text-yellow-800 dark:text-yellow-100">{{ __('Magazijn is leeg') }}</p>
            </div>
        @endif
    </div>
</x-layouts::app>

<x-layouts::app :title="__('Leverancier Overzicht')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h1 class="px-6 py-4 text-lg font-bold text-neutral-900 dark:text-neutral-100">Leverancier Overzicht</h1>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">
                                Bedrijfsnaam</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Adres
                            </th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Naam
                            </th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">
                                Emailadres</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Mobiel
                            </th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">
                                Wijzigen</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">
                                Verwijderen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-900/50">
                                <td class="px-6 py-4 text-neutral-900 dark:text-neutral-100">{{ $supplier->CompanyName }}
                                </td>
                                <td class="px-6 py-4 text-neutral-600 dark:text-neutral-400">{{ $supplier->Address }}</td>
                                <td class="px-6 py-4 text-neutral-900 dark:text-neutral-100">{{ $supplier->FirstName }} {{ $supplier->LastName }}</td>
                                <td class="px-6 py-4 text-neutral-600 dark:text-neutral-400">—</td>
                                <td class="px-6 py-4 text-neutral-600 dark:text-neutral-400">{{ $supplier->Phone }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('supplier.edit', $supplier->Id) }}"
                                        class="text-blue-500 hover:text-blue-700">Wijzigen</a>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('supplier.destroy', $supplier->Id) }}"
                                        class="text-red-500 hover:text-red-700">Verwijderen</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-neutral-600 dark:text-neutral-400">Geen
                                    leveranciers gevonden</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts::app>
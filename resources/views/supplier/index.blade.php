<x-layouts::app :title="__('Leverancier Overzicht')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h1 class="px-6 py-4 text-xl font-bold text-neutral-900 dark:text-neutral-100">Leverancier Overzicht</h1>
            <a href="{{ route('supplier.create') }}"
                class="ml-6 m-4  inline-block rounded-md bg-green-500 px-4 py-2 text-white hover:bg-green-600">Nieuwe
                Leverancier</a>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    @if (session('success'))
                        <div class="mb-4 rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 000-1.414-1.414L9 10.586 7.707 9.293a1 1 000-1.414-1.414L8.293 11.293a1 1 000 1.414l2 2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @elseif (session('error'))
                        <div class="mb-4 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.707-9.707a1 1 000-1.414L10.586 9l-2.293-2.293a1 1 000-1.414L9.293 10l2.293 2.293a1 1 000 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
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
                                <td class="px-6 py-4 text-neutral-900 dark:text-neutral-100">{{ $supplier->FullName }}</td>
                                <td class="px-6 py-4 text-neutral-600 dark:text-neutral-400">—</td>
                                <td class="px-6 py-4 text-neutral-600 dark:text-neutral-400">{{ $supplier->Phone }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('supplier.edit', $supplier->Id) }}"
                                        class="text-blue-500 hover:text-blue-700">Wijzigen</a>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Open modal met extra bevestiging om onbedoeld verwijderen te voorkomen. --}}
                                    <form method="POST" action="{{ route('supplier.destroy', $supplier->Id) }}"
                                        onsubmit="return confirm('Weet u zeker dat u deze leverancier wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type=""
                                            class="px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700">
                                            Verwijderen
                                        </button>
                                    </form>
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

            <!-- Paginate links -->
            <div class="px-6 py-4">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</x-layouts::app>
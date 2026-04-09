<x-layouts::app :title="__('Overzicht van allergieën')">

    <div class="flex h-full w-full flex-1 gap-4">

        {{-- Linkerzijde: Titel + JOIN-informatie --}}
        <div class="flex flex-col flex-1 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100 mb-4">
                Overzicht van allergieën
            </h1>

            {{-- Tabel met JOIN-resultaten --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Naam</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Beschrijving</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Pakketten</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Producten</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                        @forelse($allergies as $allergy)
                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-900/50">
                                <td class="px-6 py-4 text-neutral-900 dark:text-neutral-100">
                                    {{ $allergy->Name }}
                                </td>

                                <td class="px-6 py-4 text-neutral-600 dark:text-neutral-400">
                                    {{ $allergy->Description }}
                                </td>

                                <td class="px-6 py-4 text-neutral-900 dark:text-neutral-100">
                                    {{ $allergy->TotalFoodPackages }}
                                </td>

                                <td class="px-6 py-4 text-neutral-900 dark:text-neutral-100">
                                    {{ $allergy->TotalProducts }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-neutral-600 dark:text-neutral-400">
                                    Geen allergieën gevonden
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Rechterkolom: Toevoegen + acties --}}
        <div class="w-64 flex flex-col gap-4">

            {{-- Toevoegen-knop --}}
            <a href="{{ route('allergies.create') }}"
               class="block rounded-lg bg-blue-600 px-4 py-2 text-center text-white font-medium hover:bg-blue-700">
                Toevoegen
            </a>

            {{-- Lijst met acties per allergie --}}
            <div class="flex flex-col gap-3">

                @forelse($allergies as $allergy)
                    <div class="flex justify-between items-center rounded-lg border border-neutral-200 dark:border-neutral-700 p-3 bg-neutral-50 dark:bg-neutral-900">

                        <span class="text-neutral-900 dark:text-neutral-100">
                            {{ $allergy->Name }}
                        </span>

                        <div class="flex gap-3">
                            <a href="{{ route('allergies.edit', $allergy->Id) }}"
                               class="text-blue-500 hover:text-blue-700 font-medium">
                                Wijzigen
                            </a>

                            <form action="{{ route('allergies.destroy', $allergy->Id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Weet je zeker dat je deze allergie wilt verwijderen?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="text-red-500 hover:text-red-700 font-medium">
                                    Verwijderen
                                </button>
                            </form>
                        </div>

                    </div>
                @empty
                    <div class="text-neutral-600 dark:text-neutral-400 text-center">
                        Geen allergieën gevonden
                    </div>
                @endforelse

            </div>
        </div>

    </div>

</x-layouts::app>

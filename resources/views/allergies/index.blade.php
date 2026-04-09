<x-layouts::app :title="__('Allergieën Overzicht')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- Happy / Unhappy messages --}}
        @if(session('success'))
            <div class="rounded-lg bg-green-100 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-lg bg-red-100 px-4 py-3 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex justify-end">
            <a href="{{ route('allergies.create') }}"
               class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Nieuwe Allergie
            </a>
        </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Naam</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Beschrijving</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Wijzigen</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">Verwijderen</th>
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

                                <td class="px-6 py-4">
                                    <a href="{{ route('allergies.edit', $allergy->Id) }}"
                                       class="text-blue-500 hover:text-blue-700">
                                        Wijzigen
                                    </a>
                                </td>

                                <td class="px-6 py-4">
                                    <form action="{{ route('allergies.destroy', $allergy->Id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Weet je zeker dat je deze allergie wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700">
                                            Verwijderen
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4"
                                    class="px-6 py-4 text-center text-neutral-600 dark:text-neutral-400">
                                    Geen allergieën gevonden
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</x-layouts::app>

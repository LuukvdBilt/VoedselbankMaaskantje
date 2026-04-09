<x-layouts::app :title="__('Allergie toevoegen')">

    <div class="max-w-xl mx-auto mt-6 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">

        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100 mb-6">
            Nieuwe allergie toevoegen
        </h1>

        <form action="{{ route('allergies.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            <div>
                <label class="block text-neutral-700 dark:text-neutral-300 mb-1">Naam</label>
                <input type="text" name="Name"
                       class="w-full rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-900 px-3 py-2"
                       required>
            </div>

            <div>
                <label class="block text-neutral-700 dark:text-neutral-300 mb-1">Beschrijving</label>
                <input type="text" name="Description"
                       class="w-full rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-900 px-3 py-2"
                       required>
            </div>

            <div class="flex justify-between mt-4">
                <a href="{{ route('allergies.index') }}"
                   class="rounded-lg bg-neutral-300 dark:bg-neutral-700 px-4 py-2 text-neutral-900 dark:text-neutral-100">
                    Annuleren
                </a>

                <button type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                    Opslaan
                </button>
            </div>
        </form>

    </div>

</x-layouts::app>

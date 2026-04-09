<x-layouts::app :title="__('Allergie toevoegen')">

    {{-- Hoofdcontainer voor het formulier met compacte breedte voor betere leesbaarheid. --}}
    <div class="max-w-xl mx-auto mt-6 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">

        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100 mb-6">
            Nieuwe allergie toevoegen
        </h1>

        {{-- Toon success feedback na opslaan. --}}
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-600 text-white px-4 py-2 animate-fade">
                {{ session('success') }}
            </div>

            {{-- Redirect na 3 seconden --}}
            <script>
                setTimeout(function () {
                    window.location.href = "{{ route('allergies.index') }}";
                }, 3000);
            </script>
        @endif

        {{-- Toon foutfeedback vanuit de sessie (bijvoorbeeld uit de controller catch). --}}
        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-600 text-white px-4 py-2 animate-fade">
                {{ session('error') }}
            </div>
        @endif

        {{-- Formulier om een nieuwe allergie aan te maken. --}}
        <form action="{{ route('allergies.store') }}" method="POST" class="flex flex-col gap-4">
            @csrf

            {{-- Naam van de allergie; old() behoudt de invoer na validatiefouten. --}}
            <div>
                <label class="block text-neutral-700 dark:text-neutral-300 mb-1">Naam</label>
                <input type="text" name="Name"
                       value="{{ old('Name') }}"
                       class="w-full rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-900 px-3 py-2"
                       required>
            </div>

            {{-- Korte omschrijving van de allergie voor duidelijkheid in overzichten. --}}
            <div>
                <label class="block text-neutral-700 dark:text-neutral-300 mb-1">Beschrijving</label>
                <input type="text" name="Description"
                       value="{{ old('Description') }}"
                       class="w-full rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-900 px-3 py-2"
                       required>
            </div>

            {{-- Vooraf berekend of handmatig ingevuld aantal gerelateerde voedselpakketten. --}}
            <div>
                <label class="block text-neutral-700 dark:text-neutral-300 mb-1">Aantal pakketten</label>
                <input type="number" name="TotalFoodPackages"
                       value="{{ old('TotalFoodPackages') }}"
                       min="0"
                       class="w-full rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-900 px-3 py-2"
                       required>
            </div>

            {{-- Aantal producten dat onder deze allergie valt; mag niet negatief zijn. --}}
            <div>
                <label class="block text-neutral-700 dark:text-neutral-300 mb-1">Aantal producten</label>
                <input type="number" name="TotalProducts"
                       value="{{ old('TotalProducts') }}"
                       min="0"
                       class="w-full rounded-lg border border-neutral-300 dark:border-neutral-600 bg-neutral-50 dark:bg-neutral-900 px-3 py-2"
                       required>
            </div>

            {{-- Actieknoppen: terug naar overzicht of formulier verzenden. --}}
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

    {{-- Fade-out animatie --}}
    <style>
        @keyframes fadeOut {
            0% { opacity: 1; }
            80% { opacity: 1; }
            100% { opacity: 0; }
        }
        .animate-fade {
            animation: fadeOut 3s forwards;
        }
    </style>

</x-layouts::app>

<x-layouts::app :title="__('Allergie bewerken')">

    {{-- Hoofdcontainer voor het bewerkformulier met compacte breedte. --}}
    <div class="max-w-xl mx-auto mt-6 rounded-xl border border-neutral-200 dark:border-neutral-700 p-6">

        <h1 class="text-2xl font-semibold mb-6">Allergie bewerken</h1>

        {{-- Toon success feedback na een geslaagde update. --}}
        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-600 text-white px-4 py-2 animate-fade">
                {{ session('success') }}
            </div>

            {{-- Navigeer automatisch terug naar het overzicht na korte bevestiging. --}}
            <script>
                setTimeout(function () {
                    window.location.href = "{{ route('allergies.index') }}";
                }, 3000);
            </script>
        @endif

        {{-- Toon algemene foutmelding vanuit de controller (bijvoorbeeld in catch-blok). --}}
        @if ($errors->has('error'))
            <div class="mb-4 rounded-lg bg-red-600 text-white px-4 py-2">
                {{ $errors->first('error') }}
            </div>
        @endif

        {{-- Toon veldspecifieke validatiefouten wanneer er geen algemene foutmelding is. --}}
        @if ($errors->any() && !$errors->has('error'))
            <div class="mb-4 rounded-lg bg-red-600 text-white px-4 py-2">
                <ul class="list-disc ml-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulier voor het bijwerken van een bestaande allergie. --}}
        <form action="{{ route('allergies.update', $allergy->Id) }}" method="POST" class="flex flex-col gap-4">
            @csrf
            @method('PUT')

            {{-- Naamveld met old()-fallback zodat invoer na validatiefout behouden blijft. --}}
            <div>
                <label class="block mb-1">Naam</label>
                <input type="text" name="Name"
                       value="{{ old('Name', $allergy->Name) }}"
                       class="w-full rounded-lg border border-neutral-300 px-3 py-2"
                       required>
            </div>

            {{-- Beschrijvingsveld met bestaande waarde als standaard. --}}
            <div>
                <label class="block mb-1">Beschrijving</label>
                <input type="text" name="Description"
                       value="{{ old('Description', $allergy->Description) }}"
                       class="w-full rounded-lg border border-neutral-300 px-3 py-2"
                       required>
            </div>

            {{-- Handmatig aantal gekoppelde pakketten; alleen niet-negatieve waarden toegestaan. --}}
            <div>
                <label class="block mb-1">Aantal pakketten (handmatig)</label>
                <input type="number" name="TotalFoodPackages"
                       value="{{ old('TotalFoodPackages', $allergy->TotalFoodPackages) }}"
                       min="0"
                       class="w-full rounded-lg border border-neutral-300 px-3 py-2"
                       required>
            </div>

            {{-- Handmatig aantal gekoppelde producten; ook hier geen negatieve waarden. --}}
            <div>
                <label class="block mb-1">Aantal producten (handmatig)</label>
                <input type="number" name="TotalProducts"
                       value="{{ old('TotalProducts', $allergy->TotalProducts) }}"
                       min="0"
                       class="w-full rounded-lg border border-neutral-300 px-3 py-2"
                       required>
            </div>

            {{-- Actieknoppen: annuleren of wijzigingen opslaan. --}}
            <div class="flex justify-between mt-4">
                <a href="{{ route('allergies.index') }}"
                   class="rounded-lg bg-neutral-300 px-4 py-2">
                    Annuleren
                </a>

                <button type="submit"
                        class="rounded-lg bg-blue-600 text-white px-4 py-2">
                    Opslaan
                </button>
            </div>
        </form>

    </div>

    <style>
        @keyframes fadeOut { 0%{opacity:1;} 80%{opacity:1;} 100%{opacity:0;} }
        .animate-fade { animation: fadeOut 3s forwards; }
    </style>

</x-layouts::app>

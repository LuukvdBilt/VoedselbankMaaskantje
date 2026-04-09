<x-layouts::app :title="__('Overzicht van allergieën')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="border-b border-neutral-200 dark:border-neutral-700 bg-neutral-50 dark:bg-neutral-900">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">
                                Naam</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">
                                Beschrijving</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">
                                Pakketten</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">
                                Producten</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">
                                Wijzigen</th>
                            <th class="px-6 py-3 text-left font-semibold text-neutral-900 dark:text-neutral-100">
                                Verwijderen</th>
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

                                <td class="px-6 py-4">
                                    <a href="{{ route('allergies.edit', $allergy->Id) }}"
                                        class="text-blue-500 hover:text-blue-700">Wijzigen</a>
                                </td>

                                <td class="px-6 py-4">
                                    <button 
                                        onclick="openDeleteModal({{ $allergy->Id }}, '{{ $allergy->Name }}')"
                                        class="text-red-500 hover:text-red-700">
                                        Verwijderen
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-6 text-center text-neutral-600 dark:text-neutral-400 italic">
                                    We hebben op het moment geen producten waar mensen allergisch voor kunnen zijn.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Toevoegen-knop onderaan --}}
        <div class="flex justify-end">
            <a href="{{ route('allergies.create') }}"
               class="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700">
                Allergie toevoegen
            </a>
        </div>

    </div>

  <!-- DELETE MODAL -->
<div id="deleteModal" 
     class="fixed inset-0 hidden items-center justify-center z-50">

    <!-- Achtergrond (transparant, geen zwart) -->
    <div class="absolute inset-0 backdrop-blur-sm"></div>

    <!-- Modal box -->
    <div class="relative bg-white dark:bg-neutral-800 p-6 rounded-xl w-full max-w-md shadow-2xl animate-fadeIn">

        <h2 class="text-xl font-semibold mb-4">Allergie verwijderen</h2>

        <p class="mb-4">
            Typ <span class="font-bold">VERWIJDEREN</span> om 
            <span id="deleteItemName" class="font-semibold"></span> definitief te verwijderen.
        </p>

        <!-- UNHAPPY MELDING -->
        <div id="deleteError" class="hidden mb-4 rounded-lg bg-red-600 text-white px-4 py-2">
            De ingevoerde code is onjuist.
        </div>

        <!-- HAPPY MELDING -->
        <div id="deleteSuccess" class="hidden mb-4 rounded-lg bg-green-600 text-white px-4 py-2">
            Verwijderen bevestigd! De allergie wordt verwijderd...
        </div>

        <input 
            id="deleteConfirmInput"
            type="text"
            placeholder="Typ VERWIJDEREN"
            class="w-full border border-neutral-300 rounded-lg px-3 py-2 mb-4"
        >

        <form id="deleteForm" method="POST" class="flex justify-between">
            @csrf
            @method('DELETE')

            <button type="button"
                onclick="closeDeleteModal()"
                class="px-4 py-2 rounded-lg bg-neutral-300">
                Annuleren
            </button>

            <button id="deleteConfirmButton"
                type="button"
                onclick="validateDeleteCode()"
                class="px-4 py-2 rounded-lg bg-red-600 text-white">
                Verwijderen
            </button>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to   { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.2s ease-out;
    }
</style>

<script>
    function openDeleteModal(id, name) {
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('deleteItemName').innerText = name;
        document.getElementById('deleteForm').action = "/allergies/" + id;

        // Reset
        document.getElementById('deleteConfirmInput').value = "";
        document.getElementById('deleteError').classList.add('hidden');
        document.getElementById('deleteSuccess').classList.add('hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function validateDeleteCode() {
        const input = document.getElementById('deleteConfirmInput').value;
        const errorBox = document.getElementById('deleteError');
        const successBox = document.getElementById('deleteSuccess');

        if (input !== "VERWIJDEREN") {
            errorBox.classList.remove('hidden');
            successBox.classList.add('hidden');
            return;
        }

        errorBox.classList.add('hidden');
        successBox.classList.remove('hidden');

        setTimeout(() => {
            document.getElementById('deleteForm').submit();
        }, 1500);
    }
</script>

{{-- $user = new App\Models\User;
$user->name = 'Admin';
$user->email = 'admin@maaskantje.com';
$user->password = bcrypt('achraf123');
$user->rolename = 'admin';
$user->save(); --}}
</x-layouts::app>

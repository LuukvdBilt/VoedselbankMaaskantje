<!-- resources/views/customersregistration/index.blade.php -->

<x-layouts::app :title="__('Mijn Registratie')">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-green-900 dark:text-white mb-2">
                Mijn Registratie
            </h1>
            <p class="text-gray-600 dark:text-gray-300">
                @if($client && $client->FirstName)
                    Update je persoonlijke gegevens
                @else
                    Vul je persoonlijke gegevens in om te beginnen
                @endif
            </p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-2xl p-6 mb-8 flex items-start gap-4" id="successMessage">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <div class="flex-1">
                    <p class="font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
                <button onclick="closeMessage()" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>

            <script>
                function closeMessage() {
                    document.getElementById('successMessage')?.remove();
                }

                // Auto-hide after 5 seconds
                setTimeout(closeMessage, 5000);
            </script>
        @endif

        <div class="max-w-3xl mx-auto">
            <!-- Show View (if registered) -->
            @if($client && $client->FirstName)
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden mb-8">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-slate-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-green-900 dark:text-white">Jouw Gegevens</h2>
                        <button type="button" onclick="toggleEdit()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition">
                            <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Wijzigen
                        </button>
                    </div>

                    <div id="viewMode" class="p-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Voornaam</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $client->FirstName }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Achternaam</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $client->LastName }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Telefoonnummer</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $client->Phone }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">E-mailadres</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white break-all">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Adres</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $client->Street }} {{ $client->HouseNumber }}, {{ $client->PostalCode }} {{ $client->City }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Gezinsleden</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $client->TotalMembers }} personen</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Edit Form (hidden by default if registered) -->
            <div id="editMode" @if($client && $client->FirstName) class="hidden" @endif>
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-gray-200 dark:border-slate-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                        <h2 class="text-2xl font-bold text-green-900 dark:text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Registratieformulier
                        </h2>
                    </div>

                    <form action="@if($client && $client->FirstName){{ route('customersregistration.update', $client->Id) }}@else{{ route('customersregistration.store') }}@endif" method="POST" class="p-8">
                        @csrf
                        @if($client && $client->FirstName)
                            @method('PUT')
                        @endif

                        @if ($errors->any())
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
                                <p class="font-medium text-red-800 dark:text-red-200 mb-2">Fouten gevonden:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li class="text-sm text-red-700 dark:text-red-300">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Section: Personal Information -->
                        <div class="mb-8 pb-8 border-b border-gray-200 dark:border-slate-700">
                            <h3 class="text-lg font-bold text-green-900 dark:text-white mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Persoonlijke Gegevens
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Voornaam</label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $client->FirstName ?? '') }}" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('first_name') ring-2 ring-red-500 @enderror"
                                        placeholder="Bijv: Jan" required>
                                    @error('first_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Achternaam</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $client->LastName ?? '') }}" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('last_name') ring-2 ring-red-500 @enderror"
                                        placeholder="Bijv: Jansen" required>
                                    @error('last_name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Telefoonnummer</label>
                                    <input type="tel" name="phone" value="{{ old('phone', $client->Phone ?? '') }}" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('phone') ring-2 ring-red-500 @enderror"
                                        placeholder="06 12345678" required>
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">E-mailadres</label>
                                    <input type="email" value="{{ auth()->user()->email }}" readonly
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-gray-100 dark:bg-slate-600 text-gray-900 dark:text-white cursor-not-allowed opacity-75">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Dit kan niet worden gewijzigd</p>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Address -->
                        <div class="mb-8 pb-8 border-b border-gray-200 dark:border-slate-700">
                            <h3 class="text-lg font-bold text-green-900 dark:text-white mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Adresgegevens
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Straat</label>
                                    <input type="text" name="street" value="{{ old('street', $client->Street ?? '') }}" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('street') ring-2 ring-red-500 @enderror"
                                        placeholder="Bijv: Hoofdstraat" required>
                                    @error('street')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Huisnummer</label>
                                    <input type="text" name="house_number" value="{{ old('house_number', $client->HouseNumber ?? '') }}" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('house_number') ring-2 ring-red-500 @enderror"
                                        placeholder="Bijv: 42" required>
                                    @error('house_number')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Postcode</label>
                                    <input type="text" name="postal_code" value="{{ old('postal_code', $client->PostalCode ?? '') }}" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('postal_code') ring-2 ring-red-500 @enderror"
                                        placeholder="Bijv: 1234AB" required>
                                    @error('postal_code')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stad</label>
                                    <input type="text" name="city" value="{{ old('city', $client->City ?? '') }}" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('city') ring-2 ring-red-500 @enderror"
                                        placeholder="Bijv: Amsterdam" required>
                                    @error('city')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section: Family -->
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-green-900 dark:text-white mb-6 flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.048M12 4v12m9.636-4.636a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Gezinsinformatie
                            </h3>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Totaal aantal gezinsleden</label>
                                <input type="number" name="total_members" value="{{ old('total_members', $client->TotalMembers ?? 1) }}" min="1" max="20"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('total_members') ring-2 ring-red-500 @enderror" required>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Inclusief jezelf</p>
                                @error('total_members')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-8 border-t border-gray-200 dark:border-slate-700">
                            <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-all hover:shadow-lg hover:shadow-green-500/30">
                                <svg class="w-5 h-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                @if($client && $client->FirstName)
                                    Wijzigingen Opslaan
                                @else
                                    Registratie Opslaan
                                @endif
                            </button>
                            <button type="button" onclick="toggleEdit()" class="flex-1 bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 text-gray-900 dark:text-white font-bold py-3 px-6 rounded-lg transition-all">
                                Annuleren
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleEdit() {
            document.getElementById('viewMode')?.classList.toggle('hidden');
            document.getElementById('editMode').classList.toggle('hidden');
        }
    </script>
</x-layouts::app>
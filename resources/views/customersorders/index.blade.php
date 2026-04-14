<x-layouts::app :title="__('Mijn Bestellingen')">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-green-900 dark:text-white mb-2">
                Mijn Bestellingen
            </h1>
            <p class="text-gray-600 dark:text-gray-300">
                Bekijk je eerdere en huidige bestellingen
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
                <button onclick="closeMessage()" class="text-green-600 hover:text-green-800">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            <script>
                function closeMessage() {
                    document.getElementById('successMessage')?.remove();
                }
                setTimeout(closeMessage, 5000);
            </script>
        @endif

        <div class="max-w-6xl mx-auto">
            <div class="flex gap-4 mb-8">
                <a href="{{ route('customersorders.create', $client->Id) }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-medium transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Nieuwe Bestelling
                </a>
            </div>

            @if($distributions->count() > 0)
                <div class="space-y-6">
                    @foreach($distributions as $distribution)
                        <div class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden hover:shadow-md transition">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-linear-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="font-bold text-xl text-green-900 dark:text-white">
                                            {{ $distribution->foodPackage->Name ?? 'Onbekend pakket' }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $distribution->foodPackage->Description ?? '' }}</p>
                                    </div>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium @if($distribution->is_active) bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 @else bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-gray-200 @endif">
                                        @if($distribution->is_active) Actief @else Inactief @endif
                                    </span>
                                </div>
                            </div>
                            <div class="p-6">
                                <!-- Order Info Grid -->
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-slate-700">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Bestelgeplaatst</p>
                                        <p class="text-sm text-gray-900 dark:text-white font-medium mt-1">{{ $distribution->created_at?->format('d-m-Y') ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Distributiedatum</p>
                                        <p class="text-sm text-gray-900 dark:text-white font-medium mt-1">{{ $distribution->DistributionDate?->format('d-m-Y') ?? 'Geen datum' }}</p>
                                    </div>
                                    @if($distribution->household->client->address)
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Postcode</p>
                                            <p class="text-sm text-gray-900 dark:text-white font-medium mt-1">{{ $distribution->household->client->address->PostalCode ?? 'N/A' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Huisnummer</p>
                                            <p class="text-sm text-gray-900 dark:text-white font-medium mt-1">{{ $distribution->household->client->address->HouseNumber ?? 'N/A' }}</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- Products in Package -->
                                @if($distribution->foodPackage->products && $distribution->foodPackage->products->count() > 0)
                                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-slate-700">
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                            <svg class="w-4 h-4 inline mr-1 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 3.062v6.218c0 1.02-.305 1.966-.848 2.773a3.055 3.055 0 01-1.962 1.409l-5.175.917A3.066 3.066 0 003.064 17.15V6.517A3.066 3.066 0 016.267 3.455z" clip-rule="evenodd" />
                                            </svg>
                                            Producten ({{ $distribution->foodPackage->products->count() }})
                                        </p>
                                        <div class="space-y-2">
                                            @foreach($distribution->foodPackage->products as $product)
                                                <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-slate-700 rounded">
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $product->ProductName }}</span>
                                                    <span class="text-xs font-semibold text-green-600 dark:text-green-400">x{{ $product->pivot->Quantity ?? 1 }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Allergies -->
                                @if($distribution->foodPackage->allergies && $distribution->foodPackage->allergies->count() > 0)
                                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-slate-700">
                                        <p class="text-sm font-semibold text-red-600 dark:text-red-400 mb-3">
                                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M13.707 1.293a1 1 0 00-1.414 0L.293 13.707a1 1 0 000 1.414l12.414 12.414a1 1 0 001.414 0l12.414-12.414a1 1 0 000-1.414L13.707 1.293zM2 13.414L13.414 2l11.414 11.414L13.414 24 2 13.414z" clip-rule="evenodd" />
                                            </svg>
                                            Allergieën
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($distribution->foodPackage->allergies as $allergy)
                                                <span class="inline-block px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs rounded-full">
                                                    {{ $allergy->Name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Special Requests/Notes -->
                                @if($distribution->note)
                                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-slate-700">
                                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            <svg class="w-4 h-4 inline mr-1 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 8a1 1 0 000 2h6a1 1 0 000-2H8zm0 3a1 1 0 000 2h3a1 1 0 000-2H8z" clip-rule="evenodd" />
                                            </svg>
                                            Opmerkingen
                                        </p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 italic bg-gray-50 dark:bg-slate-700 p-3 rounded">{{ $distribution->note }}</p>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <a href="{{ route('customersorders.edit', [$client->Id, $distribution->Id]) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-medium transition text-center">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Wijzigen
                                    </a>
                                    <form action="{{ route('customersorders.destroy', [$client->Id, $distribution->Id]) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze bestelling wilt verwijderen?');" style="display:inline; flex: 1;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-medium transition">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Verwijderen
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m0 0l8 4m-8-4v10l8 4m0-10l8 4m-8-4v10" />
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Je hebt nog geen bestellingen geplaatst.</p>
                    <a href="{{ route('customersorders.create', $client->Id) }}" class="inline-block bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-medium transition">
                        Plaats je eerste bestelling
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-layouts::app>
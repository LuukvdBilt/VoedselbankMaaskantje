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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($distributions as $distribution)
                        <div class="bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden hover:shadow-md transition">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                                <h3 class="font-bold text-lg text-green-900 dark:text-white">
                                    {{ $distribution->foodPackage->Name ?? 'Onbekend pakket' }}
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Hoeveelheid</p>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $distribution->Quantity }}</p>
                                </div>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Distributiedatum</p>
                                    <p class="text-gray-900 dark:text-white">{{ $distribution->DistributionDate?->format('d-m-Y H:i') ?? 'Geen datum' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium @if($distribution->is_active) bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 @else bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-gray-200 @endif">
                                        @if($distribution->is_active) Actief @else Inactief @endif
                                    </span>
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
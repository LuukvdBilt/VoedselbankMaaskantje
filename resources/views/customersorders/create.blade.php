<!-- resources/views/customersorders/create.blade.php -->

<x-layouts::app :title="__('Nieuwe Bestelling')">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-green-900 dark:text-white mb-2">
                Nieuwe Bestelling
            </h1>
            <p class="text-gray-600 dark:text-gray-300">
                Plaats een nieuwe voedingspakket bestelling
            </p>
        </div>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-200 dark:border-slate-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                    <h2 class="text-2xl font-bold text-green-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Voedselpakketten
                    </h2>
                </div>

                <form action="{{ route('customersorders.store', $client->Id) }}" method="POST" class="p-8">
                    @csrf

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

                    <!-- Package Selection / Verpakking -->
                    <div class="mb-8 pb-8 border-b border-gray-200 dark:border-slate-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                            Verpakking *
                        </label>
                        <div class="space-y-4">
                            @foreach($foodPackages as $package)
                                <label class="border border-gray-300 dark:border-slate-600 rounded-lg p-6 hover:bg-green-50 dark:hover:bg-green-900/10 transition cursor-pointer block">
                                    <div class="flex items-start gap-4">
                                        <input type="radio" name="food_package_id" value="{{ $package->Id }}" 
                                            class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-2 focus:ring-green-500 mt-1"
                                            id="package_{{ $package->Id }}"
                                            @checked(old('food_package_id') == $package->Id)>

                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $package->Name }}</h3>
                                            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $package->Description ?? 'Geen beschrijving' }}</p>

                                            <!-- Product Toevoegen -->
                                            @if($package->products && $package->products->count() > 0)
                                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                                                    <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Product Toevoegen:</p>
                                                    <div class="space-y-2">
                                                        @foreach($package->products as $product)
                                                            <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-slate-700 rounded">
                                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                                    {{ $product->ProductName ?? 'Onbekend' }}
                                                                </p>
                                                                <span class="text-xs font-semibold text-green-600 dark:text-green-400">
                                                                    x{{ $product->pivot->Quantity ?? 1 }}
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Allergies / Allergieën -->
                                            @if($package->allergies && $package->allergies->count() > 0)
                                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-slate-700">
                                                    <p class="text-xs font-semibold text-red-600 dark:text-red-400 mb-2">Allergieën:</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($package->allergies as $allergy)
                                                            <span class="inline-block px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 text-xs rounded-full">
                                                                {{ $allergy->Name ?? 'Onbekend' }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        @error('food_package_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Wensen -->
                    <div class="mb-8 pb-8 border-b border-gray-200 dark:border-slate-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Wensen
                        </label>
                        <textarea name="wishes" placeholder="Bijzondere wensen..."
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500"
                            rows="3">{{ old('wishes') }}</textarea>
                    </div>

                    <!-- Postcode -->
                    <div class="mb-8 pb-8 border-b border-gray-200 dark:border-slate-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Postcode
                        </label>
                        <input type="text" name="postal_code" value="{{ old('postal_code', $client->address->PostalCode ?? '') }}" 
                            placeholder="Bijv. 1234AB"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('postal_code') ring-2 ring-red-500 @enderror">
                        @error('postal_code')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Huisnummer -->
                    <div class="mb-8 pb-8 border-b border-gray-200 dark:border-slate-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Huisnummer
                        </label>
                        <input type="text" name="house_number" value="{{ old('house_number', $client->address->HouseNumber ?? '') }}" 
                            placeholder="Bijv. 42"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('house_number') ring-2 ring-red-500 @enderror">
                        @error('house_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Opmerking -->
                    <div class="mb-8">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Opmerking
                        </label>
                        <textarea name="note" placeholder="Aanvullende opmerkingen..."
                            class="w-full px-4 py-3 border border-gray-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 @error('note') ring-2 ring-red-500 @enderror"
                            rows="4">{{ old('note') }}</textarea>
                        @error('note')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-lg transition-all hover:shadow-lg hover:shadow-green-500/30">
                            <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Bestelling Plaatsen
                        </button>
                        <a href="{{ route('customersorders.index', $client->Id) }}" class="flex-1 bg-gray-200 dark:bg-slate-700 hover:bg-gray-300 dark:hover:bg-slate-600 text-gray-900 dark:text-white font-bold py-3 px-6 rounded-lg transition-all text-center">
                            Annuleren
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts::app>
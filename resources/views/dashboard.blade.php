<!-- resources/views/dashboard.blade.php -->

<x-layouts::app :title="__('Dashboard')">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-green-900 dark:text-white mb-2">
                Welkom terug, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="text-gray-600 dark:text-gray-300">
                Hier is je overzicht van vandaag
            </p>
        </div>

        @php
            $isEmployee = in_array(auth()->user()->rolename, ['admin', 'manager', 'supplier'], true);
            $isCustomer = auth()->user()->rolename === 'customer';
        @endphp

        @if ($isEmployee)

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat Card 1: Suppliers -->
            <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-green-50 to-transparent dark:from-green-900/10 dark:to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM12 14a9 9 0 00-9 9v2h18v-2a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-green-600 bg-green-100 dark:bg-green-900/30 px-3 py-1 rounded-full">
                            live uit DB
                        </span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Leveranciers</p>
                    <p class="text-3xl font-black text-green-900 dark:text-white">
                        {{ $supplierCount }}
                    </p>
                </div>
            </div>

            <!-- Stat Card 2: Inventory Items -->
            <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-50 to-transparent dark:from-amber-900/10 dark:to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-amber-600 bg-amber-100 dark:bg-amber-900/30 px-3 py-1 rounded-full">
                            {{ $inventoryStockTotal }} stuks
                        </span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Magazijn regels</p>
                    <p class="text-3xl font-black text-amber-600 dark:text-amber-400">
                        {{ $inventoryItemCount }}
                    </p>
                </div>
            </div>

            <!-- Stat Card 3: Allergies -->
            <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent dark:from-blue-900/10 dark:to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-blue-600 bg-blue-100 dark:bg-blue-900/30 px-3 py-1 rounded-full">
                            actief
                        </span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Allergieën</p>
                    <p class="text-3xl font-black text-blue-600 dark:text-blue-400">
                        {{ $allergyCount }}
                    </p>
                </div>
            </div>

            <!-- Stat Card 4: Active Orders -->
            <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-lg transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-red-50 to-transparent dark:from-red-900/10 dark:to-transparent"></div>
                <div class="relative">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-red-600 bg-red-100 dark:bg-red-900/30 px-3 py-1 rounded-full">
                            openstaand
                        </span>
                    </div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">Actieve bestellingen</p>
                    <p class="text-3xl font-black text-red-600 dark:text-red-400">
                        {{ $activeOrderCount }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Recent Activity -->
            <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-green-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Recente Activiteit
                    </h2>
                    <a href="#" class="text-sm text-green-600 hover:text-green-700 font-medium">Alles zien →</a>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-slate-700">
                    <!-- Activity Item 1 -->
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white">Nieuwe klant geregistreerd</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Maria Garcia, Amsterdam-Noord</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">2 minuten geleden</p>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Item 2 -->
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white">Pakket gedistribueerd</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Familie Jansen - Voedselbasket Premium</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">1 uur geleden</p>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Item 3 -->
                    <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zm-11-1a1 1 0 11-2 0 1 1 0 012 0zM8 7a1 1 0 100-2 1 1 0 000 2zm4-1a1 1 0 11-2 0 1 1 0 012 0zm3 1a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 dark:text-white">Inventaris bijgewerkt</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">52 nieuwe producten toegevoegd vanuit Jumbo Zaanstad</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">3 uur geleden</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Sidebar -->
            <div class="space-y-4">
                <!-- Action Card 1: Suppliers -->
                <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="h-1 bg-gradient-to-r from-green-500 to-green-600"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-bold text-green-900 dark:text-white">Leverancier Overzicht</h3>
                            <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Bekijk en beheer alle leveranciers op een plek.</p>
                        <a href="{{ route('supplier.index') }}" class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all group-hover:shadow-lg group-hover:shadow-green-500/30">
                            Openen
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Action Card 2: Inventory -->
                <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-bold text-green-900 dark:text-white">Magazijn</h3>
                            <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Controleer voorraad en beheer producten in het magazijn.</p>
                        <a href="{{ route('inventory.index') }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all group-hover:shadow-lg group-hover:shadow-amber-500/30">
                            Openen
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Action Card 3: Allergies -->
                <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="h-1 bg-gradient-to-r from-blue-500 to-blue-600"></div>
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-bold text-green-900 dark:text-white">Allergieën</h3>
                            <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Beheer allergieën voor veilige pakket-samenstelling.</p>
                        <a href="{{ route('allergies.index') }}" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all group-hover:shadow-lg group-hover:shadow-blue-500/30">
                            Openen
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Overview -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-slate-700">
                <h2 class="text-lg font-bold text-green-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Top 5 Beschikbare Producten
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Product</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Categorie</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Hoeveelheid</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-slate-700">
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">Rijst (1kg)</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Granen</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-semibold">145</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-3 py-1 rounded-full text-xs font-medium">
                                    Ruim beschikbaar
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">Pasta Assorti</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Granen</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-semibold">128</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 px-3 py-1 rounded-full text-xs font-medium">
                                    Ruim beschikbaar
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">Melk (1L)</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Zuivel</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-semibold">89</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 px-3 py-1 rounded-full text-xs font-medium">
                                    Matig beschikbaar
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">Eieren (10 stuks)</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Zuivel</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-semibold">56</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 px-3 py-1 rounded-full text-xs font-medium">
                                    Matig beschikbaar
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">Brood (400g)</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">Bakkerij</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-semibold">34</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 px-3 py-1 rounded-full text-xs font-medium">
                                    Weinig beschikbaar
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        @elseif ($isCustomer)
            @php
                $clientId = \App\Models\Client::where('Id', auth()->id())->value('Id');
                $orderUrl = $clientId ? route('customersorders.create', $clientId) : route('customersregistration.index');
                $orderOverviewUrl = $clientId ? route('customersorders.index', $clientId) : route('customersregistration.index');
            @endphp

            <div class="rounded-3xl bg-gradient-to-br from-green-900 to-green-800 text-white p-8 md:p-10 mb-8">
                <p class="text-amber-300 text-sm font-semibold uppercase tracking-wider mb-2">Klantportaal</p>
                <h2 class="text-3xl md:text-4xl font-bold leading-tight mb-4">Welkom bij jouw voedselbank dashboard</h2>
                <p class="text-white/80 max-w-2xl">Registreer je gegevens en plaats daarna eenvoudig je bestelling. Zo kunnen wij sneller het juiste pakket voor je klaarzetten.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-7 shadow-sm">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-900 dark:text-white mb-2">registratie</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-5">Vul of wijzig je gegevens zodat je bestelling goed verwerkt kan worden.</p>
                    <a href="{{ route('customersregistration.index') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        Naar registratie
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-7 shadow-sm">
                    <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-green-900 dark:text-white mb-2">bestellen</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-5">Nog niet geregistreerd? Dan sturen we je eerst automatisch naar de registratiepagina.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ $orderUrl }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                        Naar bestellen
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        </a>
                        <a href="{{ $orderOverviewUrl }}" class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                            Naar overzicht
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 p-6">
                <p class="text-gray-700 dark:text-gray-300">Voor jouw rol zijn er momenteel geen dashboard-acties ingesteld.</p>
            </div>
        @endif
    </div>
</x-layouts::app>
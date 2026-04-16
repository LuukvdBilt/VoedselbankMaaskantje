<x-layouts::app :title="__('Leverancier Overzicht')">
    <div class="flex w-full flex-col gap-6 p-4 md:p-6 bg-slate-50/50 min-h-screen">

        <!-- Header Section -->
        <div class="flex flex-col gap-1">
            <h1 class="text-3xl font-bold text-emerald-800 flex items-center gap-2">
                Leveranciers Dashboard <span class="text-2xl"></span>
            </h1>
            <p class="text-neutral-500 text-sm">Hier is uw overzicht van alle actieve leveranciers</p>
        </div>

        <!-- Notifications (Success/Error) -->
        @if (session('success'))
            <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4">
                <div class="flex items-center gap-3">
                    <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
                    <meta http-equiv="refresh" content="3;url={{ route('supplier.index') }}">
                </div>
            </div>
        @elseif (session('error'))
            <div class="rounded-xl border border-red-100 bg-red-50 p-4">
                <div class="flex items-center gap-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    <meta http-equiv="refresh" content="6;url={{ route('supplier.index') }}">
                </div>
            </div>
        @endif

        <!-- KPI / Statistics Cards (Like top in photo 1) -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
            <!-- Card 1 -->
            <div class="flex flex-col justify-between rounded-2xl border border-neutral-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-medium text-neutral-500">Totaal Leveranciers</p>
                    <h3 class="text-3xl font-bold text-emerald-800">{{ $suppliers->total() ?? 0 }}</h3>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="flex flex-col justify-between rounded-2xl border border-neutral-100 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="text-sm font-medium text-neutral-500">Actieve Contracten</p>
                    <h3 class="text-3xl font-bold text-amber-600">{{ $IsActive ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Main Content (Table left, Actions right) -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            <!-- Left column: Table (Takes 2/3 of space) -->
            <div class="lg:col-span-2">
                <div
                    class="flex h-full flex-col overflow-hidden rounded-2xl border border-neutral-100 bg-white shadow-sm">
                    <!-- Table Header -->
                    <div class="flex items-center justify-between border-b border-neutral-100 px-6 py-4">
                        <h2 class="text-lg font-bold text-emerald-800 flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Alle Leveranciers
                        </h2>
                    </div>

                    <!-- Table Body -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-neutral-600">
                            <thead class="bg-neutral-50/50 text-xs uppercase text-neutral-500">
                                <tr>
                                    <th class="px-6 py-4 font-medium">Bedrijf</th>
                                    <th class="px-6 py-4 font-medium">Contactpersoon</th>
                                    <th class="px-6 py-4 font-medium">Mobiel</th>
                                    <th class="px-6 py-4 font-medium">Status</th>
                                    <th class="px-6 py-4 font-medium text-center">Acties</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                @forelse($suppliers as $supplier)
                                    <tr class="hover:bg-neutral-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-neutral-900">{{ $supplier->CompanyName }}</div>
                                            <div class="text-xs text-neutral-400 mt-0.5">{{ $supplier->Address }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-neutral-700">{{ $supplier->FullName }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-neutral-600">{{ $supplier->Phone }}</td>
                                        <td class="px-6 py-4 text-neutral-600">
                                            {{ $supplier->IsActive ? 'Actief' : 'Inactief' }}
                                        </td>
                                        <td class="px-6 py-4 flex items-center justify-center gap-3">
                                            <a href="{{ route('supplier.edit', $supplier->Id) }}"
                                                class="text-blue-500 hover:text-blue-700 transition cursor-pointer">
                                                Bewerken
                                            </a>
                                            <div x-data="{ open: false }">
                                                <!-- Trigger Button -->
                                                <button @click="open = true" type="button" class="text-red-500 hover:text-red-700 transition cursor-pointer">
                                                    Verwijderen
                                                </button>

                                                <!-- Modal Backdrop -->
                                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto" x-cloak>

                                                    <!-- Dark Overlay -->
                                                    <div class="fixed inset-0 bg-black opacity-50" @click="open = false">
                                                    </div>

                                                    <!-- Modal Content -->
                                                    <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6 mx-4 z-10">
                                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Verwijdering
                                                            bevestigen</h3>
                                                        <p class="text-gray-600 mb-6">
                                                            Weet u zeker dat u deze leverancier wilt verwijderen? Deze actie
                                                            kan niet ongedaan gemaakt worden.
                                                        </p>

                                                        <div class="flex justify-end gap-3">
                                                            <!-- Cancel Button -->
                                                            <button @click="open = false"
                                                                class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md transition cursor-pointer">
                                                                Annuleren
                                                            </button>

                                                            <!-- Confirm Delete Form -->
                                                            <form method="POST" action="{{ route('supplier.destroy', $supplier->Id) }}" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-md transition cursor-pointer">
                                                                    Ja, verwijderen
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-neutral-500">
                                            Geen leveranciers gevonden in het systeem.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="border-t border-neutral-100 p-4 bg-neutral-50/30">
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>

            <!-- Right column: Action Cards (Takes 1/3 of space) -->
            <div class="flex flex-col gap-4">

                <!-- Action Card: Add New (Looks like 'My Data' in your photo) -->
                <div
                    class="rounded-2xl border-t-4 border-t-emerald-500 bg-white p-6 shadow-sm border-x border-b border-neutral-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-bold text-emerald-800">Nieuwe Leverancier</h3>
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-neutral-500 mb-4">Voeg een nieuwe leverancier toe aan de database om
                        bestellingen te plaatsen.</p>
                    <a href="{{ route('supplier.create') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600 w-fit">
                        Toevoegen
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
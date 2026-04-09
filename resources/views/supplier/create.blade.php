<x-layouts::app :title="__('Nieuwe Leverancier')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <h1 class="px-6 py-4 text-xl font-bold text-neutral-900 dark:text-neutral-100">Nieuwe Leverancier</h1>

            <form action="{{ route('supplier.store') }}" method="POST" class="space-y-6 px-6 py-4">
                @csrf

                <div>
                    <label for="CompanyName" class="block text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                        Bedrijfsnaam
                    </label>
                    <input type="text" name="CompanyName" id="CompanyName" value="{{ old('CompanyName') }}"
                        class="mt-2 w-full rounded-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 px-3 py-2 text-neutral-900 dark:text-neutral-100 @error('CompanyName') border-red-500 @enderror"
                        required>
                    @error('CompanyName')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="FullName" class="block text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                        Naam
                    </label>
                    <input type="text" name="FullName" id="FullName" value="{{ old('FullName') }}"
                        class="mt-2 w-full rounded-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 px-3 py-2 text-neutral-900 dark:text-neutral-100 @error('FullName') border-red-500 @enderror"
                        required>
                    @error('FullName')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="Address" class="block text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                        Adres
                    </label>
                    <input type="text" name="Address" id="Address" value="{{ old('Address') }}"
                        class="mt-2 w-full rounded-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 px-3 py-2 text-neutral-900 dark:text-neutral-100 @error('Address') border-red-500 @enderror"
                        required>
                    @error('Address')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="Email" class="block text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                        Emailadres
                    </label>
                    <input type="email" name="Email" id="Email" value="{{ old('Email') }}"
                        class="mt-2 w-full rounded-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 px-3 py-2 text-neutral-900 dark:text-neutral-100 @error('Email') border-red-500 @enderror"
                        required>
                    @error('Email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="Phone" class="block text-sm font-semibold text-neutral-900 dark:text-neutral-100">
                        Mobiel
                    </label>
                    <input type="tel" name="Phone" id="Phone" value="{{ old('Phone') }}"
                        class="mt-2 w-full rounded-md border border-neutral-300 dark:border-neutral-600 bg-white dark:bg-neutral-800 px-3 py-2 text-neutral-900 dark:text-neutral-100 @error('Phone') border-red-500 @enderror"
                        required>
                    @error('Phone')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                        class="rounded-md bg-green-500 px-4 py-2 text-white font-semibold hover:bg-green-600">
                        Opslaan
                    </button>
                    <a href="{{ route('supplier.index') }}"
                        class="rounded-md bg-neutral-500 px-4 py-2 text-white font-semibold hover:bg-neutral-600">
                        Annuleren
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
<x-layouts::app :title="__('Leverancier bewerken')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div
            class="relative mx-auto w-full max-w-2xl overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">

            <h1 class="px-6 py-4 text-xl font-bold text-neutral-900 dark:text-neutral-100">
                Leverancier bewerken
            </h1>

            @if (session('error'))
                <div class="mb-4 rounded-md bg-red-50 p-4">
                    <p class="text-lg font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <form action="{{ route('supplier.update', ['id' => $supplier->Id]) }}" method="POST" class="space-y-6 px-6 py-4">
                @csrf
                @method('PUT')

                {{-- Bedrijfsnaam --}}
                <div>
                    <label for="CompanyName" class="block text-sm font-semibold">Bedrijfsnaam</label>
                    <input type="text" name="CompanyName" id="CompanyName"
                        value="{{ old('CompanyName', $supplier->CompanyName) }}"
                        class="mt-2 w-full rounded-md border px-3 py-2 @error('CompanyName') border-red-500 @enderror"
                        required>
                </div>

                {{-- Email --}}
                <div>
                    <label for="Email" class="block text-sm font-semibold">Emailadres</label>
                    <input type="email" name="Email" id="Email" value="{{ old('Email', $supplier->Email) }}"
                        class="mt-2 w-full rounded-md border px-3 py-2 @error('Email') border-red-500 @enderror"
                        required>
                </div>

                {{-- Telefoon --}}
                <div>
                    <label for="Phone" class="block text-sm font-semibold">Mobiel</label>
                    <input type="tel" name="Phone" id="Phone" value="{{ old('Phone', $supplier->Phone) }}"
                        placeholder="0612345678 of +31612345678" pattern="^(\+31|0)(6|1|2|3|4|5|7|8|9)[0-9]{8}$"
                        class="mt-2 w-full rounded-md border px-3 py-2 @error('Phone') border-red-500 @enderror"
                        required>
                </div>

                {{-- Voornaam --}}
                <div>
                    <label for="FirstName" class="block text-sm font-semibold">Voornaam</label>
                    <input type="text" name="FirstName" id="FirstName"
                        value="{{ old('FirstName', $supplier->FirstName) }}"
                        class="mt-2 w-full rounded-md border px-3 py-2 @error('FirstName') border-red-500 @enderror"
                        required>
                </div>

                {{-- Achternaam --}}
                <div>
                    <label for="LastName" class="block text-sm font-semibold">Achternaam</label>
                    <input type="text" name="LastName" id="LastName" value="{{ old('LastName', $supplier->LastName) }}"
                        class="mt-2 w-full rounded-md border px-3 py-2 @error('LastName') border-red-500 @enderror"
                        required>
                </div>

                {{-- Straat --}}
                <div>
                    <label for="Street" class="block text-sm font-semibold">Straat</label>
                    <input type="text" name="Street" id="Street" value="{{ old('Street', $supplier->Street) }}"
                        class="mt-2 w-full rounded-md border px-3 py-2 @error('Street') border-red-500 @enderror"
                        required>
                </div>

                {{-- Huisnummer --}}
                <div>
                    <label for="HouseNumber" class="block text-sm font-semibold">Huisnummer</label>
                    <input type="number" name="HouseNumber" id="HouseNumber"
                        value="{{ old('HouseNumber', $supplier->HouseNumber) }}" min="1" max="9999"
                        class="mt-2 w-full rounded-md border px-3 py-2 @error('HouseNumber') border-red-500 @enderror"
                        required>
                </div>

                {{-- Postcode --}}
                <div>
                    <label for="PostalCode" class="block text-sm font-semibold">Postcode</label>
                    <input type="text" name="PostalCode" id="PostalCode"
                        value="{{ old('PostalCode', $supplier->PostalCode) }}" placeholder="1234AB"
                        pattern="^[1-9][0-9]{3}\s?[A-Za-z]{2}$"
                        class="mt-2 w-full rounded-md border px-3 py-2 @error('PostalCode') border-red-500 @enderror"
                        required>
                </div>

                {{-- Plaats --}}
                <div>
                    <label for="City" class="block text-sm font-semibold">Plaats</label>
                    <input type="text" name="City" id="City" value="{{ old('City', $supplier->City) }}"
                        class="mt-2 w-full rounded-md border px-3 py-2 @error('City') border-red-500 @enderror"
                        required>
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
<!-- resources/views/customers/registration/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-green-900">Gegevens Wijzigen</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <p class="font-bold">De ingevoerde gegevens zijn niet geldig.</p>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.registration.update', $client->Id) }}" method="POST" class="bg-white rounded-lg shadow p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Voornaam *</label>
                <input type="text" name="first_name" value="{{ old('first_name', $client->FirstName) }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Achternaam *</label>
                <input type="text" name="last_name" value="{{ old('last_name', $client->LastName) }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telefoonnummer *</label>
                <input type="tel" name="phone" value="{{ old('phone', $client->Phone) }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gezinsleden *</label>
                <input type="number" name="total_members" value="{{ old('total_members', $client->TotalMembers) }}" min="1" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Straat *</label>
                <input type="text" name="street" value="{{ old('street', $client->Street) }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Huisnummer *</label>
                <input type="text" name="house_number" value="{{ old('house_number', $client->HouseNumber) }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Postcode *</label>
                <input type="text" name="postal_code" value="{{ old('postal_code', $client->PostalCode) }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stad *</label>
                <input type="text" name="city" value="{{ old('city', $client->City) }}" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>
        </div>

        <div class="flex gap-4 pt-6 border-t">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-2 rounded font-medium transition">
                Opslaan
            </button>
            <a href="{{ route('customers.registration.show', $client->Id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-2 rounded font-medium transition">
                Annuleren
            </a>
        </div>
    </form>
</div>
@endsection
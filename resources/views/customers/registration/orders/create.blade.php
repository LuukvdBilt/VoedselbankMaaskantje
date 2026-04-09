<!-- resources/views/customers/orders/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-6 text-green-900">{{ isset($order) ? 'Bestelling wijzigen' : 'Nieuwe bestelling' }}</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <p class="font-bold">De ingevulde gegevens zijn niet geldig. Vul alle verplichte velden in.</p>
        </div>
    @endif

    <form action="{{ isset($order) ? route('customers.orders.update', [$client->Id, $order->Id]) : route('customers.orders.store', $client->Id) }}" method="POST" class="bg-white rounded-lg shadow p-8">
        @csrf
        @if (isset($order))
            @method('PUT')
        @endif

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Selecteer voedselpakket *</label>
            <select name="food_package_id" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" required>
                <option value="">-- Kies een pakket --</option>
                @foreach ($packages as $package)
                    <option value="{{ $package->Id }}" 
                        {{ (isset($order) && $order->FoodPackageId == $package->Id) ? 'selected' : '' }}>
                        {{ $package->Name }} - {{ $package->Description }}
                    </option>
                @endforeach
            </select>
            @error('food_package_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Opmerkingen</label>
            <textarea name="note" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" rows="4" placeholder="Bijv. Dieetwensen, allergieën, speciale wensen">{{ isset($order) ? $order->note : '' }}</textarea>
        </div>

        <div class="flex gap-4 pt-6 border-t">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-8 py-2 rounded font-medium transition">
                {{ isset($order) ? 'Bijwerken' : 'Bestelling plaatsen' }}
            </button>
            <a href="{{ route('customers.orders.index', $client->Id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-2 rounded font-medium transition">
                Annuleren
            </a>
        </div>
    </form>
</div>
@endsection
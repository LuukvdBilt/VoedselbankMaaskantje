<!-- resources/views/customers/orders/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-green-900">Mijn Bestellingen</h1>
            <p class="text-gray-600 mt-1">Bestellingen van {{ $client->FirstName }} {{ $client->LastName }}</p>
        </div>
        <a href="{{ route('customers.orders.create', $client->Id) }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded font-medium transition">
            + Bestelling toevoegen
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if (is_array($orders) && count($orders) > 0)
        <div class="space-y-4">
            @foreach ($orders as $order)
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">{{ $order->PackageName }}</h3>
                            <p class="text-gray-600 mt-1">{{ $order->PackageDescription }}</p>
                            <p class="text-sm text-gray-500 mt-2">
                                📅 {{ \Carbon\Carbon::parse($order->DistributionDate)->format('d-m-Y H:i') }}
                            </p>
                            @if ($order->VolunteerName)
                                <p class="text-sm text-gray-500">
                                    👤 Vrijwilliger: {{ $order->VolunteerName }}
                                </p>
                            @endif
                            @if ($order->note)
                                <p class="text-sm text-gray-600 mt-2 italic">Opmerking: {{ $order->note }}</p>
                            @endif
                        </div>
                        <div class="flex gap-2 ml-4">
                            <a href="{{ route('customers.orders.edit', [$client->Id, $order->Id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium transition">
                                Wijzigen
                            </a>
                            <form action="{{ route('customers.orders.destroy', [$client->Id, $order->Id]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Weet je zeker dat je deze bestelling wilt verwijderen?')" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded text-sm font-medium transition">
                                    Verwijderen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-gray-50 rounded-lg p-12 text-center border-2 border-dashed border-gray-300">
            <p class="text-gray-600 text-lg mb-4">Je hebt nog geen bestellingen geplaatst.</p>
            <a href="{{ route('customers.orders.create', $client->Id) }}" class="text-green-500 hover:text-green-700 font-medium">
                Plaats nu je eerste bestelling →
            </a>
        </div>
    @endif

    <div class="mt-8">
        <a href="{{ route('customers.registration.show', $client->Id) }}" class="text-green-500 hover:text-green-700 font-medium">
            ← Terug naar mijn gegevens
        </a>
    </div>
</div>
@endsection
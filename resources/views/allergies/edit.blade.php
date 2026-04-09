@extends('layouts.app')

@section('content')
<div class="container mt-4">

    <h2>Allergie bewerken</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Er zijn fouten gevonden:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('allergies.update', $allergy->Id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="Name" class="form-label">Naam</label>
            <input type="text" name="Name" id="Name" class="form-control" value="{{ $allergy->Name }}" required>
        </div>

        <div class="mb-3">
            <label for="Description" class="form-label">Beschrijving</label>
            <input type="text" name="Description" id="Description" class="form-control" value="{{ $allergy->Description }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Bijwerken</button>
        <a href="{{ route('allergies.index') }}" class="btn btn-secondary">Annuleren</a>
    </form>

</div>
@endsection

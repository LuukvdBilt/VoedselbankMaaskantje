<?php

namespace App\Http\Controllers;

use App\Models\AllergiesModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AllergiesController extends Controller
{
    // Eenvoudige model-instantie voor queries die niet via standaard Eloquent-relaties lopen.
    private $AllergiesModel;

    public function __construct()
    {
        // Initialiseer het model eenmalig zodat acties dezelfde query-methodes gebruiken.
        $this->AllergiesModel = new AllergiesModel();
    }

    public function index()
    {
        // Haal verrijkte lijst op met aantallen pakketten en producten per allergie.
        $allergies = $this->AllergiesModel->getAllAllergies();

        Log::info('Allergie-overzicht geladen.', [
            'count' => $allergies->count(),
            'user_id' => auth()->id(),
        ]);

        // Geef de resultaten door aan de overzichtspagina.
        return view('allergies.index', compact('allergies'));
    }

    public function create()
    {
        Log::info('Allergie-aanmaakformulier geopend.', [
            'user_id' => auth()->id(),
        ]);

        // Toon alleen het formulier; opslaan gebeurt in store().
        return view('allergies.create');
    }

    public function store(Request $request)
    {
        // Valideer invoer voordat er iets in de database wordt weggeschreven.
        $validated = $request->validate([
            'Name' => [
                'required',
                'string',
                'max:255',
                // Voorkom dubbele allergieën op basis van naam.
                Rule::unique('Allergies', 'Name'),
            ],
            'Description' => 'required|string|max:255',
            'TotalFoodPackages' => 'required|integer|min:0',
            'TotalProducts' => 'required|integer|min:0',
        ], [
            'Name.unique' => 'Deze allergie bestaat al.',
        ]);

        try {
            Log::info('Start met aanmaken van allergie.', [
                'name' => $validated['Name'],
                'user_id' => auth()->id(),
            ]);

            // Gebruik alleen gevalideerde velden voor mass assignment.
            $allergy = AllergiesModel::create($validated);

            Log::info('Allergie succesvol aangemaakt.', [
                'allergy_id' => $allergy->Id,
                'name' => $allergy->Name,
                'user_id' => auth()->id(),
            ]);

            return back()->with('success', 'Allergie succesvol toegevoegd!');
        } catch (\Exception $e) {
            Log::error('Aanmaken van allergie mislukt.', [
                'name' => $validated['Name'] ?? null,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            // Toon een nette foutmelding en behoud formulierdata voor de gebruiker.
            return back()
                ->withErrors(['error' => 'Er ging iets mis bij het opslaan van de allergie.'])
                ->withInput();
        }
    }

    public function edit($id)
    {
        Log::info('Start met laden van allergie-bewerkformulier.', [
            'allergy_id' => $id,
            'user_id' => auth()->id(),
        ]);

        try {
            // Stop met een 404 als het record niet bestaat.
            $allergy = AllergiesModel::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::warning('Allergie niet gevonden voor bewerken.', [
                'allergy_id' => $id,
                'user_id' => auth()->id(),
            ]);

            throw $e;
        }

        Log::info('Allergie-bewerkformulier geladen.', [
            'allergy_id' => $allergy->Id,
            'name' => $allergy->Name,
            'user_id' => auth()->id(),
        ]);

        // Toon formulier met bestaande waarden voor bewerken.
        return view('allergies.edit', compact('allergy'));
    }

    public function update(Request $request, $id)
    {
        // Zelfde validatie als bij aanmaken, maar negeer de huidige record-ID bij unique-check.
        $validated = $request->validate([
            'Name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('Allergies', 'Name')->ignore($id, 'Id'),
            ],
            'Description' => 'required|string|max:255',
            'TotalFoodPackages' => 'required|integer|min:0',
            'TotalProducts' => 'required|integer|min:0',
        ], [
            'Name.unique' => 'Deze allergie bestaat al.',
        ]);

        try {
            Log::info('Start met bijwerken van allergie.', [
                'allergy_id' => $id,
                'name' => $validated['Name'],
                'user_id' => auth()->id(),
            ]);

            // Haal op of faal direct; daarna alleen bijwerken met gevalideerde data.
            $allergy = AllergiesModel::findOrFail($id);
            $allergy->update($validated);

            Log::info('Allergie succesvol bijgewerkt.', [
                'allergy_id' => $allergy->Id,
                'name' => $allergy->Name,
                'user_id' => auth()->id(),
            ]);

            return back()->with('success', 'Allergie succesvol bijgewerkt!');
        } catch (\Exception $e) {
            Log::error('Bijwerken van allergie mislukt.', [
                'allergy_id' => $id,
                'name' => $validated['Name'] ?? null,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            // Bij fout blijft de invoer bewaard zodat de gebruiker niets opnieuw hoeft in te vullen.
            return back()
                ->withErrors(['error' => 'Er ging iets mis bij het bijwerken van de allergie.'])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            Log::info('Start met verwijderen van allergie.', [
                'allergy_id' => $id,
                'user_id' => auth()->id(),
            ]);

            // Zoek en verwijder de allergie; bij niet-bestaan volgt automatisch een 404.
            $allergy = AllergiesModel::findOrFail($id);
            $allergy->delete();

            Log::info('Allergie succesvol verwijderd.', [
                'allergy_id' => $id,
                'name' => $allergy->Name,
                'user_id' => auth()->id(),
            ]);

            // Na verwijderen terug naar het overzicht met een feedbackmelding.
            return redirect()
                ->route('allergies.index')
                ->with('success', 'Allergie succesvol verwijderd.');
        } catch (\Exception $e) {
            Log::error('Verwijderen van allergie mislukt.', [
                'allergy_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            // Vang onverwachte fouten af en toon een algemene melding.
            return back()->withErrors(['error' => 'Er ging iets mis bij het verwijderen van de allergie.']);
        }
    }
}

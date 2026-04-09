<?php

namespace App\Http\Controllers;

use App\Models\AllergiesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AllergiesController extends Controller
{
    private $AllergiesModel;

    public function __construct()
    {
        $this->AllergiesModel = new AllergiesModel();
    }

    /**
     * Toon overzicht van alle allergieën.
     */
    public function index()
    {
        try {
            $allergies = $this->AllergiesModel->orderBy('Name')->get();

            Log::info('Allergieën succesvol geladen.');

            return view('allergies.index', compact('allergies'));

        } catch (\Exception $e) {
            Log::error('Fout bij laden allergieën: ' . $e->getMessage());

            return back()->with('error', 'Er ging iets mis bij het laden van de allergieën.');
        }

        return view('allergies.index', compact('allergies'));
    }

    /**
     * Formulier voor nieuwe allergie.
     */
    public function create()
    {
        return view('allergies.create');
    }

    /**
     * Sla nieuwe allergie op.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Name' => 'required|string|max:255|unique:Allergies,Name',
            'Description' => 'required|string|max:255',
        ]);

        try {
            AllergiesModel::create($request->all());

            Log::info('Nieuwe allergie toegevoegd: ' . $request->Name);

            return redirect()
                ->route('allergies.index')
                ->with('success', 'Allergie succesvol toegevoegd.');

        } catch (\Exception $e) {
            Log::error('Fout bij opslaan allergie: ' . $e->getMessage());

            return back()
                ->with('error', 'Er ging iets mis bij het opslaan van de allergie.')
                ->withInput();
        }
    }

    /**
     * Formulier voor bewerken.
     */
    public function edit($id)
    {
        $allergy = AllergiesModel::findOrFail($id);

        return view('allergies.edit', compact('allergy'));
    }

    /**
     * Update allergie.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'Name' => 'required|string|max:255|unique:Allergies,Name,' . $id . ',Id',
            'Description' => 'required|string|max:255',
        ]);

        try {
            $allergy = AllergiesModel::findOrFail($id);
            $allergy->update($request->all());

            Log::info('Allergie bijgewerkt: ' . $allergy->Name);

            return redirect()
                ->route('allergies.index')
                ->with('success', 'Allergie succesvol bijgewerkt.');

        } catch (\Exception $e) {
            Log::error('Fout bij updaten allergie: ' . $e->getMessage());

            return back()
                ->with('error', 'Er ging iets mis bij het bijwerken van de allergie.')
                ->withInput();
        }
    }

    /**
     * Verwijder allergie.
     */
    public function destroy($id)
    {
        try {
            $allergy = AllergiesModel::findOrFail($id);
            $allergy->delete();

            Log::warning('Allergie verwijderd: ' . $allergy->Name);

            return redirect()
                ->route('allergies.index')
                ->with('success', 'Allergie succesvol verwijderd.');

        } catch (\Exception $e) {
            Log::error('Fout bij verwijderen allergie: ' . $e->getMessage());

            return back()->with('error', 'Er ging iets mis bij het verwijderen van de allergie.');
        }
    }
}

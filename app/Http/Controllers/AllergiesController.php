<?php

namespace App\Http\Controllers;

use App\Models\AllergiesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AllergiesController extends Controller
{
    private $AllergiesModel;

    public function __construct()
    {
        $this->AllergiesModel = new AllergiesModel();
    }

    public function index()
    {
        $allergies = $this->AllergiesModel->getAllAllergies();
        return view('allergies.index', compact('allergies'));
    }

    public function create()
    {
        return view('allergies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('Allergies', 'Name'),
            ],
            'Description' => 'required|string|max:255',
            'TotalFoodPackages' => 'required|integer|min:0',
            'TotalProducts' => 'required|integer|min:0',
        ], [
            'Name.unique' => 'Deze allergie bestaat al.',
        ]);

        try {
            AllergiesModel::create($validated);

            return back()->with('success', 'Allergie succesvol toegevoegd!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Er ging iets mis bij het opslaan van de allergie.'])
                ->withInput();
        }
    }

    public function edit($id)
    {
        $allergy = AllergiesModel::findOrFail($id);
        return view('allergies.edit', compact('allergy'));
    }

    public function update(Request $request, $id)
    {
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
            $allergy = AllergiesModel::findOrFail($id);
            $allergy->update($validated);

            return back()->with('success', 'Allergie succesvol bijgewerkt!');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Er ging iets mis bij het bijwerken van de allergie.'])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $allergy = AllergiesModel::findOrFail($id);
            $allergy->delete();

            return redirect()
                ->route('allergies.index')
                ->with('success', 'Allergie succesvol verwijderd.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Er ging iets mis bij het verwijderen van de allergie.']);
        }
    }
}

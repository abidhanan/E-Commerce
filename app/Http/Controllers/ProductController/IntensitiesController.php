<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Intensities;
use Illuminate\Validation\Rule;
class IntensitiesController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Intensities::query();
        if ($request->search) {
            $query->where('label', 'like', "%{$request->search}%");
        }
        
        $intensities = $query->latest()->paginate(10);

        if($request->ajax){
            return response()->json([
                'table' => view('Admin.Intensities.partials.table', compact('intensities'))->render(),
                'pagination' => $intensities->links()->render()
            ]);
        }
        return view('Admin.Intensities.index', compact('intensities'));
    }

    public function create()
    {
        return view('Admin.Intensities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|in:low,high',
            'description' => 'nullable|string',
        ]);

        // cek apakah label sudah ada
        $exists = Intensities::where('label', $validated['label'])->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'label' => ucfirst($validated['label']) . ' already exists.'
                ]);
        }

        Intensities::create($validated);

        return redirect()
            ->route('admin.intensities.index')
            ->with('success', 'Intensity created successfully.');
    }

    public function edit(string $id)
    {
        $intensity = Intensities::findOrFail($id);
        return view('Admin.Intensities.edit', compact('intensity'));
    }

   public function update(Request $request, string $id)
{
    $intensity = Intensities::findOrFail($id);

    $validated = $request->validate([
        'label' => [
            'required',
            Rule::in(['low', 'high']),
            Rule::unique('intensities', 'label')->ignore($intensity->id),
        ],
        'description' => 'nullable|string',
    ]);

    $intensity->update($validated);

    return redirect()
        ->route('admin.intensities.index')
        ->with('success', 'Intensity updated successfully.');
}

    public function destroy(string $id)
    {
        $intensity = Intensities::findOrFail($id);
        $intensity->delete();

        return redirect()
            ->route('admin.intensities.index')
            ->with('success', 'Intensity deleted successfully.');
    }
}
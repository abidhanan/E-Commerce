<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TemperatureProduct;

class TemperatureController extends Controller
{
    private function hasRangeOverlap(int $minTemperature, ?int $maxTemperature, ?int $ignoreId = null): bool
    {
        return TemperatureProduct::query()
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->where(function ($query) use ($minTemperature, $maxTemperature) {
                if (is_null($maxTemperature)) {
                    $query->whereNull('max_temperature')
                        ->orWhere('max_temperature', '>', $minTemperature);

                    return;
                }

                $query->where('min_temperature', '<', $maxTemperature)
                    ->where(function ($inner) use ($minTemperature) {
                        $inner->whereNull('max_temperature')
                            ->orWhere('max_temperature', '>', $minTemperature);
                    });
            })
            ->exists();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TemperatureProduct::query();

        if ($request->search) {
            $query->where('label', 'like', "%{$request->search}%");
        }

        $temperatures = $query->latest()->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'table' => view('Admin.Temperature.partials.table', compact('temperatures'))->render(),
                'pagination' => $temperatures->links()->render()
            ]);
        }

        return view('Admin.Temperature.index', compact('temperatures'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('Admin.Temperature.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'min_temperature' => 'required|integer',
            'max_temperature' => 'nullable|integer|gte:min_temperature',
            'label' => 'nullable|string|max:50',
            'description' => 'required|string',
        ]);

        $label = $validated['label'] ?? (
            !is_null($validated['max_temperature'])
                ? "{$validated['min_temperature']} – {$validated['max_temperature']}°C"
                : "+{$validated['min_temperature']}°C"
        );

        $exists = $this->hasRangeOverlap(
            $validated['min_temperature'],
            $validated['max_temperature'],
        );

        if ($exists) {
            return back()->withErrors([
                'min_temperature' => 'Temperature range overlaps with existing data.'
            ])->withInput();
        }

        TemperatureProduct::create([
            'min_temperature' => $validated['min_temperature'],
            'max_temperature' => $validated['max_temperature'],
            'label' => $label,
            'description' => $validated['description'],
        ]);

        return redirect()
            ->route('admin.temperatures.index')
            ->with('success', 'Temperature created successfully.');
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       $temperature = TemperatureProduct::findOrFail($id);
        return view('Admin.Temperature.edit', compact('temperature'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
{
    $temperature = TemperatureProduct::findOrFail($id);

    $validated = $request->validate([
        'min_temperature' => 'required|integer',
        'max_temperature' => 'nullable|integer|gte:min_temperature',
        'label' => 'nullable|string|max:50',
        'description' => 'required|string',
    ]);

    // ===== FIX LABEL (handle 0 properly) =====
    $max = $validated['max_temperature'];

    if (!empty($validated['label'])) {
        $label = $validated['label'];
    } else {
        $label = !is_null($max)
            ? "{$validated['min_temperature']} – {$max}°C"
            : "+{$validated['min_temperature']}°C";
    }

    $newMin = $validated['min_temperature'];
    $exists = $this->hasRangeOverlap($newMin, $max, (int) $id);

    if ($exists) {
        return back()->withErrors([
            'min_temperature' => 'Temperature range overlaps with existing data.'
        ])->withInput();
    }

    // ===== UPDATE DATA =====
    $temperature->update([
        'min_temperature' => $newMin,
        'max_temperature' => $validated['max_temperature'],
        'label' => $label,
        'description' => $validated['description'],
    ]);

    return redirect()
        ->route('admin.temperatures.index')
        ->with('success', 'Temperature updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $temperature = TemperatureProduct::findOrFail($id);
        $temperature->delete();

        return redirect()
            ->route('admin.temperatures.index')
            ->with('success', 'Temperature deleted successfully.');
    }
}

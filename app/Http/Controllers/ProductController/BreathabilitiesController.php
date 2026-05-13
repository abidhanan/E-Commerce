<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Breathability;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class BreathabilitiesController extends Controller
{
    public function index(Request $request)
    {
        $query = Breathability::query();
        if ($request->search) {
            $query->where('label', 'like', "%{$request->search}%");
        }
        $breathabilities = Breathability::latest()->paginate(10);
        if($request->ajax){
            return response()->json([
                'table' => view('Admin.Breathability.partials.table', compact('breathabilities'))->render(),
                'pagination' => $breathabilities->links()->render()
            ]);
        }
        return view('Admin.Breathability.index', compact('breathabilities'));
    }

    public function create()
    {
        return view('Admin.Breathability.create');
    }



public function store(Request $request)
{
    $validated = $request->validate([
        'level' => [
            'required',
            'integer',
            'min:0', 
            'max:6',
            Rule::unique('breathabilities', 'level') 
        ],
        'description' => 'nullable|string',
    ]);

    DB::transaction(function () use ($validated) {

        // simpan dulu pakai placeholder
        Breathability::create([
            'level' => $validated['level'],
            'label' => 'temp',
            'description' => $validated['description'] ?? null,
        ]);

        // ambil max level terbaru
        $maxLevel = Breathability::max('level');

        // update semua label jadi level/max
        Breathability::query()->update([
            'label' => DB::raw("CONCAT(level, '/', {$maxLevel})")
        ]);
    });

    return redirect()
        ->route('admin.breathabilities.index')
        ->with('success', 'Breathability created successfully.');
}

    public function edit(string $id)
    {
        $breathability = Breathability::findOrFail($id);
        return view('Admin.Breathability.edit', compact('breathability'));
    }

    public function update(Request $request, string $id)
{
    $breathability = Breathability::findOrFail($id);

    $validated = $request->validate([
        'level' => 'required|integer|unique:breathabilities,level,' . $id,
        'description' => 'nullable|string',
    ]);

    DB::transaction(function () use ($validated, $breathability) {

        // update data utama dulu
        $breathability->update([
            'level' => $validated['level'],
            'description' => $validated['description'] ?? null,
        ]);

        // ambil max level terbaru
        $maxLevel = Breathability::max('level');

        // update semua label
        Breathability::query()->update([
            'label' => DB::raw("CONCAT(level, '/', {$maxLevel})")
        ]);
    });

    return redirect()
        ->route('admin.breathabilities.index')
        ->with('success', 'Breathability updated successfully.');
}

    public function destroy(string $id)
    {
        $breathability = Breathability::findOrFail($id);
        $breathability->delete();

        return redirect()
            ->route('admin.breathabilities.index')
            ->with('success', 'Breathability deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Insulation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
class InsulationController extends Controller
{
    public function index(Request $request)
    {
        $query = Insulation::query();
        if ($request->search) {
            $query->where('label', 'like', "%{$request->search}%");
        }
        $insulations = Insulation::latest()->paginate(10);
        if($request->ajax){
            return response()->json([
                'table' => view('Admin.Insulation.partials.table', compact('insulations'))->render(),
                'pagination' => $insulations->links()->render()
            ]);
        }
        return view('Admin.Insulation.index', compact('insulations'));
    }

    public function create()
    {
        return view('Admin.Insulation.create');
    }

   public function store(Request $request)
    {
        $validated = $request->validate([
            'level' => [
                'required',
                'integer',
                'min:0',
                'max:6',
                Rule::unique('insulations', 'level') 
            ],
            'description' => 'nullable|string',
        ]);

        // ===== SIMPAN DULU TANPA LABEL =====
        $insulation = Insulation::create([
            'level' => $validated['level'],
            'label' => 'temp',
            'description' => $validated['description'] ?? null,
        ]);

        // ===== AMBIL MAX LEVEL =====
        $maxLevel = Insulation::max('level');

        // ===== UPDATE SEMUA LABEL =====
        $all = Insulation::all();

        foreach ($all as $item) {
            $item->update([
                'label' => "{$item->level}/{$maxLevel}"
            ]);
        }

        return redirect()
            ->route('admin.insulations.index')
            ->with('success', 'Insulation created successfully.');
    }

    public function edit(string $id)
    {
        $insulation = Insulation::findOrFail($id);
        return view('Admin.Insulation.edit', compact('insulation'));
    }

    public function update(Request $request, string $id)
{
    $insulation = Insulation::findOrFail($id);

    $validated = $request->validate([
        'level' => 'required|integer|unique:insulations,level,' . $id,
        'description' => 'nullable|string',
    ]);

    DB::transaction(function () use ($validated, $insulation) {

        // ===== UPDATE DATA UTAMA =====
        $insulation->update([
            'level' => $validated['level'],
            'description' => $validated['description'] ?? null,
        ]);

        // ===== AMBIL MAX LEVEL TERBARU =====
        $maxLevel = Insulation::max('level');

        // ===== UPDATE SEMUA LABEL =====
        Insulation::query()->update([
            'label' => DB::raw("CONCAT(level, '/', {$maxLevel})")
        ]);
    });

    return redirect()
        ->route('admin.insulations.index')
        ->with('success', 'Insulation updated successfully.');
}
    public function destroy(string $id)
    {
        $data = Insulation::findOrFail($id);
        $data->delete();

        return redirect()
            ->route('admin.insulations.index')
            ->with('success', 'Insulation deleted successfully.');
    }
}
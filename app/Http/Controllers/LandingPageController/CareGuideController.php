<?php

namespace App\Http\Controllers\LandingpageController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CareGuide;
class CareGuideController extends Controller
{
    public function index()
    {
        $guides = CareGuide::orderBy('position')->get();
        return view('admin.care-guide.index', compact('guides'));
       
    }
    public function create()
    {
        return view('admin.care-guide.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        $data['position'] = CareGuide::max('position') + 1;
        CareGuide::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'is_active' => $data['is_active'],
            'position' => $data['position'],
        ]);

        return redirect()
            ->route('admin.care-guides.index')
            ->with('success', 'Care guide created successfully.');
    }

    public function edit(CareGuide $guide)
    {
        return view('admin.care-guide.form', compact('guide'));
    }
    public function update(Request $request, CareGuide $guide)
    {
       $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->has('is_active');
        $guide->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'is_active' => $data['is_active'],
            
        ]);

        return redirect()
            ->route('admin.care-guides.index')
            ->with('success', 'Care guide updated successfully.');
    }

    public function destroy(CareGuide $guide)
    {
        $guide->delete();
        return back()->with('success', 'Care guide deleted successfully.');
    }

    public function toggleStatus(CareGuide $guide)
    {
       
        $guide->update([
            'is_active' => ! $guide->is_active,
        ]);
        
        return back()->with('success', 'Care guide status updated successfully.');
    }

    public function reorder(Request $request)
    {
        foreach ($request->input('order') as $index => $id) {
            CareGuide::where('id', $id)->update([
                'position' => $index + 1
            ]);
        }

        $updated = CareGuide::select('id', 'question', 'position')
            ->orderBy('position')
            ->get();

        return response()->json([
            'success' => true,
            'updated_order' => $updated
        ]);
    } 
}
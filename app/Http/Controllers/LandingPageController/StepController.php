<?php

namespace App\Http\Controllers\LandingpageController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgressStep;

class StepController extends Controller
{
    public function returnindex(){
        $steps = ProgressStep::query()->where('module', 'return_module')->orderBy('step_order')->get();
        return view('admin.return.index', compact('steps'));
    }

    public function returncreate(){
        $steps = ProgressStep::query()->where('is_active', true)->orderBy('step_order')->get();
        return view('admin.return.create', compact('steps'));
    }

   public function returnstore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:progress_steps,slug',
            'description' => 'nullable|string',
            'step_order' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        ProgressStep::create([
            'module' => 'return_module',
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'step_order' => $request->step_order,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('admin.return-steps')
            ->with('success', 'Return step created successfully.');
    }

        public function returnedit(ProgressStep $step)
        {
            return view('admin.return.edit', compact('step'));
        }
    public function returnupdate(Request $request, ProgressStep $step)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:progress_steps,slug,' . $step->id,
            'description' => 'nullable|string',
            'step_order' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        $step->update([
            'module' => 'return_module',
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'step_order' => $request->step_order,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('admin.return-steps')
            ->with('success', 'Return step updated successfully.');
    }

    public function returndestroy(ProgressStep $step)
    {
        $step->delete();

        return redirect()
            ->route('admin.return-steps')
            ->with('success', 'Return step deleted successfully.');
    }


    public function howtobuyindex()
    {
        $steps = ProgressStep::query()
            ->where('module', 'how_to_buy_module')
            ->orderBy('step_order')
            ->get();

        return view('admin.how-to-buy.index', compact('steps'));
    }

    public function howtobuycreate()
    {
        $steps = ProgressStep::query()
            ->where('module', 'how_to_buy_module')
            ->where('is_active', true)
            ->orderBy('step_order')
            ->get();

        return view('admin.how-to-buy.create', compact('steps'));
    }
    public function howtobuystore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:progress_steps,slug',
            'description' => 'nullable|string',
            'step_order' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        ProgressStep::create([
            'module' => 'how_to_buy_module',
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'step_order' => $request->step_order,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('admin.how-to-buy-steps')
            ->with('success', 'How To Buy step created successfully.');
    }

    public function howtobuyedit(ProgressStep $step)
    {
        return view('admin.how-to-buy.edit', compact('step'));
    }

    public function howtobuytoggleStatus(ProgressStep $step)
    {
        $step->update([
            'is_active' => ! $step->is_active,
        ]);

        return redirect()
            ->route('admin.how-to-buy-steps')
            ->with('success', 'How To Buy step status updated successfully.');
    }
    public function howtobuyupdate(Request $request, ProgressStep $step)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:progress_steps,slug,' . $step->id,
            'description' => 'nullable|string',
            'step_order' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        $step->update([
            'module' => 'how_to_buy_module',
            'title' => $request->title,
            'slug' => $request->slug,
            'description' => $request->description,
            'step_order' => $request->step_order,
            'is_active' => $request->is_active,
        ]);

        return redirect()
            ->route('admin.how-to-buy-steps')
            ->with('success', 'How To Buy step updated successfully.');
    }

    public function howtobuydestroy(ProgressStep $step)
    {
        $step->delete();

        return redirect()
            ->route('admin.how-to-buy-steps')
            ->with('success', 'How To Buy step deleted successfully.');
    }
}
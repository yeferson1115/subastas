<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::query()
            ->orderBy('user_type')
            ->orderBy('duration_months')
            ->get()
            ->groupBy('user_type');

        return view('admin.plans.index', [
            'plans' => $plans,
            'userTypeLabels' => Plan::userTypeLabels(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'plans' => ['required', 'array'],
            'plans.*.id' => ['required', Rule::exists('plans', 'id')],
            'plans.*.price' => ['required', 'numeric', 'min:0'],
            'plans.*.is_active' => ['nullable', 'boolean'],
        ]);

        foreach ($validated['plans'] as $planData) {
            $plan = Plan::findOrFail($planData['id']);
            $plan->update([
                'price' => $planData['price'],
                'is_active' => (bool) ($planData['is_active'] ?? false),
            ]);
        }

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Planes actualizados correctamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index()
    {
        return view('admin.plans.index', [
            'plans' => $this->configuredPlans(),
            'durations' => Plan::durationOptions(),
            'userTypeLabels' => Plan::userTypeLabels(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'plans' => ['required', 'array'],
            'plans.*.id' => ['required', Rule::exists('plans', 'id')],
            'plans.*.duration_months' => ['required', Rule::in(Plan::DURATIONS)],
            'plans.*.price' => ['required', 'numeric', 'min:0'],
            'plans.*.is_active' => ['nullable', 'boolean'],
        ]);

        foreach ($validated['plans'] as $planData) {
            $plan = Plan::findOrFail($planData['id']);
            $durationChanged = (int) $plan->duration_months !== (int) $planData['duration_months'];

            $plan->update([
                'duration_months' => $planData['duration_months'],
                'price' => $planData['price'],
                'is_active' => (bool) ($planData['is_active'] ?? false),
            ]);

            if ($durationChanged) {
                $this->recalculateAssignedUsersExpiration($plan);
            }
        }

        return redirect()
            ->route('admin.plans.index')
            ->with('success', 'Planes actualizados correctamente.');
    }

    private function recalculateAssignedUsersExpiration(Plan $plan): void
    {
        $plan->users()
            ->whereNotNull('plan_started_at')
            ->get()
            ->each(function ($user) use ($plan) {
                $user->forceFill([
                    'plan_expires_at' => $user->plan_started_at->copy()->addMonthsNoOverflow($plan->duration_months),
                ])->save();
            });
    }

    private function configuredPlans()
    {
        foreach (array_keys(Plan::userTypeLabels()) as $userType) {
            Plan::firstOrCreate(
                ['user_type' => $userType],
                ['duration_months' => Plan::DURATION_ONE_MONTH, 'price' => 0, 'is_active' => true]
            );
        }

        return Plan::query()
            ->whereIn('user_type', array_keys(Plan::userTypeLabels()))
            ->orderBy('user_type')
            ->get()
            ->keyBy('user_type');
    }
}

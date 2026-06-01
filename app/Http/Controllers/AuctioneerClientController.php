<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class AuctioneerClientController extends Controller
{
    public function index()
    {
        $clients = User::with(['roles', 'plan'])
            ->where('user_type', User::TYPE_AUCTIONEER)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.auctioneer-clients.index', compact('clients'));
    }

    public function create()
    {
        $client = new User([
            'auctioneer_client_type' => User::AUCTIONEER_NATURAL,
            'user_type' => User::TYPE_AUCTIONEER,
        ]);

        $plans = $this->auctioneerPlans();

        return view('admin.auctioneer-clients.create', compact('client', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateClient($request);

        $client = new User();
        $this->fillClient($client, $validated, $request->input('password'));
        $client->save();

        $client->assignRole($this->auctioneerRole()->name);

        return redirect()
            ->route('admin.auctioneer-clients.index')
            ->with('success', 'Cliente subastador creado correctamente.');
    }

    public function edit(User $auctioneerClient)
    {
        abort_unless($auctioneerClient->user_type === User::TYPE_AUCTIONEER, 404);

        $client = $auctioneerClient;

        $plans = $this->auctioneerPlans();

        return view('admin.auctioneer-clients.edit', compact('client', 'plans'));
    }

    public function update(Request $request, User $auctioneerClient)
    {
        abort_unless($auctioneerClient->user_type === User::TYPE_AUCTIONEER, 404);

        $validated = $this->validateClient($request, $auctioneerClient->id);
        $this->fillClient($auctioneerClient, $validated, $request->input('password'));
        $auctioneerClient->save();

        $auctioneerClient->syncRoles([$this->auctioneerRole()->name]);

        return redirect()
            ->route('admin.auctioneer-clients.index')
            ->with('success', 'Cliente subastador actualizado correctamente.');
    }

    public function destroy(User $auctioneerClient)
    {
        abort_unless($auctioneerClient->user_type === User::TYPE_AUCTIONEER, 404);

        $auctioneerClient->delete();

        return redirect()
            ->route('admin.auctioneer-clients.index')
            ->with('success', 'Cliente subastador eliminado correctamente.');
    }

    private function validateClient(Request $request, ?int $userId = null): array
    {
        return $request->validate([
            'auctioneer_client_type' => ['required', Rule::in([User::AUCTIONEER_NATURAL, User::AUCTIONEER_COMPANY])],
            'name' => ['required', 'string', 'max:255'],
            'last_name' => [
                Rule::requiredIf($request->input('auctioneer_client_type') === User::AUCTIONEER_NATURAL),
                'nullable',
                'string',
                'max:255',
            ],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:50'],
            'document_type' => ['nullable', 'string', 'max:50'],
            'document_number' => ['nullable', 'string', 'max:80'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:120'],
            'company_name' => [
                Rule::requiredIf($request->input('auctioneer_client_type') === User::AUCTIONEER_COMPANY),
                'nullable',
                'string',
                'max:255',
            ],
            'company_document_number' => [
                Rule::requiredIf($request->input('auctioneer_client_type') === User::AUCTIONEER_COMPANY),
                'nullable',
                'string',
                'max:80',
            ],
            'company_legal_representative' => [
                Rule::requiredIf($request->input('auctioneer_client_type') === User::AUCTIONEER_COMPANY),
                'nullable',
                'string',
                'max:255',
            ],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'plan_id' => [
                'nullable',
                Rule::exists('plans', 'id')->where(fn ($query) => $query->where('user_type', User::TYPE_AUCTIONEER)->where('is_active', true)),
            ],
            'plan_started_at' => ['nullable', 'date', 'required_with:plan_id'],
        ]);
    }

    private function fillClient(User $client, array $validated, ?string $password): void
    {
        $client->fill([
            'user_type' => User::TYPE_AUCTIONEER,
            'auctioneer_client_type' => $validated['auctioneer_client_type'],
            'name' => $validated['name'],
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'document_type' => $validated['document_type'] ?? null,
            'document_number' => $validated['document_number'] ?? null,
            'address' => $validated['address'] ?? null,
            'city' => $validated['city'] ?? null,
            'company_name' => $validated['auctioneer_client_type'] === User::AUCTIONEER_COMPANY ? $validated['company_name'] : null,
            'company_document_number' => $validated['auctioneer_client_type'] === User::AUCTIONEER_COMPANY ? $validated['company_document_number'] : null,
            'company_legal_representative' => $validated['auctioneer_client_type'] === User::AUCTIONEER_COMPANY ? $validated['company_legal_representative'] : null,
            'company_phone' => $validated['auctioneer_client_type'] === User::AUCTIONEER_COMPANY ? ($validated['company_phone'] ?? null) : null,
            'company_address' => $validated['auctioneer_client_type'] === User::AUCTIONEER_COMPANY ? ($validated['company_address'] ?? null) : null,
            'plan_id' => $validated['plan_id'] ?? null,
            'plan_started_at' => ! empty($validated['plan_id']) ? $validated['plan_started_at'] : null,
            'plan_expires_at' => $this->calculatePlanExpiration($validated['plan_id'] ?? null, $validated['plan_started_at'] ?? null),
        ]);

        if ($password) {
            $client->password = Hash::make($password);
        }
    }


    private function auctioneerPlans()
    {
        return Plan::query()
            ->where('user_type', User::TYPE_AUCTIONEER)
            ->where('is_active', true)
            ->orderBy('duration_months')
            ->get();
    }

    private function calculatePlanExpiration(?int $planId, ?string $startDate): ?Carbon
    {
        if (! $planId || ! $startDate) {
            return null;
        }

        $plan = Plan::find($planId);

        if (! $plan) {
            return null;
        }

        return Carbon::parse($startDate)->addMonthsNoOverflow($plan->duration_months);
    }

    private function auctioneerRole(): Role
    {
        return Role::firstOrCreate(['name' => User::TYPE_AUCTIONEER]);
    }
}

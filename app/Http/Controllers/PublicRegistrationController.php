<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class PublicRegistrationController extends Controller
{
    public function bidder(): View
    {
        return view('public.register.bidder', [
            'plans' => $this->activePlans(User::TYPE_BIDDER),
        ]);
    }

    public function auctioneer(): View
    {
        return view('public.register.auctioneer', [
            'plans' => $this->activePlans(User::TYPE_AUCTIONEER),
        ]);
    }

    public function storeBidder(Request $request): RedirectResponse
    {
        $data = $request->validate($this->baseRules() + [
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'plan_id' => $this->planRules(User::TYPE_BIDDER),
        ]);

        $user = $this->createUser($data, User::TYPE_BIDDER, [
            'address' => $data['address'] ?? null,
            'city' => $data['city'],
        ]);

        $this->assignRole($user, User::TYPE_BIDDER);
        $this->assignSelectedPlan($user, User::TYPE_BIDDER, (int) $data['plan_id']);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('public.auctions.index')->with('status', 'Registro completado. Ya puedes ofertar en las subastas disponibles.');
    }

    public function storeAuctioneer(Request $request): RedirectResponse
    {
        $data = $request->validate($this->baseRules() + [
            'auctioneer_client_type' => ['required', 'in:' . User::AUCTIONEER_NATURAL . ',' . User::AUCTIONEER_COMPANY],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:120'],
            'company_name' => ['required_if:auctioneer_client_type,' . User::AUCTIONEER_COMPANY, 'nullable', 'string', 'max:255'],
            'company_document_number' => ['required_if:auctioneer_client_type,' . User::AUCTIONEER_COMPANY, 'nullable', 'string', 'max:80'],
            'company_legal_representative' => ['nullable', 'string', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:255'],
            'plan_id' => $this->planRules(User::TYPE_AUCTIONEER),
        ]);

        $user = $this->createUser($data, User::TYPE_AUCTIONEER, [
            'auctioneer_client_type' => $data['auctioneer_client_type'],
            'address' => $data['address'],
            'city' => $data['city'],
            'company_name' => $data['company_name'] ?? null,
            'company_document_number' => $data['company_document_number'] ?? null,
            'company_legal_representative' => $data['company_legal_representative'] ?? null,
            'company_phone' => $data['company_phone'] ?? null,
            'company_address' => $data['company_address'] ?? null,
        ]);

        $this->assignRole($user, User::TYPE_AUCTIONEER);
        $this->assignSelectedPlan($user, User::TYPE_AUCTIONEER, (int) $data['plan_id']);
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('index')->with('success', 'Registro de subastador completado. Bienvenido al panel administrativo.');
    }

    private function baseRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:50'],
            'document_type' => ['required', 'string', 'max:50'],
            'document_number' => ['required', 'string', 'max:80', 'unique:users,document_number'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'terms' => ['accepted'],
        ];
    }

    private function createUser(array $data, string $type, array $extra = []): User
    {
        return User::create(array_merge([
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'document_type' => $data['document_type'],
            'document_number' => $data['document_number'],
            'user_type' => $type,
            'password' => Hash::make($data['password']),
        ], $extra));
    }

    private function assignRole(User $user, string $role): void
    {
        Role::firstOrCreate(['name' => $role]);
        $user->assignRole($role);
    }

    private function planRules(string $type): array
    {
        return [
            'required',
            Rule::exists('plans', 'id')->where(fn ($query) => $query->where('user_type', $type)->where('is_active', true)),
        ];
    }

    private function assignSelectedPlan(User $user, string $type, int $planId): void
    {
        $plan = $this->activePlans($type)->firstWhere('id', $planId);

        abort_if(! $plan, 422, 'El plan seleccionado no está disponible.');

        $start = now();

        $user->forceFill([
            'plan_id' => $plan->id,
            'plan_started_at' => $start,
            'plan_expires_at' => $start->copy()->addMonthsNoOverflow($plan->duration_months),
        ])->save();
    }

    private function activePlans(string $type)
    {
        return Plan::query()
            ->where('user_type', $type)
            ->where('is_active', true)
            ->orderBy('duration_months')
            ->get();
    }
}


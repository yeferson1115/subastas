<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\User;
use App\Http\Requests\StoreUser;
use App\Http\Requests\UpdateUser;
use App\Models\Log\LogSistema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;


class UserController extends Controller
{
     public function __construct()
    {
        /*$this->middleware('permission:Ver Usuario')->only('index');
        $this->middleware('permission:Registrar Usuario')->only('create');
        $this->middleware('permission:Registrar Usuario')->only('store');
        $this->middleware('permission:Editar Usuario')->only('edit');
        $this->middleware('permission:Editar Usuario')->only('update');
        $this->middleware('permission:Ver Usuario')->only('show');*/

    }

    public function index(Request $request)
    {
        $users = User::with(['roles', 'permissions', 'plan'])
                       ->orderBy('created_at', 'desc')
                       ->get();



        return view('admin.usuarios.index', ['users' => $users]);
    }




    public function create()
    {

        return view('admin.usuarios.create', ['plans' => $this->activePlans()]);
    }



public function store(Request $request)
{
    $request->validate([
        'name'      => 'required|string|max:255',
        'last_name' => 'required|string|max:255',        
        'email'     => 'required|email|unique:users,email',
        'password'  => 'required|string|min:8|confirmed',            
        'phone'  => 'nullable|string|max:50',
        'contact' => 'nullable|string|max:255',
        'signature' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        'plan_id' => 'nullable|exists:plans,id',
        'plan_started_at' => 'nullable|date|required_with:plan_id',
    ]);

    // Crear el usuario
    $user = new User();
    $user->name      = $request->name;
    $user->last_name = $request->last_name;    
    $user->email     = $request->email;
    $user->password  = Hash::make($request->password);
    $user->gender    = $request->gender ?? null;   
    $user->phone    = $request->phone;
    $user->contact  = $request->contact;
    


    $user->save();

    if ($request->hasFile('signature')) {
        $user->signature_path = $this->storeSignatureInPublic($request->file('signature'), $user->id);
        $user->save();
    }

    // Asignar rol si se seleccionó
    if ($request->has('role')) {
        $user->assignRole($request->role);
        $user->user_type = $this->userTypeFromRole($request->role);
        $user->save();
    }

    $this->syncPlan($user, $request->input('plan_id'), $request->input('plan_started_at'));

    return response()->json([
        'success' => true,
        'user_id' => $user->id
    ]);
}





    public function show($id)
    {
        $user = User::find($id);
        return view('admin.usuarios.edit', ['user' => $user, 'plans' => $this->activePlans()]);
    }




    public function edit($id)
    {
        $user = User::with('roles')->with('permissions')->find($id);
        

        return view('admin.usuarios.edit', ['user' => $user, 'plans' => $this->activePlans()]);
    }




    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',            
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',            
            'phone'  => 'nullable|string|max:50',
            'contact' => 'nullable|string|max:255',
            'signature' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'plan_id' => 'nullable|exists:plans,id',
            'plan_started_at' => 'nullable|date|required_with:plan_id'
            
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->last_name = $request->last_name;        
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->contact = $request->contact;
        $user->gender=$request->gender;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('signature')) {
            $this->deletePublicFile($user->signature_path);
            $user->signature_path = $this->storeSignatureInPublic($request->file('signature'), $user->id);
        }

        $user->save();

        if ($request->has('role')) {
            $role = Role::find($request->role);
            if ($role) {
                $user->syncRoles([$role->name]);
                $user->user_type = $this->userTypeFromRole($role->name);
                $user->save();
            }
        }

        $this->syncPlan($user, $request->input('plan_id'), $request->input('plan_started_at'));

        return json_encode(['success' => true]);
    }


    private function activePlans()
    {
        return Plan::query()
            ->whereIn('user_type', [User::TYPE_AUCTIONEER, User::TYPE_BIDDER])
            ->where('is_active', true)
            ->orderBy('user_type')
            ->orderBy('duration_months')
            ->get();
    }

    private function syncPlan(User $user, ?string $planId, ?string $startDate): void
    {
        if (! $user->requiresActivePlan() || ! $planId || ! $startDate) {
            $user->forceFill([
                'plan_id' => null,
                'plan_started_at' => null,
                'plan_expires_at' => null,
            ])->save();
            return;
        }

        $plan = Plan::query()
            ->where('id', $planId)
            ->where('user_type', $user->user_type)
            ->where('is_active', true)
            ->first();

        if (! $plan) {
            $user->forceFill([
                'plan_id' => null,
                'plan_started_at' => null,
                'plan_expires_at' => null,
            ])->save();
            return;
        }

        $start = Carbon::parse($startDate);

        $user->forceFill([
            'plan_id' => $plan->id,
            'plan_started_at' => $start,
            'plan_expires_at' => $start->copy()->addMonthsNoOverflow($plan->duration_months),
        ])->save();
    }

    private function storeSignatureInPublic(UploadedFile $file, int $userId): string
    {
        $directory = public_path('firmas-comerciales');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'png');
        $filename = 'user-' . $userId . '-' . Str::random(10) . '.' . $extension;
        $file->move($directory, $filename);

        return 'firmas-comerciales/' . $filename;
    }

    private function deletePublicFile(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $fullPath = public_path($relativePath);

        if (is_file($fullPath)) {
            @unlink($fullPath);
        }
    }

    private function userTypeFromRole(string|int $role): string
    {
        if (is_numeric($role)) {
            $role = Role::find($role)?->name ?? '';
        }

        return match (strtolower((string) $role)) {
            'admin', 'administrador' => User::TYPE_ADMIN,
            'subastador' => User::TYPE_AUCTIONEER,
            default => User::TYPE_BIDDER,
        };
    }


    public function destroy($id)
    {

        $user = User::find($id)->delete();

        return json_encode(['success' => true]);
    }



    public function autocomplete(Request $request)
    {
        return User::search($request->q)->take(10)->get();
    }
}

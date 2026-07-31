<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Classified;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ClassifiedController extends Controller
{
    public function index(Request $request)
    {
        $query = Classified::with(['user', 'category', 'subcategory'])->latest();

        if (! $this->isAdmin($request->user())) {
            $query->where('user_id', $request->user()->id);
        }

        return view('admin.classifieds.index', ['classifieds' => $query->paginate(10)]);
    }

    public function create(Request $request)
    {
        return view('admin.classifieds.create', $this->formData($request, new Classified()));
    }

    public function store(Request $request)
    {
        $validated = $this->validateClassified($request);
        $validated['user_id'] = $this->resolveUserId($request, $validated);
        $validated['slug'] = $this->generateUniqueSlug($validated['title']);
        $validated['images'] = $this->storeMultipleFiles($request, 'images', 'images/classifieds');

        Classified::create($validated);

        return redirect()->route('classifieds.index')->with('success', 'Clasificado creado correctamente.');
    }

    public function edit(Request $request, Classified $classified)
    {
        $this->authorizeOwner($request, $classified);

        return view('admin.classifieds.edit', $this->formData($request, $classified));
    }

    public function update(Request $request, Classified $classified)
    {
        $this->authorizeOwner($request, $classified);
        $validated = $this->validateClassified($request, $classified);
        $validated['user_id'] = $this->resolveUserId($request, $validated);
        $validated['slug'] = $this->generateUniqueSlug($validated['title'], $classified->id);

        if ($request->hasFile('images')) {
            foreach ($classified->images ?? [] as $image) {
                $this->deleteFile($image);
            }
            $validated['images'] = $this->storeMultipleFiles($request, 'images', 'images/classifieds');
        }

        $classified->update($validated);

        return redirect()->route('classifieds.index')->with('success', 'Clasificado actualizado correctamente.');
    }

    public function destroy(Request $request, Classified $classified)
    {
        $this->authorizeOwner($request, $classified);

        foreach ($classified->images ?? [] as $image) {
            $this->deleteFile($image);
        }
        $classified->delete();

        return response()->json(['message' => 'Clasificado eliminado correctamente.']);
    }

    private function formData(Request $request, Classified $classified): array
    {
        return [
            'classified' => $classified,
            'categories' => Category::with('subcategories')->orderBy('name')->get(),
            'subcategories' => $classified->category_id ? Subcategory::where('category_id', $classified->category_id)->orderBy('name')->get() : collect(),
            'users' => $this->isAdmin($request->user()) ? User::orderBy('name')->get() : collect([$request->user()]),
            'isAdmin' => $this->isAdmin($request->user()),
        ];
    }

    private function validateClassified(Request $request, ?Classified $classified = null): array
    {
        return $request->validate([
            'user_id' => [Rule::requiredIf($this->isAdmin($request->user())), 'nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => ['nullable', Rule::exists('subcategories', 'id')->where(fn ($query) => $query->where('category_id', $request->input('category_id')))],
            'description' => ['required', 'string'],
            'contact' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'images' => [$classified?->exists ? 'nullable' : 'required', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function resolveUserId(Request $request, array $validated): int
    {
        return $this->isAdmin($request->user()) ? (int) $validated['user_id'] : $request->user()->id;
    }

    private function authorizeOwner(Request $request, Classified $classified): void
    {
        abort_unless($this->isAdmin($request->user()) || $classified->user_id === $request->user()->id, 403);
    }

    private function isAdmin(User $user): bool
    {
        return $user->user_type === User::TYPE_ADMIN || $user->hasRole(User::TYPE_ADMIN);
    }

    private function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (Classified::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        return $slug;
    }

    private function storeMultipleFiles(Request $request, string $field, string $directory): array
    {
        if (! $request->hasFile($field)) {
            return [];
        }

        $target = public_path($directory);
        if (! File::exists($target)) {
            File::makeDirectory($target, 0755, true);
        }

        return collect($request->file($field))->map(function ($file) use ($target, $directory) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->move($target, $filename);
            return $directory . '/' . $filename;
        })->all();
    }

    private function deleteFile(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}

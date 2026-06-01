<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->latest()
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeImage($request);
        }

        Category::create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $this->validateCategory($request, $category);
        $validated['slug'] = $this->generateUniqueSlug($validated['name'], $category->id);

        if ($request->hasFile('image')) {
            $this->deleteImage($category->image);
            $validated['image'] = $this->storeImage($request);
        }

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Category $category)
    {
        $this->deleteImage($category->image);
        $category->delete();

        return response()->json([
            'message' => 'Categoría eliminada correctamente.',
        ]);
    }

    private function validateCategory(Request $request, ?Category $category = null): array
    {
        return $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($category?->id),
            ],
            'image' => [$category ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Category::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function storeImage(Request $request): string
    {
        $directory = public_path('images/categories');

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $file = $request->file('image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'images/categories/' . $filename;
    }

    private function deleteImage(?string $image): void
    {
        if (! $image) {
            return;
        }

        $path = public_path($image);

        if (File::exists($path)) {
            File::delete($path);
        }
    }
}

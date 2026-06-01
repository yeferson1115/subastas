<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubcategoryController extends Controller
{
    public function index()
    {
        $subcategories = Subcategory::with('category')->latest()->paginate(10);

        return view('admin.subcategories.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $subcategory = new Subcategory();

        return view('admin.subcategories.create', compact('categories', 'subcategory'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateSubcategory($request);
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $validated['image'] = $this->storeImage($request);

        Subcategory::create($validated);

        return redirect()->route('subcategories.index')->with('success', 'Subcategoría creada correctamente.');
    }

    public function edit(Subcategory $subcategory)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.subcategories.edit', compact('categories', 'subcategory'));
    }

    public function update(Request $request, Subcategory $subcategory)
    {
        $validated = $this->validateSubcategory($request, $subcategory);
        $validated['slug'] = $this->generateUniqueSlug($validated['name'], $subcategory->id);

        if ($request->hasFile('image')) {
            $this->deleteFile($subcategory->image);
            $validated['image'] = $this->storeImage($request);
        }

        $subcategory->update($validated);

        return redirect()->route('subcategories.index')->with('success', 'Subcategoría actualizada correctamente.');
    }

    public function destroy(Subcategory $subcategory)
    {
        $this->deleteFile($subcategory->image);
        $subcategory->delete();

        return response()->json(['message' => 'Subcategoría eliminada correctamente.']);
    }

    public function byCategory(Category $category)
    {
        return $category->subcategories()->orderBy('name')->get(['id', 'name']);
    }

    private function validateSubcategory(Request $request, ?Subcategory $subcategory = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('subcategories', 'name')
                    ->where(fn ($query) => $query->where('category_id', $request->input('category_id')))
                    ->ignore($subcategory?->id),
            ],
            'image' => [$subcategory ? 'nullable' : 'required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (Subcategory::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function storeImage(Request $request): string
    {
        $directory = public_path('images/subcategories');
        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $file = $request->file('image');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        return 'images/subcategories/' . $filename;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}

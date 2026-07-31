<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Classified;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicClassifiedController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::with('subcategories')->orderBy('name')->get();
        $selectedCategory = $request->query('categoria');
        $selectedSubcategory = $request->query('subcategoria');

        $classifieds = Classified::with(['category', 'subcategory'])
            ->when($selectedCategory, fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $selectedCategory)))
            ->when($selectedSubcategory, fn ($query) => $query->whereHas('subcategory', fn ($subcategory) => $subcategory->where('slug', $selectedSubcategory)))
            ->when($request->filled('precio_min'), fn ($query) => $query->where('price', '>=', $request->query('precio_min')))
            ->when($request->filled('precio_max'), fn ($query) => $query->where('price', '<=', $request->query('precio_max')))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('public.classifieds.index', compact('classifieds', 'categories', 'selectedCategory', 'selectedSubcategory'));
    }
}

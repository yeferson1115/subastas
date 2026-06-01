<?php

namespace App\Http\Controllers;

use App\Models\AuctionProduct;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuctionProductController extends Controller
{
    public function index(Request $request)
    {
        $query = AuctionProduct::with(['auctioneer', 'category', 'subcategory'])->latest();

        if ($this->isAuctioneer($request->user()) && ! $this->isAdmin($request->user())) {
            $query->where('auctioneer_id', $request->user()->id);
        }

        $products = $query->paginate(10);

        return view('admin.auction-products.index', compact('products'));
    }

    public function create(Request $request)
    {
        $product = new AuctionProduct(['quantity' => 1, 'mandatory_visit' => false]);

        return view('admin.auction-products.create', $this->formData($request, $product));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);
        $validated['auctioneer_id'] = $this->resolveAuctioneerId($request, $validated);
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);
        $validated['mandatory_visit'] = (bool) $validated['mandatory_visit'];
        $validated['product_details'] = $validated['product_details'] ?? [];
        $validated['technical_sheet_path'] = $this->storeSingleFile($request, 'technical_sheet', 'documents/auction-products');
        $validated['terms_path'] = $this->storeSingleFile($request, 'terms', 'documents/auction-products');
        $validated['images'] = $this->storeMultipleFiles($request, 'images', 'images/auction-products');

        AuctionProduct::create($validated);

        return redirect()->route('auction-products.index')->with('success', 'Producto a subastar creado correctamente.');
    }

    public function edit(Request $request, AuctionProduct $auctionProduct)
    {
        $this->authorizeOwner($request, $auctionProduct);

        return view('admin.auction-products.edit', $this->formData($request, $auctionProduct));
    }

    public function update(Request $request, AuctionProduct $auctionProduct)
    {
        $this->authorizeOwner($request, $auctionProduct);

        $validated = $this->validateProduct($request, $auctionProduct);
        $validated['auctioneer_id'] = $this->resolveAuctioneerId($request, $validated);
        $validated['slug'] = $this->generateUniqueSlug($validated['name'], $auctionProduct->id);
        $validated['mandatory_visit'] = (bool) $validated['mandatory_visit'];
        $validated['product_details'] = $validated['product_details'] ?? [];

        if ($request->hasFile('technical_sheet')) {
            $this->deleteFile($auctionProduct->technical_sheet_path);
            $validated['technical_sheet_path'] = $this->storeSingleFile($request, 'technical_sheet', 'documents/auction-products');
        }

        if ($request->hasFile('terms')) {
            $this->deleteFile($auctionProduct->terms_path);
            $validated['terms_path'] = $this->storeSingleFile($request, 'terms', 'documents/auction-products');
        }

        if ($request->hasFile('images')) {
            foreach ($auctionProduct->images ?? [] as $image) {
                $this->deleteFile($image);
            }
            $validated['images'] = $this->storeMultipleFiles($request, 'images', 'images/auction-products');
        }

        $auctionProduct->update($validated);

        return redirect()->route('auction-products.index')->with('success', 'Producto a subastar actualizado correctamente.');
    }

    public function destroy(Request $request, AuctionProduct $auctionProduct)
    {
        $this->authorizeOwner($request, $auctionProduct);

        $this->deleteFile($auctionProduct->technical_sheet_path);
        $this->deleteFile($auctionProduct->terms_path);
        foreach ($auctionProduct->images ?? [] as $image) {
            $this->deleteFile($image);
        }
        $auctionProduct->delete();

        return response()->json(['message' => 'Producto a subastar eliminado correctamente.']);
    }

    private function formData(Request $request, AuctionProduct $product): array
    {
        $categories = Category::with('subcategories')->orderBy('name')->get();
        $subcategories = $product->category_id
            ? Subcategory::where('category_id', $product->category_id)->orderBy('name')->get()
            : collect();
        $auctioneers = $this->isAdmin($request->user())
            ? User::where('user_type', User::TYPE_AUCTIONEER)->orderBy('name')->get()
            : collect([$request->user()]);

        return [
            'product' => $product,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'auctioneers' => $auctioneers,
            'productTypes' => AuctionProduct::productTypes(),
            'isAdmin' => $this->isAdmin($request->user()),
        ];
    }

    private function validateProduct(Request $request, ?AuctionProduct $product = null): array
    {
        return $request->validate([
            'auctioneer_id' => [Rule::requiredIf($this->isAdmin($request->user())), 'nullable', 'exists:users,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'subcategory_id' => [
                'nullable',
                Rule::exists('subcategories', 'id')->where(fn ($query) => $query->where('category_id', $request->input('category_id'))),
            ],
            'name' => ['required', 'string', 'max:255'],
            'auction_start_date' => ['required', 'date'],
            'auction_end_date' => ['required', 'date', 'after_or_equal:auction_start_date'],
            'auction_start_time' => ['required', 'date_format:H:i'],
            'auction_end_time' => ['required', 'date_format:H:i'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'technical_sheet' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:10240'],
            'terms' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'product_type' => ['required', Rule::in(AuctionProduct::productTypes())],
            'product_details' => ['nullable', 'array'],
            'product_details.*' => ['nullable', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'mandatory_visit' => ['required', 'boolean'],
            'quantity' => ['required', 'integer', 'min:1'],
            'detail' => ['required', 'string'],
            'images' => [$product ? 'nullable' : 'required', 'array'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
    }

    private function resolveAuctioneerId(Request $request, array $validated): int
    {
        if ($this->isAdmin($request->user())) {
            return (int) $validated['auctioneer_id'];
        }

        return $request->user()->id;
    }

    private function authorizeOwner(Request $request, AuctionProduct $product): void
    {
        abort_unless($this->isAdmin($request->user()) || $product->auctioneer_id === $request->user()->id, 403);
    }

    private function isAdmin(User $user): bool
    {
        return $user->user_type === User::TYPE_ADMIN || $user->hasRole(User::TYPE_ADMIN);
    }

    private function isAuctioneer(User $user): bool
    {
        return $user->user_type === User::TYPE_AUCTIONEER || $user->hasRole(User::TYPE_AUCTIONEER);
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 1;

        while (AuctionProduct::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function storeSingleFile(Request $request, string $field, string $directory): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        return $this->storeUploadedFile($request->file($field), $directory);
    }

    private function storeMultipleFiles(Request $request, string $field, string $directory): array
    {
        if (! $request->hasFile($field)) {
            return [];
        }

        return collect($request->file($field))
            ->map(fn ($file) => $this->storeUploadedFile($file, $directory))
            ->all();
    }

    private function storeUploadedFile($file, string $directory): string
    {
        $target = public_path($directory);
        if (! File::exists($target)) {
            File::makeDirectory($target, 0755, true);
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($target, $filename);

        return $directory . '/' . $filename;
    }

    private function deleteFile(?string $path): void
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}

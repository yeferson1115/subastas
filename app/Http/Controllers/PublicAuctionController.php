<?php

namespace App\Http\Controllers;

use App\Models\AuctionBid;
use App\Models\AuctionProduct;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicAuctionController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::with('subcategories')->orderBy('name')->get();
        $selectedCategory = $request->query('categoria');
        $selectedSubcategory = $request->query('subcategoria');

        $auctions = AuctionProduct::query()
            ->with(['category', 'subcategory', 'highestBid'])
            ->when($selectedCategory, fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $selectedCategory)))
            ->when($selectedSubcategory, fn ($query) => $query->whereHas('subcategory', fn ($subcategory) => $subcategory->where('slug', $selectedSubcategory)))
            ->latest('auction_start_date')
            ->paginate(12)
            ->withQueryString();

        return view('public.auctions.index', compact('auctions', 'categories', 'selectedCategory', 'selectedSubcategory'));
    }

    public function show(AuctionProduct $auctionProduct): View
    {
        $auctionProduct->load(['auctioneer', 'category', 'subcategory', 'bids.user']);

        return view('public.auctions.show', ['auction' => $auctionProduct]);
    }

    public function bid(Request $request, AuctionProduct $auctionProduct): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && $this->isBidder($user), 403);
        abort_unless($auctionProduct->is_active, 422, 'Esta subasta no se encuentra activa.');

        $highestBid = (float) ($auctionProduct->bids()->max('amount') ?? $auctionProduct->base_price);
        $minimumBid = $highestBid + 1;

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:' . $minimumBid],
            'comment' => ['nullable', 'string', 'max:500'],
        ], [
            'amount.min' => 'La oferta debe ser superior a la oferta actual. Valor mínimo: $' . number_format($minimumBid, 0, ',', '.'),
        ]);

        AuctionBid::create([
            'auction_product_id' => $auctionProduct->id,
            'user_id' => $user->id,
            'amount' => $data['amount'],
            'comment' => $data['comment'] ?? null,
        ]);

        return redirect()->route('public.auctions.show', $auctionProduct->slug)->with('status', 'Tu oferta fue registrada correctamente.');
    }

    private function isBidder(User $user): bool
    {
        return $user->user_type === User::TYPE_BIDDER || $user->hasRole(User::TYPE_BIDDER);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\AuctionProduct;
use App\Models\User;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeAuctionModule($request->user());

        $status = $request->query('status', 'active');
        $allowedStatuses = ['active', 'finished', 'scheduled', 'all'];

        if (! in_array($status, $allowedStatuses, true)) {
            $status = 'active';
        }

        $query = AuctionProduct::with(['auctioneer', 'category', 'subcategory', 'bids.user'])->latest();
        $this->scopeVisibleAuctions($query, $request->user());
        $this->applyStatusFilter($query, $status);

        $auctions = $query->paginate(10)->withQueryString();
        $counts = $this->statusCounts($request->user());

        return view('admin.auctions.index', compact('auctions', 'status', 'counts'));
    }

    public function show(Request $request, AuctionProduct $auction)
    {
        $this->authorizeAuctionModule($request->user());
        $this->authorizeVisibleAuction($request->user(), $auction);

        $auction->load(['auctioneer', 'category', 'subcategory', 'bids.user']);

        return view('admin.auctions.show', compact('auction'));
    }

    private function scopeVisibleAuctions($query, User $user): void
    {
        if (! $this->isAdmin($user)) {
            $query->where('auctioneer_id', $user->id);
        }
    }

    private function applyStatusFilter($query, string $status): void
    {
        $nowDate = now()->toDateString();
        $nowTime = now()->format('H:i');

        if ($status === 'active') {
            $query->where(function ($query) use ($nowDate, $nowTime) {
                $query->where('auction_start_date', '<', $nowDate)
                    ->orWhere(function ($query) use ($nowDate, $nowTime) {
                        $query->where('auction_start_date', $nowDate)
                            ->where('auction_start_time', '<=', $nowTime);
                    });
            })->where(function ($query) use ($nowDate, $nowTime) {
                $query->where('auction_end_date', '>', $nowDate)
                    ->orWhere(function ($query) use ($nowDate, $nowTime) {
                        $query->where('auction_end_date', $nowDate)
                            ->where('auction_end_time', '>=', $nowTime);
                    });
            });
        }

        if ($status === 'finished') {
            $query->where(function ($query) use ($nowDate, $nowTime) {
                $query->where('auction_end_date', '<', $nowDate)
                    ->orWhere(function ($query) use ($nowDate, $nowTime) {
                        $query->where('auction_end_date', $nowDate)
                            ->where('auction_end_time', '<', $nowTime);
                    });
            });
        }

        if ($status === 'scheduled') {
            $query->where(function ($query) use ($nowDate, $nowTime) {
                $query->where('auction_start_date', '>', $nowDate)
                    ->orWhere(function ($query) use ($nowDate, $nowTime) {
                        $query->where('auction_start_date', $nowDate)
                            ->where('auction_start_time', '>', $nowTime);
                    });
            });
        }
    }

    private function statusCounts(User $user): array
    {
        $baseQuery = AuctionProduct::query();
        $this->scopeVisibleAuctions($baseQuery, $user);

        return collect(['active', 'finished', 'scheduled', 'all'])
            ->mapWithKeys(function (string $status) use ($baseQuery) {
                $query = clone $baseQuery;

                if ($status !== 'all') {
                    $this->applyStatusFilter($query, $status);
                }

                return [$status => $query->count()];
            })
            ->all();
    }

    private function authorizeVisibleAuction(User $user, AuctionProduct $auction): void
    {
        abort_unless($this->isAdmin($user) || $auction->auctioneer_id === $user->id, 403);
    }

    private function authorizeAuctionModule(User $user): void
    {
        abort_unless($this->isAdmin($user) || $this->isAuctioneer($user), 403);
    }

    private function isAdmin(User $user): bool
    {
        return $user->user_type === User::TYPE_ADMIN || $user->hasRole(User::TYPE_ADMIN);
    }

    private function isAuctioneer(User $user): bool
    {
        return $user->user_type === User::TYPE_AUCTIONEER || $user->hasRole(User::TYPE_AUCTIONEER);
    }
}

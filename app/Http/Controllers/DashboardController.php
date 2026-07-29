<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuctionProduct;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAuctions = AuctionProduct::count();
        $totalBidders = User::query()
            ->where('user_type', User::TYPE_BIDDER)
            ->orWhereHas('roles', function ($query) {
                $query->where('name', User::TYPE_BIDDER);
            })
            ->count();

        return view('admin.home.dashboard', compact(
            'totalAuctions',
            'totalBidders'
        ));
    }

    
}

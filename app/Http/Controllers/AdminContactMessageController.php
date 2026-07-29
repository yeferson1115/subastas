<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($this->isAdmin($request->user()), 403);

        $messages = ContactMessage::latest()->paginate(15);

        return view('admin.contact-messages.index', compact('messages'));
    }

    private function isAdmin($user): bool
    {
        return $user?->user_type === \App\Models\User::TYPE_ADMIN
            || $user?->hasRole(\App\Models\User::TYPE_ADMIN);
    }
}

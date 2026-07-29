<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPageController extends Controller
{
    public function contact(): View
    {
        return view('public.pages.contact', [
            'categories' => $this->categories(),
        ]);
    }

    public function storeContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['required', 'string', 'max:180'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ContactMessage::create($data);

        return redirect()
            ->route('public.contact')
            ->with('status', 'Gracias por contactarnos. Hemos recibido tu mensaje y pronto nuestro equipo se comunicará contigo.');
    }

    public function terms(): View
    {
        return view('public.pages.terms', [
            'categories' => $this->categories(),
        ]);
    }

    public function privacy(): View
    {
        return view('public.pages.privacy', [
            'categories' => $this->categories(),
        ]);
    }

    private function categories()
    {
        return Category::with('subcategories')->orderBy('name')->get();
    }
}

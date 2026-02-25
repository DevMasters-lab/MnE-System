<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Card; 
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function dashboard()
    {
        $menus = Menu::orderBy('order_no')->get();

        return view('dashboard', compact('menus'));
    }
    
    public function index()
    {
        $menus = Menu::orderBy('order_no')->get();
        return view('menu_options.index', compact('menus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order_no' => 'required|integer',
            'type' => 'required|in:Sub(Card),Embedded URL',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $data = $request->only(['name', 'order_no', 'type', 'url']);

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('menu_icons', 'public');
            $data['icon_path'] = $path;
        }

        Menu::create($data);
        return back()->with('success', 'Menu option added successfully.');
    }
    
    public function show(Menu $menu)
    {
        if ($menu->type === 'Sub(Card)') {
            $cards = $menu->cards()->orderBy('order_no')->get();
            return view('menus.subcard', compact('menu', 'cards'));
        }
        
        return view('menus.embedded', compact('menu'));
    }

    public function edit(Menu $menu)
    {
        return response()->json($menu);
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order_no' => 'required|integer',
            'type' => 'required',
            'icon' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'order_no', 'type', 'url']);

        if ($request->hasFile('icon')) {
            if ($menu->icon_path) { Storage::disk('public')->delete($menu->icon_path); }
            $data['icon_path'] = $request->file('icon')->store('menu_icons', 'public');
        }

        $menu->update($data);
        return back()->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->icon_path) { Storage::disk('public')->delete($menu->icon_path); }
        $menu->delete();
        return back()->with('success', 'Menu deleted successfully.');
    }

    // --- SUB-CARD LOGIC ---

    public function storeCard(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'link_url' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
            'order_no' => 'required|integer'
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('card_images', 'public');
        }

        $menu->cards()->create($data);
        return back()->with('success', 'Card added successfully.');
    }

    public function destroyCard(Card $card)
    {
        if ($card->image_path) { Storage::disk('public')->delete($card->image_path); }
        $card->delete();
        return back()->with('success', 'Card deleted successfully.');
    }

    public function editCard(Card $card)
    {
        return response()->json($card);
    }

    public function updateCard(Request $request, Card $card)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'link_url' => 'nullable|url',
            'image' => 'nullable|image|max:2048',
            'order_no' => 'required|integer'
        ]);

        if ($request->hasFile('image')) {
            // Delete the old image file to keep storage clean
            if ($card->image_path) { Storage::disk('public')->delete($card->image_path); }
            $data['image_path'] = $request->file('image')->store('card_images', 'public');
        }

        $card->update($data);
        return back()->with('success', 'Card updated successfully.');
    }

    
}
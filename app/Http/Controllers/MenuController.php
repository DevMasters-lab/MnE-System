<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Card; 
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuController extends Controller
{
    private function clearPermissionCache()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function dashboard()
    {
        $allMenus = Menu::orderBy('order_no')->get();

        $menus = $allMenus->filter(function ($menu) {
            return auth()->user()->can(strtoupper(trim($menu->name)));
        });

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

        $menu = Menu::create($data);

        $permission = Permission::firstOrCreate([
            'name' => strtoupper(trim($menu->name)),
            'guard_name' => 'web'
        ]);

        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permission);
        }

        $this->clearPermissionCache();

        return back()->with('success', 'Menu option added successfully.');
    }

    public function show(Menu $menu_option)
    {
        $permissionName = strtoupper(trim($menu_option->name));

        if (!auth()->user()->can($permissionName)) {
            abort(403, 'Unauthorized access to ' . $permissionName);
        }

        if ($menu_option->type === 'Sub(Card)') {
            $cards = $menu_option->cards()->orderBy('order_no')->get();
            return view('menus.subcard', ['menu' => $menu_option, 'cards' => $cards]);
        }

        $url = $menu_option->url;
        $isYouTube = false;

        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match)) {
            $url = "https://www.youtube.com/embed/" . $match[1];
            $isYouTube = true;
        }

        return view('menus.embedded', [
            'menu' => $menu_option,
            'embedUrl' => $url,
            'isYouTube' => $isYouTube
        ]);
    }

    public function edit(Menu $menu_option) 
    {
        return response()->json($menu_option);
    }

    public function update(Request $request, Menu $menu_option)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order_no' => 'required|integer',
            'type' => 'required',
            'icon' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'order_no', 'type', 'url']);

        if ($request->type === 'Sub(Card)') {
            $data['url'] = null;
        }

        if ($request->hasFile('icon')) {
            if ($menu_option->icon_path) { 
                Storage::disk('public')->delete($menu_option->icon_path); 
            }
            $data['icon_path'] = $request->file('icon')->store('menu_icons', 'public');
        }

        $oldName = strtoupper(trim($menu_option->name));
        $newName = strtoupper(trim($request->name));

        $menu_option->update($data);

        if ($oldName !== $newName) {
            $permission = Permission::where('name', $oldName)->first();
            if ($permission) {
                $permission->update(['name' => $newName]);
            } else {
                $permission = Permission::firstOrCreate(['name' => $newName, 'guard_name' => 'web']);
                $adminRole = Role::where('name', 'Admin')->first();
                if ($adminRole) { $adminRole->givePermissionTo($permission); }
            }
            // Clear cache after renaming
            $this->clearPermissionCache();
        }

        return redirect()->route('menus.index')->with('success', 'Menu updated successfully.');
    }

    public function destroy(Menu $menu_option)
    {
        $permission = Permission::where('name', strtoupper(trim($menu_option->name)))->first();
        if ($permission) {
            $permission->delete();
        }

        if ($menu_option->icon_path) { Storage::disk('public')->delete($menu_option->icon_path); }
        $menu_option->delete();

        $this->clearPermissionCache();
        
        return back()->with('success', 'Menu deleted successfully.');
    }

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
            if ($card->image_path) { Storage::disk('public')->delete($card->image_path); }
            $data['image_path'] = $request->file('image')->store('card_images', 'public');
        }

        $card->update($data);
        return back()->with('success', 'Card updated successfully.');
    }
}
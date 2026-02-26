<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the roles and handle Edit Mode.
     */
    public function index(Request $request)
    {
        // 1. Ensure core system permissions always exist
        $defaultPermissions = ['DASHBOARD', 'MANAGE USER', 'MENU OPTION', 'MANAGE ROLE'];

        $adminRole = Role::where('name', 'Admin')->first();

        foreach ($defaultPermissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => 'web'
            ]);
        }

        // 2. Force Admin to sync with ALL permissions (System + Dynamic Menus)
        // This ensures Admin always has 100% access without manual checking
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
        }

        // 3. Fetch data for the UI
        $permissions = Permission::all();
        $roles = Role::with('permissions')->latest()->paginate(10);
        
        $role = null;
        if ($request->has('edit')) {
            $role = Role::find($request->edit);
        }

        return view('admin.roles.index', compact('roles', 'role', 'permissions'));
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);

        if ($request->has('permissions')) {
            // Safety check: ensure permission rows exist before syncing
            foreach ($request->permissions as $permissionName) {
                Permission::firstOrCreate([
                    'name' => $permissionName, 
                    'guard_name' => 'web'
                ]);
            }
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role created and linked!');
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array'
        ]);

        $role->update(['name' => $request->name]);

        if ($request->has('permissions')) {
            foreach ($request->permissions as $permissionName) {
                Permission::firstOrCreate([
                    'name' => $permissionName, 
                    'guard_name' => 'web'
                ]);
            }
            $role->syncPermissions($request->permissions);
        } else {
            // If no checkboxes are selected, remove all permissions from this role
            $role->syncPermissions([]);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Safety: Prevent accidental lockout by protecting the Admin role
        if($role->name === 'Admin') {
            return redirect()->back()->with('error', 'The Master Admin role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
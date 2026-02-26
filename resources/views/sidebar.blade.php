<aside class="w-64 bg-[#232936] text-white flex flex-col h-screen fixed left-0 top-0 shadow-xl">
    <div class="p-6 flex items-center gap-3">
        <div class="flex-shrink-0">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto rounded-md object-contain">
        </div>
        <div class="min-w-0">
            <h1 class="text-xl font-bold tracking-wide leading-tight truncate text-gray-100">MnE System</h1>
        </div>
    </div>
    
    <div class="border-t border-gray-700 flex flex-col flex-1 overflow-hidden">
        <nav class="flex-1 px-4 mt-6 space-y-2 overflow-y-auto custom-scrollbar">
            
            {{-- Overview Link --}}
            @can('OVERVIEW')
            <a href="{{ route('overview') }}" 
            class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('overview') ? 'bg-[#2a3142] border border-[#4f70ce] text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-md transition group">
                <svg class="w-5 h-5 {{ request()->routeIs('overview') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-sm font-medium">Overview</span>
            </a>
            @endcan

            <div class="border-t border-gray-700 my-2 opacity-50"></div>

            {{-- Dynamic Menu Items --}}
            @foreach(\App\Models\Menu::orderBy('order_no')->get() as $menu)
                @can(strtoupper($menu->name))
                {{-- FIX: Changed $menu->id to $menu->name below --}}
                <a href="{{ route('menus.show', $menu->name) }}" 
                   class="flex items-center gap-3 px-4 py-3 {{ request()->fullUrlIs(route('menus.show', $menu->name)) ? 'bg-[#2a3142] border border-[#4f70ce] text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-md transition group">
                    
                    @if($menu->icon_path)
                        <img src="{{ asset('storage/' . $menu->icon_path) }}" class="w-5 h-5 opacity-70 group-hover:opacity-100 object-contain transition-opacity">
                    @else
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                        </svg>
                    @endif
                    
                    <span class="text-sm font-medium">{{ $menu->name }}</span>
                </a>
                @endcan
            @endforeach
        </nav>

        <div class="px-4 pb-6 space-y-2">
            {{-- Administrative Section: Only shows if user has at least one of these permissions --}}
            @if(auth()->user()->can('MANAGE USER') || auth()->user()->can('MENU OPTION') || auth()->user()->can('MANAGE ROLE'))
            <div class="border-t border-gray-700 pt-4 space-y-2">
                
                @can('MANAGE USER')
                <a href="{{ route('users.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('users.index') ? 'bg-[#2a3142] border border-[#4f70ce]' : 'text-gray-300 hover:text-white hover:bg-white/5' }} rounded-md transition">
                    <svg class="w-5 h-5 {{ request()->routeIs('users.index') ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-sm font-medium">Manage Users</span>
                </a>
                @endcan

                @can('MENU OPTION')
                <a href="{{ route('menus.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('menus.index') ? 'bg-[#2a3142] border border-[#4f70ce]' : 'text-gray-300 hover:text-white hover:bg-white/5' }} rounded-md transition group">
                    <svg class="w-5 h-5 {{ request()->routeIs('menus.index') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span class="text-sm font-medium">Menu Options</span>
                </a>
                @endcan

                @can('MANAGE ROLE')
                <a href="{{ route('roles.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('roles.*') ? 'bg-[#2a3142] border border-[#4f70ce]' : 'text-gray-300 hover:text-white hover:bg-white/5' }} rounded-md transition group">
                    <svg class="w-5 h-5 {{ request()->routeIs('roles.*') ? 'text-white' : 'text-gray-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span class="text-sm font-medium">Manage Roles</span>
                </a>
                @endcan
            </div>
            @endif

            {{-- Logout Section: Always visible --}}
            <div class="border-t border-gray-700 pt-4 space-y-2">
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-[#e06c6c] hover:text-red-400 hover:bg-red-500/5 transition group rounded-md">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="text-sm font-medium">Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
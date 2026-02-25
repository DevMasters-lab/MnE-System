@extends('layouts.app')

@section('content')
<div class="p-8 max-w-7xl mx-auto">
    {{-- Header --}}
    <header class="mb-10 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">
                Welcome back, <span class="text-[#4f70ce]">{{ Auth::user()->name }}</span>
            </h1>
        </div>
    </header>

    {{-- Stats Row: Different Style from Modules --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-[#4f70ce] rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Modules</p>
                <h2 class="text-2xl font-black text-slate-800">{{ $menus->count() }}</h2>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-green-50 text-green-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Active Users</p>
                <h2 class="text-2xl font-black text-slate-800">{{ \App\Models\User::count() }}</h2>
            </div>
        </div>

        <div class="bg-gradient-to-br from-[#4f70ce] to-[#3b549a] p-6 rounded-2xl shadow-lg shadow-blue-200 flex items-center gap-4">
            <div class="p-3 bg-white/20 text-white rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>
            <div>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest">Your Role</p>
                <h2 class="text-2xl font-black text-white capitalize">{{ Auth::user()->role }}</h2>
            </div>
        </div>
    </div>

    {{-- Module Section Heading --}}
    <div class="flex items-center gap-4 mb-8">
        <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em]">Application Modules</h2>
        <div class="h-[1px] flex-1 bg-slate-200"></div>
    </div>

    {{-- Interactive Module Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($menus as $menu)
            <a href="{{ route('menus.show', $menu->id) }}" 
               class="group relative bg-[#232936] p-8 rounded-3xl border border-transparent hover:border-[#4f70ce] transition-all duration-300 shadow-xl hover:-translate-y-2">
                
                {{-- Floating Arrow --}}
                <div class="absolute top-6 right-6 text-white/0 group-hover:text-white/50 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
                </div>

                <div class="w-14 h-14 bg-slate-700/50 rounded-2xl flex items-center justify-center group-hover:bg-[#4f70ce] transition-all mb-6 shadow-inner">
                    <svg class="w-7 h-7 text-[#4f70ce] group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </div>
                
                <h3 class="text-xl font-bold text-white mb-2 group-hover:text-[#4f70ce] transition-colors">{{ $menu->name }}</h3>
                <p class="text-slate-400 text-sm leading-relaxed">
                    Click to access management tools and system data for {{ $menu->name }}.
                </p>
            </a>
        @endforeach
    </div>
</div>
@endsection
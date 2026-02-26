@extends('layouts.app')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <header class="mb-10 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">
                Welcome back, <span class="text-[#4f70ce]">{{ Auth::user()->name }}</span>
            </h1>
        </div>
    </header>

    {{-- Stats Row: Customized for User View --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        {{-- Stat 1: Available Modules --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-blue-50 text-[#4f70ce] rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Your Modules</p>
                <h2 class="text-2xl font-black text-slate-800">
                    {{ $menus->filter(fn($m) => auth()->user()->can(strtoupper($m->name)))->count() }}
                </h2>
            </div>
        </div>

        {{-- Stat 2: REPLACED 'System Users' with 'Active Session' or 'System Status' --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Security Status</p>
                <h2 class="text-2xl font-black text-slate-800">Verified</h2>
            </div>
        </div>

        {{-- Stat 3: Role Display --}}
        <div class="bg-gradient-to-br from-[#4f70ce] to-[#3b549a] p-6 rounded-2xl shadow-lg shadow-blue-200 flex items-center gap-4">
            <div class="p-3 bg-white/20 text-white rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
            <div>
                <p class="text-blue-100 text-xs font-bold uppercase tracking-widest">Assigned Role</p>
                <h2 class="text-2xl font-black text-white capitalize">
                    {{ Auth::user()->roles->first()->name ?? 'User' }}
                </h2>
            </div>
        </div>
    </div>

    {{-- Module Section Heading --}}
    <div class="flex items-center gap-4 mb-8">
        <h2 class="text-sm font-black text-slate-400 uppercase tracking-[0.2em]">Your Authorized Modules</h2>
        <div class="h-[1px] flex-1 bg-slate-200"></div>
    </div>

    {{-- Interactive Module Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($menus as $menu)
            @can(strtoupper($menu->name))
                <a href="{{ route('menus.show', $menu->id) }}" 
                class="group relative bg-[#232936] p-8 rounded-3xl border border-transparent hover:border-[#4f70ce] transition-all duration-300 shadow-xl hover:-translate-y-2">
                    
                    <div class="w-14 h-14 bg-slate-700/50 rounded-2xl flex items-center justify-center group-hover:bg-[#4f70ce] transition-all mb-6 shadow-inner">
                        @if($menu->icon_path)
                            <img src="{{ asset('storage/' . $menu->icon_path) }}" class="w-7 h-7 object-contain group-hover:scale-110 transition-transform">
                        @else
                            <svg class="w-7 h-7 text-[#4f70ce] group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-[#4f70ce] transition-colors">{{ $menu->name }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        Click to access tools for {{ $menu->name }}.
                    </p>
                </a>
            @endcan
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-slate-400 italic">No modules have been assigned to your account yet.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
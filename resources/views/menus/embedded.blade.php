@extends('layouts.app')

@section('content')
<div class="p-6 h-screen flex flex-col overflow-hidden">
    <div class="flex items-center justify-between mb-6 px-2">
        <div>
            <h2 class="text-2xl font-bold text-[#1e2336]">{{ $menu->name }}</h2>
            <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-[0.2em]">External Resource View</p>
        </div>
        
        <a href="{{ $menu->url }}" target="_blank" class="text-[11px] font-bold text-blue-500 hover:text-blue-700 transition uppercase tracking-widest flex items-center gap-2">
            Open in New Tab
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
        </a>
    </div>

    <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden relative mb-4">
        <div id="iframeLoader" class="absolute inset-0 flex items-center justify-center bg-gray-50 z-10">
            <div class="flex flex-col items-center gap-3">
                <div class="w-10 h-10 border-4 border-blue-100 border-t-blue-500 rounded-full animate-spin"></div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Loading Resource...</p>
            </div>
        </div>

        <iframe 
            id="resourceFrame"
            src="{{ $menu->url }}" 
            class="w-full h-full border-none shadow-inner" 
            allowfullscreen 
            loading="lazy"
            referrerpolicy="no-referrer"
            sandbox="allow-forms allow-scripts allow-same-origin allow-popups"
            onload="document.getElementById('iframeLoader').classList.add('hidden')"
        ></iframe>
    </div>
</div>

<style>
    /* Prevent the main app from scrolling when the iframe is active */
    body { overflow: hidden; }
</style>
@endsection
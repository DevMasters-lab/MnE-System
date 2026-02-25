@extends('layouts.app')

@section('content')
<div class="p-6">
    {{-- Header Section --}}
    <div class="flex items-center justify-between mb-8 w-full px-2">
        <div>
            <h2 class="text-2xl font-bold text-[#1e2336]">Menu Options Configuration</h2>
            <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-[0.2em]">Navigation Setup & Hierarchy</p>
        </div>
        
        <button onclick="openModal()" 
                class="bg-[#3b6df0] hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-sm font-semibold shadow-md transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add Menu Option
        </button>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-[#232936] text-white">
                <tr class="text-[11px] uppercase tracking-[0.2em]">
                    <th class="px-8 py-5 font-bold">Icon</th>
                    <th class="px-8 py-5 font-bold">Menu Name</th>
                    <th class="px-8 py-5 font-bold">Type</th>
                    <th class="px-8 py-5 font-bold">Order</th>
                    <th class="px-8 py-5 font-bold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($menus as $menu)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-8 py-6">
                        <div class="bg-gray-50 p-1.5 rounded-md border border-gray-100 inline-block shadow-sm">
                            @if($menu->icon_path)
                                <img src="{{ asset('storage/' . $menu->icon_path) }}" class="w-7 h-7 object-contain">
                            @else
                                <div class="w-7 h-7 flex items-center justify-center text-gray-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-8 py-6 font-bold text-[#1e2336]">{{ $menu->name }}</td>
                    <td class="px-8 py-6">
                        <span class="px-3 py-1 text-[9px] font-bold rounded-full {{ $menu->type == 'Embedded URL' ? 'bg-purple-50 text-purple-600 border border-purple-100' : 'bg-blue-50 text-blue-600 border border-blue-100' }} uppercase tracking-wider">
                            {{ $menu->type }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-gray-500 font-medium">{{ $menu->order_no }}</td>
                    <td class="px-8 py-6">
                        <div class="flex items-center justify-center gap-5">
                            <button onclick="openEditModal({{ $menu->id }})" class="text-blue-500 hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            
                            <form action="{{ route('menus.destroy', $menu->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this menu option?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-16 text-center text-gray-400 italic font-medium">No menu options configured yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Unified Add/Edit Modal --}}
<div id="addMenuModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div id="modalBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>

    <div id="modalCard" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform scale-95 opacity-0 transition-all duration-300 overflow-hidden">
        <div class="flex justify-between items-center px-10 py-6 border-b border-gray-50 bg-[#f8fafc]">
            <div>
                <h3 id="modalTitle" class="text-xl font-bold text-[#1e2336]">Add Menu Option</h3>
                <p id="modalSubtitle" class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-[0.2em]">Navigation Configuration</p>
            </div>
            <button onclick="closeModal()" class="w-10 h-10 rounded-full hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition text-gray-400">✕</button>
        </div>

        <form id="menuForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div id="methodField"></div> 
            
            <div class="p-10 space-y-8">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Menu Name</label>
                    <input type="text" name="name" id="menu_name" required
                           class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all placeholder:text-gray-300">
                </div>

                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Order Number</label>
                        <input type="number" name="order_no" id="menu_order" value="1"
                               class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Menu Type</label>
                        <select name="type" id="typeSelector" required
                                class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition bg-white cursor-pointer">
                            <option value="Sub(Card)">Sub(Card)</option>
                            <option value="Embedded URL">Embedded URL</option>
                        </select>
                    </div>
                </div>

                <div id="urlField" class="hidden">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Target URL</label>
                    <input type="text" name="url" id="menu_url"
                           class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition"
                           placeholder="https://example.com/external-link">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Menu Icon Asset</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-10 text-center hover:border-blue-300 hover:bg-blue-50/50 transition cursor-pointer relative group">
                        {{-- Added onchange for preview --}}
                        <input type="file" name="icon" onchange="previewImage(this)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-3">
                            <div class="bg-blue-50 w-14 h-14 rounded-full flex items-center justify-center mx-auto text-blue-500 transition-transform group-hover:scale-110">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <p class="text-sm text-gray-600 font-bold">Select Icon to Upload</p>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">PNG, JPG, or SVG (Max 2MB)</p>
                        </div>
                    </div>

                    {{-- Image Preview Container --}}
                    <div id="previewContainer" class="hidden mt-6 p-4 border border-gray-100 rounded-xl bg-[#f8fafc] flex items-center gap-4 animate-fade-in">
                        <div class="w-16 h-16 bg-white rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden shadow-sm">
                            <img id="imagePreview" src="#" alt="Preview" class="w-full h-full object-contain p-2">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-[#1e2336] truncate" id="fileNameDisplay"></p>
                            <button type="button" onclick="removeSelectedImage()" class="text-[9px] font-bold text-red-500 uppercase tracking-wider mt-1 hover:underline">Remove Image</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-6 px-10 py-6 border-t border-gray-50 bg-[#fbfbfb] rounded-b-2xl">
                <button type="button" onclick="closeModal()" 
                        class="text-[11px] font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-[0.2em]">
                    Cancel
                </button>

                <button type="submit" id="submitBtn"
                        class="px-12 py-4 bg-[#1e2336] hover:bg-black text-white text-[11px] font-bold rounded-xl shadow-xl shadow-blue-900/10 hover:-translate-y-0.5 active:scale-95 transition-all uppercase tracking-[0.2em]">
                    Save Menu
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('addMenuModal');
    const backdrop = document.getElementById('modalBackdrop');
    const card = document.getElementById('modalCard');
    const menuForm = document.getElementById('menuForm');
    const typeSelector = document.getElementById('typeSelector');
    const urlField = document.getElementById('urlField');

    // Image Preview Logic
    window.previewImage = function(input) {
        const container = document.getElementById('previewContainer');
        const preview = document.getElementById('imagePreview');
        const fileName = document.getElementById('fileNameDisplay');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                fileName.innerText = input.files[0].name;
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    window.removeSelectedImage = function() {
        const fileInput = document.querySelector('input[name="icon"]');
        fileInput.value = '';
        document.getElementById('previewContainer').classList.add('hidden');
    }

    window.openModal = function() {
        resetForm();
        document.getElementById('modalTitle').innerText = 'Add Menu Option';
        menuForm.action = "{{ route('menus.store') }}";
        document.getElementById('methodField').innerHTML = '';
        document.getElementById('submitBtn').innerText = 'Save Menu';
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.replace('opacity-0', 'opacity-100');
            card.classList.replace('opacity-0', 'opacity-100');
            card.classList.replace('scale-95', 'scale-100');
        }, 10);
    }

    window.openEditModal = function(id) {
        resetForm();
        fetch(`/menu-options/${id}/edit`)
            .then(res => res.json())
            .then(data => {
                document.getElementById('modalTitle').innerText = 'Edit Menu Option';
                document.getElementById('menu_name').value = data.name;
                document.getElementById('menu_order').value = data.order_no;
                document.getElementById('typeSelector').value = data.type;
                document.getElementById('menu_url').value = data.url || '';
                
                if(data.type === 'Embedded URL') {
                    urlField.classList.remove('hidden');
                } else {
                    urlField.classList.add('hidden');
                }

                menuForm.action = `/menu-options/${id}`;
                document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
                document.getElementById('submitBtn').innerText = 'Update Menu';

                modal.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.replace('opacity-0', 'opacity-100');
                    card.classList.replace('opacity-0', 'opacity-100');
                    card.classList.replace('scale-95', 'scale-100');
                }, 10);
            })
            .catch(error => console.error('Error fetching data:', error));
    }

    window.closeModal = function() {
        backdrop.classList.replace('opacity-100', 'opacity-0');
        card.classList.replace('opacity-100', 'opacity-0');
        card.classList.replace('scale-100', 'scale-95');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function resetForm() {
        menuForm.reset();
        urlField.classList.add('hidden');
        removeSelectedImage(); // Clear preview on reset
    }

    backdrop.addEventListener('click', closeModal);

    typeSelector.addEventListener('change', function () {
        this.value === 'Embedded URL' ? urlField.classList.remove('hidden') : urlField.classList.add('hidden');
    });
</script>
@endsection
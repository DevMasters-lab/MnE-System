@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-10 w-full px-2">
        <div>
            <h2 class="text-2xl font-bold text-[#1e2336]">{{ $menu->name }}</h2>
        </div>
        
        <button onclick="openCardModal()" 
                class="bg-[#3b6df0] hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-[11px] font-bold shadow-md transition-all active:scale-95 flex items-center gap-2 uppercase tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Card
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($cards as $card)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden flex flex-col">
                
                <div class="absolute top-3 right-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                    <button onclick="openEditCardModal({{ $card->id }})" class="p-2 bg-white/90 backdrop-blur-sm text-blue-600 rounded-lg shadow-sm hover:bg-blue-600 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <form action="{{ route('cards.destroy', $card->id) }}" method="POST" onsubmit="return confirm('Delete this card?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 bg-white/90 backdrop-blur-sm text-red-600 rounded-lg shadow-sm hover:bg-red-600 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>

                <a href="{{ $card->link_url ?? '#' }}" target="_blank" class="flex flex-col h-full group">
                    <div class="w-full h-56 bg-white border-b border-gray-50 flex items-center justify-center relative overflow-hidden group-hover:opacity-90 transition-opacity">
                        @if($card->image_path)
                            <img src="{{ asset('storage/' . $card->image_path) }}" class="w-full h-full object-cover object-center">
                        @else
                            <div class="flex flex-col items-center opacity-40">
                                <svg class="w-8 h-8 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-6 flex-1 flex flex-col bg-white">
                        <h4 class="text-lg font-bold text-[#1e2336] uppercase tracking-wider mb-2 group-hover:text-blue-600 transition-colors line-clamp-2">
                            {{ $card->title }}
                        </h4>
                        
                        <p class="text-sm text-gray-500 font-medium line-clamp-2 leading-relaxed">
                            {{ $card->short_description }}
                        </p>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full py-20 text-center text-gray-400 font-medium italic bg-white rounded-2xl border border-dashed border-gray-200">
                No cards added yet. Click "Add New Card" to get started.
            </div>
        @endforelse
    </div>
</div>

<div id="editCardModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div onclick="closeEditCardModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all">
        <div class="px-8 py-6 border-b border-gray-100 bg-[#f8fafc] flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-[#1e2336]">Edit Resource Card</h3>
                <p class="text-[9px] text-gray-400 mt-0.5 uppercase font-bold tracking-[0.1em]">Update Details</p>
            </div>
            <button onclick="closeEditCardModal()" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-red-50 hover:text-red-500 transition-colors">✕</button>
        </div>
        <form id="editCardForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
            @csrf @method('PUT')
            
            <div class="space-y-5">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Title</label>
                    <input type="text" name="title" id="edit_title" required class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-4 py-3 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10 outline-none transition">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Short Description</label>
                    <input type="text" name="short_description" id="edit_desc" required class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-4 py-3 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10 outline-none transition">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Link URL</label>
                        <input type="text" name="link_url" id="edit_url" class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-4 py-3 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Order No.</label>
                        <input type="number" name="order_no" id="edit_order" class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-4 py-3 text-sm focus:border-blue-400 focus:ring-2 focus:ring-blue-500/10 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Cover Banner Asset</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-blue-300 hover:bg-blue-50/50 transition cursor-pointer relative group">
                        <input type="file" name="image" id="edit_image_input" onchange="previewEditImage(this)" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-2">
                            <div class="bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center mx-auto text-blue-500 transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <p class="text-[11px] text-gray-600 font-bold">Select New Banner to Upload</p>
                            <p class="text-[9px] text-gray-400 uppercase tracking-widest">Leave empty to keep current image</p>
                        </div>
                    </div>

                    <div id="editPreviewContainer" class="hidden mt-4 p-3 border border-gray-100 rounded-xl bg-[#f8fafc] flex items-center gap-4 animate-fade-in">
                        <div class="w-20 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden shadow-sm">
                            <img id="edit_image_preview" src="#" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-bold text-[#1e2336] truncate" id="editFileNameDisplay">Current Active Banner</p>
                            <button type="button" onclick="removeEditSelectedImage()" class="text-[9px] font-bold text-red-500 uppercase tracking-wider mt-1 hover:underline">Remove Image</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-6 mt-6 border-t border-gray-50">
                <button type="button" onclick="closeEditCardModal()" class="text-[11px] font-bold text-gray-400 hover:text-gray-600 uppercase tracking-widest px-4">Cancel</button>
                <button type="submit" class="bg-[#1e2336] hover:bg-black text-white px-8 py-3.5 rounded-xl font-bold text-[11px] uppercase tracking-widest shadow-xl shadow-gray-200 transition-all active:scale-95">Update Card</button>
            </div>
        </form>
    </div>
</div>

<script>
window.previewEditImage = function(input) {
    const container = document.getElementById('editPreviewContainer');
    const preview = document.getElementById('edit_image_preview');
    const fileName = document.getElementById('editFileNameDisplay');

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

window.removeEditSelectedImage = function() {
    document.getElementById('edit_image_input').value = '';
    document.getElementById('editPreviewContainer').classList.add('hidden');
}

function openEditCardModal(id) {
    fetch(`/cards/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('edit_title').value = data.title;
            document.getElementById('edit_desc').value = data.short_description;
            document.getElementById('edit_url').value = data.link_url || '';
            document.getElementById('edit_order').value = data.order_no;
            document.getElementById('editCardForm').action = `/cards/${id}`;
            
            const previewContainer = document.getElementById('editPreviewContainer');
            const previewImg = document.getElementById('edit_image_preview');
            const fileName = document.getElementById('editFileNameDisplay');
            
            if(data.image_path) {
                previewImg.src = `/storage/${data.image_path}`;
                fileName.innerText = "Current Active Banner";
                previewContainer.classList.remove('hidden');
            } else {
                previewImg.src = '';
                previewContainer.classList.add('hidden');
            }
            
            document.getElementById('edit_image_input').value = ''; 

            document.getElementById('editCardModal').classList.remove('hidden');
        });
}

function closeEditCardModal() {
    document.getElementById('editCardModal').classList.add('hidden');
    document.getElementById('edit_image_input').value = '';
    document.getElementById('editPreviewContainer').classList.add('hidden');
}
</script>

@include('menus.partials.add-card-modal')
@endsection
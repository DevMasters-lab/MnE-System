@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-10 w-full px-2">
        <div>
            <h2 class="text-2xl font-bold text-[#1e2336]">{{ $menu->name }}</h2>
            <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-[0.2em]">Resource Collection</p>
        </div>
        
        <button onclick="openCardModal()" 
                class="bg-[#3b6df0] hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-[11px] font-bold shadow-md transition-all flex items-center gap-2 uppercase tracking-widest">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Card
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @forelse($cards as $card)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative">
                <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="openEditCardModal({{ $card->id }})" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-600 hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </button>
                    <form action="{{ route('cards.destroy', $card->id) }}" method="POST" onsubmit="return confirm('Delete this card?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                </div>

                <div class="p-8 text-center">
                    <div class="bg-[#f8fafc] w-14 h-14 rounded-xl flex items-center justify-center mb-6 mx-auto border border-gray-50 group-hover:bg-blue-50 transition-colors">
                        @if($card->image_path)
                            <img src="{{ asset('storage/' . $card->image_path) }}" class="w-8 h-8 object-contain">
                        @else
                            <span class="text-2xl">📁</span>
                        @endif
                    </div>
                    <a href="{{ $card->link_url ?? '#' }}" target="_blank">
                        <h4 class="text-[13px] font-bold text-[#1e2336] uppercase tracking-wider mb-2">{{ $card->title }}</h4>
                        <p class="text-[11px] text-gray-400 font-medium line-clamp-2">{{ $card->short_description }}</p>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center text-gray-400 italic">No cards added yet.</div>
        @endforelse
    </div>
</div>

<div id="editCardModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div onclick="closeEditCardModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden transform transition-all">
        <div class="px-8 py-6 border-b border-gray-100 bg-[#f8fafc] flex justify-between items-center">
            <h3 class="text-lg font-bold text-[#1e2336]">Edit Resource Card</h3>
            <button onclick="closeEditCardModal()" class="text-gray-400 hover:text-red-500">✕</button>
        </div>
        <form id="editCardForm" method="POST" enctype="multipart/form-data" class="p-8 space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Title</label>
                <input type="text" name="title" id="edit_title" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Short Description</label>
                <input type="text" name="short_description" id="edit_desc" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Link URL</label>
                    <input type="text" name="link_url" id="edit_url" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Order No.</label>
                    <input type="number" name="order_no" id="edit_order" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">New Image (Optional)</label>
                <input type="file" name="image" class="text-xs">
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closeEditCardModal()" class="text-[11px] font-bold text-gray-400 uppercase">Cancel</button>
                <button type="submit" class="bg-[#3b6df0] text-white px-8 py-3 rounded-xl font-bold text-[11px] uppercase tracking-widest shadow-lg">Update Card</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditCardModal(id) {
    fetch(`/cards/${id}/edit`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('edit_title').value = data.title;
            document.getElementById('edit_desc').value = data.short_description;
            document.getElementById('edit_url').value = data.link_url || '';
            document.getElementById('edit_order').value = data.order_no;
            document.getElementById('editCardForm').action = `/cards/${id}`;
            document.getElementById('editCardModal').classList.remove('hidden');
        });
}

function closeEditCardModal() {
    document.getElementById('editCardModal').classList.add('hidden');
}
</script>

@include('menus.partials.add-card-modal')
@endsection
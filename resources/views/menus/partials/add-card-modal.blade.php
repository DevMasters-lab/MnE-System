<div id="cardModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div id="cardBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>

    <div id="cardContent" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl transform scale-95 opacity-0 transition-all duration-300 overflow-hidden">
        
        <div class="flex justify-between items-center px-8 py-6 border-b border-gray-100 bg-[#f8fafc]">
            <div>
                <h3 class="text-xl font-bold text-[#1e2336]">Create New Card</h3>
                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest uppercase">Configuration for {{ $menu->name }}</p>
            </div>
            <button onclick="closeCardModal()" class="w-9 h-9 rounded-full hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition text-gray-400">✕</button>
        </div>

        <form action="{{ route('cards.store', $menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Title</label>
                        <input type="text" name="title" placeholder="e.g. YouTube" required 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Order No.</label>
                        <input type="number" name="order_no" value="1" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Short Description</label>
                    <input type="text" name="short_description" placeholder="Brief description" required 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Link URL</label>
                    <input type="text" name="link_url" placeholder="https://..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Card Image</label>
                    <input type="file" name="image" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700">
                </div>
            </div>

            <div class="flex justify-end gap-4 px-8 py-6 border-t border-gray-50 bg-gray-50">
                <button type="button" onclick="closeCardModal()" class="text-[11px] font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-widest">Cancel</button>
                <button type="submit" class="px-10 py-3 bg-[#1e2336] text-white text-[11px] font-bold rounded-xl shadow-lg hover:bg-black transition-all uppercase tracking-[0.2em]">Save Card</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCardModal() {
        const modal = document.getElementById('cardModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('cardBackdrop').classList.replace('opacity-0', 'opacity-100');
            document.getElementById('cardContent').classList.replace('opacity-0', 'opacity-100');
            document.getElementById('cardContent').classList.replace('scale-95', 'scale-100');
        }, 10);
    }

    function closeCardModal() {
        document.getElementById('cardBackdrop').classList.replace('opacity-100', 'opacity-0');
        document.getElementById('cardContent').classList.replace('opacity-100', 'opacity-0');
        document.getElementById('cardContent').classList.replace('scale-100', 'scale-95');
        setTimeout(() => document.getElementById('cardModal').classList.add('hidden'), 300);
    }
</script>
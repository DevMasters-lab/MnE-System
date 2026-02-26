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

        <form id="addCardForm" action="{{ route('cards.store', $menu->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-8 space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Title</label>
                        <input type="text" name="title" required 
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
                    <input type="text" name="short_description" required 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Link URL</label>
                    <input type="text" name="link_url" placeholder="https://..." 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500/10 outline-none transition">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Cover Banner Asset</label>
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-blue-300 hover:bg-blue-50/50 transition cursor-pointer relative group">
                        <input type="file" name="image" id="add_image_input" onchange="previewAddCardImage(this)" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="space-y-2">
                            <div class="bg-blue-50 w-10 h-10 rounded-full flex items-center justify-center mx-auto text-blue-500 transition-transform group-hover:scale-110">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </div>
                            <p class="text-[11px] text-gray-600 font-bold">Select Banner to Upload</p>
                            <p class="text-[9px] text-gray-400 uppercase tracking-widest">Recommended: 800x400px (Max 2MB)</p>
                        </div>
                    </div>

                    <div id="addPreviewContainer" class="hidden mt-4 p-3 border border-gray-100 rounded-xl bg-[#f8fafc] flex items-center gap-4 animate-fade-in">
                        <div class="w-20 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center overflow-hidden shadow-sm">
                            <img id="add_image_preview" src="#" alt="Preview" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[11px] font-bold text-[#1e2336] truncate" id="addFileNameDisplay">New Image Selected</p>
                            <button type="button" onclick="removeAddSelectedImage()" class="text-[9px] font-bold text-red-500 uppercase tracking-wider mt-1 hover:underline">Remove Image</button>
                        </div>
                    </div>
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
        document.getElementById('addCardForm').reset();
        removeAddSelectedImage();

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

    window.previewAddCardImage = function(input) {
        const container = document.getElementById('addPreviewContainer');
        const preview = document.getElementById('add_image_preview');
        const fileName = document.getElementById('addFileNameDisplay');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                fileName.innerText = input.files[0].name; // Show the file name
                container.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    window.removeAddSelectedImage = function() {
        document.getElementById('add_image_input').value = '';
        document.getElementById('addPreviewContainer').classList.add('hidden');
    }
</script>
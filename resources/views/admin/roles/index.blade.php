@extends('layouts.app')

@section('content')
<div class="p-6">
    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 text-emerald-600 border-l-4 border-emerald-500 rounded-r-lg flex items-center gap-3 animate-fade-in">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="text-sm font-medium italic">{{ session('success') }}</span>
        </div>
    @endif

    <div class="flex items-center justify-between mb-8 w-full px-2">
        <div>
            <h2 class="text-2xl font-bold text-[#1e2336]">Role & Permissions Configuration</h2>
        </div>
        <div class="flex items-center gap-4">

            <button onclick="openModal()" 
                    class="bg-[#3b6df0] hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-sm font-semibold shadow-md transition-all active:scale-95 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Role
            </button>
        </div>
    </div>

    {{-- Modal-based Role Form --}}
    <div id="roleModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div id="roleBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>

        <div id="roleCard" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl transform scale-95 opacity-0 transition-all duration-300 overflow-hidden">
            <div class="flex justify-between items-center px-10 py-6 border-b border-gray-50 bg-[#f8fafc]">
                <div>
                    <h3 id="roleModalTitle" class="text-xl font-bold text-[#1e2336]">Add New Role</h3>
                    <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-[0.2em]">Security & Permissions</p>
                </div>
                <button onclick="closeModal()" class="w-10 h-10 rounded-full hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition text-gray-400">✕</button>
            </div>

            <form id="roleForm" method="POST" class="p-10">
                @csrf
                <div id="roleMethodField"></div>

                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Role Name</label>
                        <input type="text" name="name" id="role_name" required
                               class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-6 py-4 text-sm text-[#1e2336] focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all placeholder:text-gray-300">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3 ml-1">Permissions</label>
                        <div id="permissionsContainer" class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-[#f8fafc] p-4 rounded-xl border border-gray-50">
                            @foreach($permissions as $permission)
                                <label class="group flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl cursor-pointer hover:border-blue-400 transition-all shadow-sm shadow-gray-50">
                                    <span class="text-xs font-black text-gray-400 group-hover:text-[#1e2336] uppercase tracking-widest transition-colors">{{ $permission->name }}</span>
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                           class="permission-checkbox w-5 h-5 rounded border-gray-200 text-[#4f70ce] focus:ring-[#4f70ce] focus:ring-offset-0 cursor-pointer">
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-6 mt-10 pt-8 border-t border-gray-50">
                    <button type="button" onclick="closeModal()" class="text-[11px] font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-[0.2em]">Cancel</button>
                    <button type="submit" id="roleSubmitBtn" class="px-12 py-4 bg-[#232936] hover:bg-black text-white text-[11px] font-bold rounded-xl shadow-xl shadow-gray-200 transition-all active:scale-95 uppercase tracking-[0.2em]">Save Role</button>
                </div>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden text-[#1e2336]">
        <table class="w-full text-left border-collapse">
            <thead class="bg-[#232936] text-white">
                <tr class="text-[10px] uppercase tracking-[0.25em]">
                    <th class="px-8 py-5 font-bold">Role</th>
                    <th class="px-8 py-5 font-bold">Permissions</th>
                    <th class="px-8 py-5 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @forelse($roles as $item)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-8 py-6">
                        <div class="flex items-center gap-4 font-bold uppercase">
                            {{ $item->name }}
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex flex-wrap gap-2">
                            @forelse($item->permissions as $perm)
                                <span class="bg-blue-50 text-blue-500 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border border-blue-100">
                                    {{ $perm->name }}
                                </span>
                            @empty
                                <span class="text-gray-300 italic text-[10px]">No Permissions Assigned</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-center gap-5">
                            <button type="button" onclick="openEditModal(this)"
                                data-id="{{ $item->id }}"
                                data-name="{{ $item->name }}"
                                data-permissions="{{ $item->permissions->pluck('name')->join(',') }}"
                                class="text-blue-500 hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('roles.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this role?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="3" class="p-16 text-center text-gray-400 italic font-medium">No roles configured yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script>
    const roleModal = document.getElementById('roleModal');
    const roleBackdrop = document.getElementById('roleBackdrop');
    const roleCard = document.getElementById('roleCard');
    const roleForm = document.getElementById('roleForm');

    window.openModal = function() {
        resetRoleForm();
        document.getElementById('roleModalTitle').innerText = 'Add New Role';
        roleForm.action = "{{ route('roles.store') }}";
        document.getElementById('roleMethodField').innerHTML = '';
        document.getElementById('roleSubmitBtn').innerText = 'Save Role';
        roleModal.classList.remove('hidden');
        setTimeout(() => {
            roleBackdrop.classList.replace('opacity-0', 'opacity-100');
            roleCard.classList.replace('opacity-0', 'opacity-100');
            roleCard.classList.replace('scale-95', 'scale-100');
        }, 10);
    }

    window.openEditModal = function(el) {
        resetRoleForm();
        const id = el.dataset.id;
        const name = el.dataset.name || '';
        const perms = el.dataset.permissions ? el.dataset.permissions.split(',') : [];

        document.getElementById('roleModalTitle').innerText = 'Edit Security Role';
        document.getElementById('role_name').value = name;

        // Uncheck all first
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);

        perms.forEach(p => {
            const selector = `.permission-checkbox[value="${p}"]`;
            const cb = document.querySelector(selector);
            if(cb) cb.checked = true;
        });

        roleForm.action = `/manage-roles/${id}`;
        document.getElementById('roleMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('roleSubmitBtn').innerText = 'Update Role';

        roleModal.classList.remove('hidden');
        setTimeout(() => {
            roleBackdrop.classList.replace('opacity-0', 'opacity-100');
            roleCard.classList.replace('opacity-0', 'opacity-100');
            roleCard.classList.replace('scale-95', 'scale-100');
        }, 10);
    }

    window.closeModal = function() {
        roleBackdrop.classList.replace('opacity-100', 'opacity-0');
        roleCard.classList.replace('opacity-100', 'opacity-0');
        roleCard.classList.replace('scale-100', 'scale-95');
        setTimeout(() => roleModal.classList.add('hidden'), 300);
    }

    function resetRoleForm() {
        roleForm.reset();
        document.getElementById('roleMethodField').innerHTML = '';
        document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
    }

    roleBackdrop.addEventListener('click', closeModal);
</script>

@endsection
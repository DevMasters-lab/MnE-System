@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-8 w-full px-2">
        <div>
            <h2 class="text-2xl font-bold text-[#1e2336]">Manage Users</h2>
        </div>

        <button onclick="openModal()" 
                class="bg-[#3b6df0] hover:bg-blue-700 text-white px-5 py-2.5 rounded-md text-sm font-semibold shadow-md transition-all active:scale-95 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add User
        </button>
    </div>

    {{-- Modal-based Form (Create / Edit) --}}
    <div id="userModal" class="hidden fixed inset-0 z-50 flex items-center justify-center">
        <div id="userBackdrop" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>

        <div id="userCard" class="relative bg-white rounded-2xl shadow-2xl w-full max-w-3xl transform scale-95 opacity-0 transition-all duration-300 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-50 bg-[#f8fafc] flex items-center gap-4">
                <div class="p-3 bg-[#4f70ce] text-white rounded-2xl shadow-lg shadow-blue-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 id="userModalTitle" class="text-[13px] font-bold text-[#1e2336] uppercase tracking-[0.15em]">Add New User</h3>
                </div>
                <button onclick="closeModal()" class="ml-auto w-10 h-10 rounded-full hover:bg-red-50 hover:text-red-500 flex items-center justify-center transition text-gray-400">✕</button>
            </div>

            <form id="userForm" method="POST" class="p-10">
                @csrf
                <div id="userMethodField"></div>

                <div class="space-y-8 w-full">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Full Name</label>
                        <input type="text" name="name" id="user_name" required 
                            class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all placeholder:text-gray-300">
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Email Address</label>
                        <input type="email" name="email" id="user_email" required 
                            class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Password <span id="passwordOptional" class="lowercase text-gray-300 font-normal italic ml-1 hidden">(Optional)</span></label>
                        <input type="password" name="password" id="user_password" required minlength="6"
                            class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all">
                        <p id="user_password_help" class="text-red-500 text-[10px] mt-2 font-bold uppercase tracking-widest hidden">Password must be at least 6 characters.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">User Role</label>
                        <select name="role" id="user_role" required class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all bg-white cursor-pointer">
                            <option value="" disabled selected>Select a Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-12 pt-8 border-t border-gray-50">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="is_active" id="user_is_active" checked
                            class="w-5 h-5 text-[#3b6df0] border-gray-200 rounded-lg focus:ring-[#3b6df0] transition-all cursor-pointer">
                        <span class="text-[11px] font-bold text-[#1e2336] uppercase tracking-[0.1em] group-hover:text-[#3b6df0] transition-colors">Account Active</span>
                    </label>
                    
                    <div class="flex items-center gap-6">
                        <button type="button" onclick="closeModal()" class="text-[11px] font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-[0.2em]">Cancel</button>
                        <button type="submit" id="userSubmitBtn" 
                            class="px-12 py-4 bg-[#232936] hover:bg-black text-white text-[11px] font-bold rounded-xl shadow-xl shadow-gray-200 transition-all active:scale-95 uppercase tracking-[0.2em]">Save Member</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 w-full overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#232936] text-white text-[10px] uppercase tracking-[0.25em]">
                    <th class="px-8 py-5 font-bold">Name</th>
                    <th class="px-8 py-5 font-bold">Email</th>
                    <th class="px-8 py-5 font-bold">Role</th>
                    <th class="px-8 py-5 font-bold">Status</th>
                    <th class="px-8 py-5 font-bold text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-sm">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-8 py-6 font-bold text-[#1e2336]">{{ $user->name }}</td>
                    <td class="px-8 py-6 text-gray-500 font-medium">{{ $user->email }}</td>
                    <td class="px-8 py-6">
                        {{-- DISPLAY ASSIGNED ROLES --}}
                        @forelse($user->getRoleNames() as $roleName)
                            <span class="bg-blue-50 text-blue-500 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border border-blue-100 inline-block mb-1">
                                {{ $roleName }}
                            </span>
                        @empty
                            <span class="text-gray-300 italic text-[10px]">No Role</span>
                        @endforelse
                    </td>
                    <td class="px-8 py-6">
                        @if($user->is_active ?? true)
                            <div class="flex items-center gap-2 text-[#1e8e3e] font-bold text-[9px] uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#1e8e3e]"></span> Active
                            </div>
                        @else
                            <div class="flex items-center gap-2 text-red-500 font-bold text-[9px] uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Inactive
                            </div>
                        @endif
                    </td>
                    <td class="px-8 py-6">
                        <div class="flex justify-center gap-5">
                            <button type="button" onclick="openEditModal(this)" 
                                data-id="{{ $user->id }}" 
                                data-name="{{ $user->name }}" 
                                data-email="{{ $user->email }}" 
                                data-role="{{ $user->getRoleNames()->first() ?? '' }}" 
                                data-active="{{ $user->is_active ?? true }}"
                                class="text-blue-500 hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete member?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-600 hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    const userModal = document.getElementById('userModal');
    const userBackdrop = document.getElementById('userBackdrop');
    const userCard = document.getElementById('userCard');
    const userForm = document.getElementById('userForm');
    const passwordOptional = document.getElementById('passwordOptional');
    const passwordHelp = document.getElementById('user_password_help');

    // validate length as user types
    document.getElementById('user_password').addEventListener('input', function() {
        if (this.value.length > 0 && this.value.length < 6) {
            passwordHelp.classList.remove('hidden');
        } else {
            passwordHelp.classList.add('hidden');
        }
    });

    window.openModal = function() {
        resetUserForm();
        document.getElementById('userModalTitle').innerText = 'Add New User';
        userForm.action = "{{ route('users.store') }}";
        document.getElementById('userMethodField').innerHTML = '';
        document.getElementById('user_password').required = true;
        passwordOptional.classList.add('hidden');
        document.getElementById('userSubmitBtn').innerText = 'Save Member';
        userModal.classList.remove('hidden');
        setTimeout(() => {
            userBackdrop.classList.replace('opacity-0', 'opacity-100');
            userCard.classList.replace('opacity-0', 'opacity-100');
            userCard.classList.replace('scale-95', 'scale-100');
        }, 10);
    }

    window.openEditModal = function(el) {
        resetUserForm();
        const id = el.dataset.id;
        document.getElementById('userModalTitle').innerText = 'Edit Account Details';
        document.getElementById('user_name').value = el.dataset.name || '';
        document.getElementById('user_email').value = el.dataset.email || '';
        document.getElementById('user_role').value = el.dataset.role || '';
        document.getElementById('user_is_active').checked = (el.dataset.active === '1' || el.dataset.active === 'true' || el.dataset.active === 'True');
        userForm.action = `/manage-user/${id}`;
        document.getElementById('userMethodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('user_password').required = false;
        passwordOptional.classList.remove('hidden');
        document.getElementById('userSubmitBtn').innerText = 'Update Member';
        userModal.classList.remove('hidden');
        setTimeout(() => {
            userBackdrop.classList.replace('opacity-0', 'opacity-100');
            userCard.classList.replace('opacity-0', 'opacity-100');
            userCard.classList.replace('scale-95', 'scale-100');
        }, 10);
    }

    window.closeModal = function() {
        userBackdrop.classList.replace('opacity-100', 'opacity-0');
        userCard.classList.replace('opacity-100', 'opacity-0');
        userCard.classList.replace('scale-100', 'scale-95');
        setTimeout(() => userModal.classList.add('hidden'), 300);
    }

    function resetUserForm() {
        userForm.reset();
        document.getElementById('user_is_active').checked = true;
        document.getElementById('user_password').value = '';
        document.getElementById('userMethodField').innerHTML = '';
        passwordOptional.classList.add('hidden');
        passwordHelp.classList.add('hidden');
    }

    userBackdrop.addEventListener('click', closeModal);
</script>

@endsection
@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="p-6">
    <div class="mb-8 px-2">
        <h2 class="text-2xl font-bold text-[#1e2336]">Manage Users</h2>
        <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-[0.2em]">System Access Control</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 w-full mb-10 overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 bg-[#f8fafc]">
            <h3 class="text-[13px] font-bold text-[#1e2336] uppercase tracking-[0.15em]">
                {{ $editUser ? 'Edit Account Details' : 'Create New Account' }}
            </h3>
        </div>
        
        <form action="{{ $editUser ? route('users.update', $editUser->id) : route('users.store') }}" method="POST" class="p-10">
            @csrf
            @if($editUser) @method('PUT') @endif

            <div class="space-y-8 w-full">
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Full Name</label>
                    <input type="text" name="name" value="{{ $editUser->name ?? '' }}" required 
                        class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all placeholder:text-gray-300" placeholder="e.g. Sithan Virath">
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Email Address</label>
                    <input type="email" name="email" value="{{ $editUser->email ?? '' }}" required 
                        class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all" placeholder="admin">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">
                        Password @if($editUser) <span class="lowercase text-gray-300 font-normal italic ml-1">(Optional)</span> @endif
                    </label>
                    <input type="password" name="password" {{ $editUser ? '' : 'required' }} 
                        class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all" placeholder="••••••••">
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">User Role</label>
                    <select name="role" class="w-full bg-[#f8fafc] border border-gray-100 rounded-xl px-5 py-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-400 outline-none transition-all bg-white cursor-pointer">
                        @foreach(['Team Lead', 'Backend Developer', 'Frontend Developer', 'UI/UX Designer', 'M&E Specialist', 'Admin'] as $role)
                            <option value="{{ $role }}" {{ (isset($editUser) && $editUser->role == $role) ? 'selected' : '' }}>{{ $role }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-between mt-12 pt-8 border-t border-gray-50">
                <label class="flex items-center gap-3 cursor-pointer group">
                    <input type="checkbox" name="is_active" {{ ($editUser->is_active ?? true) ? 'checked' : '' }} 
                        class="w-5 h-5 text-[#3b6df0] border-gray-200 rounded-lg focus:ring-[#3b6df0] transition-all cursor-pointer">
                    <span class="text-[11px] font-bold text-[#1e2336] uppercase tracking-[0.1em] group-hover:text-[#3b6df0] transition-colors">Account Active</span>
                </label>
                
                <div class="flex items-center gap-6">
                    @if($editUser)
                        <a href="{{ route('users.index') }}" class="text-[11px] font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-[0.2em]">Back</a>
                    @else
                        <button type="reset" class="text-[11px] font-bold text-gray-400 hover:text-gray-600 transition uppercase tracking-[0.2em]">Reset</button>
                    @endif
                    
                    <button type="submit" 
                        class="px-12 py-4 bg-[#232936] hover:bg-black text-white text-[11px] font-bold rounded-xl shadow-xl shadow-gray-200 transition-all active:scale-95 uppercase tracking-[0.2em]">
                        Save Member
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 w-full overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-[#232936] text-white text-[10px] uppercase tracking-[0.25em]">
                    <th class="px-8 py-5 font-bold">Member</th>
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
                        <span class="bg-blue-50 text-blue-500 px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border border-blue-100">
                            {{ $user->role ?? 'Admin' }}
                        </span>
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
                            <a href="{{ route('users.index', ['edit' => $user->id]) }}" class="text-blue-500 hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </a>
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
@endsection
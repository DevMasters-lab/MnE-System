<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Login</title>
    @vite('resources/css/app.css')
</head>
<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center bg-[#232936]">
        
        <div class="bg-white p-10 rounded-lg shadow-xl w-full max-w-[400px]">
            <h2 class="text-[22px] font-bold text-center text-[#1e2336] mb-8">System Login</h2>
            
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                
                <div class="mb-5">
                    <label for="email" class="block text-sm font-semibold text-gray-800 mb-1.5">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                    >
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label for="password" class="block text-sm font-semibold text-gray-800 mb-1.5">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full border border-gray-200 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500" 
                        required
                    >
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-[#3b6df0] hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-md transition duration-200 text-sm"
                >
                    Log In
                </button>
            </form>
        </div>

    </div>
</body>
</html>
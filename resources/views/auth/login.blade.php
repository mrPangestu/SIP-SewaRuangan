<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <title>Login</title>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Login ke Akun Anda</h1>
            <p class="text-gray-600">Masukkan email dan password Anda</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required autocomplete="email" autofocus>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 mb-2">Password</label>
                <input id="password" type="password" name="password" 
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required autocomplete="current-password">
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <input id="remember" type="checkbox" name="remember" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Ingat saya
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-800" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition duration-300">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-gray-600">Belum punya akun? 
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Daftar sekarang
                </a>
            </p>
        </div>
    </div>
</div>
</body>
</html>

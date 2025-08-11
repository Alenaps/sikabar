<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - SIKABAR</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-lg rounded-lg flex max-w-4xl w-full overflow-hidden border-2 border-blue-500">
        
        <!-- Kiri (Info Panel) -->
        <div class="w-1/2 text-white p-8 flex flex-col justify-center items-center" style="background: linear-gradient(180deg, #0033FF 0%, #0024AF 50%, #011460 100%);">
            <img src="{{ asset('assets/img/logo.png') }}" class="w-16 h-16 mb-4" alt="Logo">
            <h2 class="text-xl font-bold mb-4 text-center">Selamat Datang di Sikabar!</h2>
            
            <!-- Deskripsi dalam kotak putih -->
            <div class="bg-white text-gray-700 rounded p-4 shadow-md text-sm leading-relaxed text-center">
                Sistem Informasi Kependudukan Kecamatan Bandar Sribhawono (SIKABAR) adalah aplikasi web yang membantu staf kecamatan dan sekretaris desa mengelola data kependudukan seperti kartu keluarga, kelahiran, kematian, pendatang, dan perpindahan. Dengan tampilan yang ramah pengguna dan fitur keamanan yang ketat, SIKABAR menyediakan solusi pendataan kependudukan yang cepat dan efisien.
            </div>
        </div>

        <!-- Kanan (Form Login) -->
        <div class="w-1/2 p-10 bg-white">
            <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">Login</h2>

            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-gray-600">Email</label>
                    <input type="email" name="email" id="email" class="w-full p-2 border border-gray-300 rounded mt-1" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold text-gray-600">Password</label>
                    <input type="password" name="password" id="password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember" class="form-checkbox">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-cyan-400 hover:bg-cyan-500 text-white font-bold py-2 px-4 rounded">
                    Login
                </button>
                
                <div class="flex items-center justify-center mt-4">
                @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                @endif
                </div>
                
            </form>
        </div>
    </div>

</body>
</html>

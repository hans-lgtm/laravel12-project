<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemilihan OSIS - {{ $title ?? 'Halaman Utama' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .candidate-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .candidate-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
<header class="gradient-bg text-white shadow-lg">
    <div class="container mx-auto px-4 py-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Pemilihan Ketua OSIS</h1>
            <div class="flex space-x-4 items-center">
                @if(Session::get('access_code_verified'))
                <span class="text-green-300">
                    <i class="fas fa-user mr-1"></i>
                    Pemilih: {{ Session::get('access_code') }}
                </span>
                <form action="{{ route('access-code.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-blue-200 transition text-sm">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </button>
                </form>
                @endif
                
                @if(Session::get('admin_verified'))
                <span class="text-yellow-300">
                    <i class="fas fa-crown mr-1"></i>
                    Admin: {{ Session::get('admin_code') }}
                </span>
                <a href="{{ route('admin.statistics') }}" class="hover:text-blue-200 transition">
                    <i class="fas fa-chart-pie mr-1"></i>Statistik
                </a>
                <a href="{{ route('election.results') }}" class="hover:text-blue-200 transition">
                    <i class="fas fa-chart-bar mr-1"></i>Hasil
                </a>
                <form action="{{ route('access-code.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-blue-200 transition text-sm">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout Admin
                    </button>
                </form>
                @endif
                
                @if(!Session::get('access_code_verified') && !Session::get('admin_verified'))
                <a href="{{ route('access-code.verify') }}" class="hover:text-blue-200 transition">Login</a>
                @endif
            </div>
        </div>
    </div>
</header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} @M.Hanif | XII RPL 5.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
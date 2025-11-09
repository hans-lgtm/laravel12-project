<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Kode Akses - Pemilihan OSIS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-glow {
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Login Card -->
        <div class="bg-white rounded-2xl card-glow transform transition-all duration-300 hover:scale-105">
            <div class="p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-lock text-white text-2xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Pemilihan OSIS</h1>
                    <p class="text-gray-600">Masukkan kode akses untuk melanjutkan</p>
                </div>

                <!-- Success Message -->
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 animate-pulse">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
                @endif

                <!-- Error Message -->
                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    {{ session('error') }}
                </div>
                @endif

                <!-- Info Message -->
                @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg mb-6">
                    <i class="fas fa-info-circle mr-2"></i>
                    {{ session('info') }}
                </div>
                @endif

                <!-- Form -->
                <form action="{{ route('access-code.submit') }}" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="access_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Akses <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   id="access_code" 
                                   name="access_code" 
                                   required
                                   maxlength="6"
                                   placeholder="Contoh: A1B2C3"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center text-xl font-mono tracking-widest uppercase
                                   @error('access_code') border-red-500 @enderror"
                                   value="{{ old('access_code') }}">
                            <div class="absolute right-3 top-3">
                                <i class="fas fa-key text-gray-400"></i>
                            </div>
                        </div>
                        
                        @error('access_code')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        
                        <p class="text-gray-500 text-sm mt-2">
                            Masukkan 6 karakter kode akses (huruf dan angka)
                        </p>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white py-3 rounded-lg font-semibold hover:from-blue-600 hover:to-purple-700 transition transform hover:scale-105 shadow-md">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Verifikasi Kode
                    </button>
                </form>

                <!-- Information -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-gray-800 mb-1">Informasi</h4>
                            <p class="text-sm text-gray-600">
                                Setiap kode akses hanya dapat digunakan satu kali untuk memilih. 
                                Pastikan kode yang Anda masukkan benar.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-white text-sm">
                &copy; {{ date('Y') }} M.Hanif | XII RPL 5
            </p>
        </div>
    </div>

    <script>
        // Auto uppercase dan hanya allow alphanumeric
        document.getElementById('access_code').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        });

        // Focus on input when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('access_code').focus();
        });

        // Add some animation
        document.querySelector('form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memverifikasi...';
            button.disabled = true;
        });
    </script>
</body>
</html>
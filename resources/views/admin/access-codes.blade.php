@extends('layouts.app')

@section('title', 'Kelola Kode Akses')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Kelola Kode Akses</h2>
        <div class="space-x-4">
            <a href="{{ route('admin.access-codes.export') }}" 
               class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                <i class="fas fa-download mr-2"></i>Export Kode
            </a>
            <a href="{{ route('admin.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Danger Zone - Hapus Kode -->
    <div class="bg-red-50 border border-red-200 rounded-xl p-6 mb-8">
        <h3 class="text-2xl font-bold text-red-800 mb-4 flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Zone Berbahaya
        </h3>
        <p class="text-red-700 mb-4">Hati-hati! Tindakan ini tidak dapat dibatalkan.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Hapus Kode Sudah Digunakan -->
            <form action="{{ route('admin.access-codes.delete-all') }}" method="POST" 
                  onsubmit="return confirm('Yakin hapus {{ $usedCodes->count() }} kode yang sudah digunakan?')">
                @csrf
                <input type="hidden" name="type" value="used">
                <button type="submit" 
                        class="w-full bg-orange-500 hover:bg-orange-600 text-white px-4 py-3 rounded-lg font-semibold transition text-center">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Used<br>
                    <span class="text-sm">({{ $usedCodes->count() }} kode)</span>
                </button>
            </form>

            <!-- Hapus Kode Belum Digunakan -->
            <form action="{{ route('admin.access-codes.delete-all') }}" method="POST"
                  onsubmit="return confirm('Yakin hapus {{ $unusedCodes->count() }} kode yang belum digunakan?')">
                @csrf
                <input type="hidden" name="type" value="unused">
                <button type="submit" 
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-3 rounded-lg font-semibold transition text-center">
                    <i class="fas fa-trash mr-2"></i>
                    Hapus Unused<br>
                    <span class="text-sm">({{ $unusedCodes->count() }} kode)</span>
                </button>
            </form>

            <!-- Hapus Semua Kode -->
            <form action="{{ route('admin.access-codes.delete-all') }}" method="POST"
                  onsubmit="return confirm('YAKIN HAPUS SEMUA {{ $totalCodes }} KODE AKSES? Tindakan ini tidak dapat dibatalkan!')">
                @csrf
                <input type="hidden" name="type" value="all">
                <button type="submit" 
                        class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-3 rounded-lg font-semibold transition text-center">
                    <i class="fas fa-bomb mr-2"></i>
                    Hapus Semua<br>
                    <span class="text-sm">({{ $totalCodes }} kode)</span>
                </button>
            </form>

            <!-- Reset & Generate Baru -->
            <form action="{{ route('admin.access-codes.reset') }}" method="POST" 
                  onsubmit="return confirm('Yakin reset semua kode dan generate baru? {{ $totalCodes }} kode lama akan dihapus!')">
                @csrf
                <div class="flex">
                    <input type="number" 
                           name="count" 
                           min="1" 
                           max="500" 
                           value="225"
                           class="flex-1 px-3 py-2 border border-red-300 rounded-l-lg focus:ring-2 focus:ring-red-500 text-sm">
                    <button type="submit" 
                            class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-r-lg font-semibold transition whitespace-nowrap">
                        <i class="fas fa-sync-alt mr-1"></i>Reset
                    </button>
                </div>
                <p class="text-xs text-red-600 mt-1">Hapus semua + generate baru</p>
            </form>
        </div>
    </div>

    <!-- Generate New Codes Form -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Generate Kode Baru</h3>
        <form action="{{ route('admin.access-codes.generate') }}" method="POST" class="flex items-end space-x-4">
            @csrf
            <div class="flex-1">
                <label for="count" class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah Kode yang akan Digenerate
                </label>
                <input type="number" 
                       id="count" 
                       name="count" 
                       min="1" 
                       max="100" 
                       value="10"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold transition">
                <i class="fas fa-plus mr-2"></i>Generate Kode
            </button>
        </form>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600">{{ $totalCodes }}</div>
            <div class="text-gray-600">Total Kode</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="text-3xl font-bold text-green-600">{{ $unusedCodes->count() }}</div>
            <div class="text-gray-600">Belum Digunakan</div>
        </div>
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="text-3xl font-bold text-red-600">{{ $usedCodes->count() }}</div>
            <div class="text-gray-600">Sudah Digunakan</div>
        </div>
    </div>

    <!-- Unused Codes -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Kode Belum Digunakan</h3>
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                {{ $unusedCodes->count() }} kode tersedia
            </span>
        </div>
        
        @if($unusedCodes->isNotEmpty())
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($unusedCodes as $code)
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center relative group">
                <div class="text-xl font-bold text-green-800 font-mono">{{ $code->code }}</div>
                <div class="text-sm text-green-600 mt-1">Tersedia</div>
                
                <!-- Delete Button -->
                <form action="{{ route('admin.access-codes.delete', $code->id) }}" method="POST" 
                      class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            onclick="return confirm('Hapus kode {{ $code->code }}?')"
                            class="text-red-500 hover:text-red-700 text-xs">
                        <i class="fas fa-times"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-info-circle text-3xl mb-2"></i>
            <p>Tidak ada kode yang tersedia.</p>
        </div>
        @endif
    </div>

    <!-- Used Codes -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Kode Sudah Digunakan</h3>
            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">
                {{ $usedCodes->count() }} kode digunakan
            </span>
        </div>
        
        @if($usedCodes->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Digunakan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($usedCodes as $code)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-mono font-bold text-red-600">{{ $code->code }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $code->used_at ? $code->used_at->format('d/m/Y H:i:s') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $code->used_by_ip ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-info-circle text-3xl mb-2"></i>
            <p>Belum ada kode yang digunakan.</p>
        </div>
        @endif
    </div>
</div>

<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl max-w-md w-full p-6">
        <div class="text-center mb-4">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-3"></i>
            <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Konfirmasi Hapus</h3>
            <p class="text-gray-600 mt-2" id="modalMessage">Apakah Anda yakin?</p>
        </div>
        <div class="flex space-x-3">
            <button type="button" 
                    onclick="closeModal()"
                    class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                Batal
            </button>
            <form id="confirmForm" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-semibold">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(action, type, count) {
    const messages = {
        'used': `Yakin menghapus ${count} kode yang sudah digunakan?`,
        'unused': `Yakin menghapus ${count} kode yang belum digunakan?`,
        'all': `YAKIN HAPUS SEMUA ${count} KODE AKSES? Tindakan ini tidak dapat dibatalkan!`,
        'reset': `Yakin reset semua kode? ${count} kode lama akan dihapus dan diganti dengan kode baru!`
    };

    document.getElementById('modalMessage').textContent = messages[type];
    document.getElementById('confirmForm').action = action;
    document.getElementById('confirmationModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('confirmationModal').classList.add('hidden');
}
</script>
@endpush
@endsection
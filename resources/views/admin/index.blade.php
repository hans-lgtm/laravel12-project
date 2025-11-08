@extends('layouts.app')

@section('title', 'Admin - Kelola Paslon')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Kelola Pasangan Calon</h2>
        <a href="{{ route('admin.create') }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition">
            <i class="fas fa-plus mr-2"></i>Tambah Paslon
        </a>
    </div>

    <!-- Tambahkan di bagian setelah header -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <a href="{{ route('admin.statistics') }}" class="bg-blue-500 hover:bg-blue-600 text-white rounded-xl p-6 text-center transition transform hover:scale-105">
        <i class="fas fa-chart-pie text-3xl mb-3"></i>
        <h3 class="text-xl font-bold">Statistik</h3>
        <p class="text-blue-100">Lihat progress pemilihan</p>
    </a>
    
    <a href="{{ route('election.results') }}" class="bg-purple-500 hover:bg-purple-600 text-white rounded-xl p-6 text-center transition transform hover:scale-105">
        <i class="fas fa-chart-bar text-3xl mb-3"></i>
        <h3 class="text-xl font-bold">Hasil Pemilihan</h3>
        <p class="text-purple-100">Lihat hasil detail</p>
    </a>
    
    <a href="{{ route('admin.access-codes') }}" class="bg-green-500 hover:bg-green-600 text-white rounded-xl p-6 text-center transition transform hover:scale-105">
        <i class="fas fa-key text-3xl mb-3"></i>
        <h3 class="text-xl font-bold">Kode Akses</h3>
        <p class="text-green-100">Kelola kode pemilih</p>
    </a>
</div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paslon</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ketua & Wakil</th>
                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($candidates as $candidate)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-2xl font-bold text-blue-600">{{ $candidate->number }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 h-16 w-16 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold">
                                {{ $candidate->number }}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Paslon {{ $candidate->number }}</div>
                                <div class="text-sm text-gray-500">{{ $candidate->chairman_name }} & {{ $candidate->vice_chairman_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            <div><strong>Ketua:</strong> {{ $candidate->chairman_name }}</div>
                            <div><strong>Wakil:</strong> {{ $candidate->vice_chairman_name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.edit', $candidate->id) }}" 
                               class="text-blue-600 hover:text-blue-900 px-3 py-1 border border-blue-600 rounded hover:bg-blue-50 transition">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <form action="{{ route('admin.destroy', $candidate->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus paslon ini?')"
                                        class="text-red-600 hover:text-red-900 px-3 py-1 border border-red-600 rounded hover:bg-red-50 transition">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Navigation -->
    <div class="mt-8 flex justify-between">
        <a href="{{ route('election.index') }}" 
           class="text-blue-600 hover:text-blue-800 font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Halaman Utama
        </a>
        <a href="{{ route('election.results') }}" 
           class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-semibold transition">
            <i class="fas fa-chart-bar mr-2"></i>Lihat Hasil
        </a>
    </div>
</div>
@endsection
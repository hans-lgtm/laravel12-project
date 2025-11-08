@extends('layouts.app')

@section('title', 'Tambah Paslon Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Tambah Paslon Baru</h2>

        <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Ketua -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Data Ketua</h3>
                    
                    <div class="mb-4">
                        <label for="chairman_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Ketua</label>
                        <input type="text" 
                               id="chairman_name" 
                               name="chairman_name" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="chairman_photo" class="block text-sm font-medium text-gray-700 mb-2">Foto Ketua</label>
                        <input type="file" 
                               id="chairman_photo" 
                               name="chairman_photo" 
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>

                <!-- Wakil Ketua -->
                <div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-4 border-b pb-2">Data Wakil Ketua</h3>
                    
                    <div class="mb-4">
                        <label for="vice_chairman_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Wakil Ketua</label>
                        <input type="text" 
                               id="vice_chairman_name" 
                               name="vice_chairman_name" 
                               required
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label for="vice_chairman_photo" class="block text-sm font-medium text-gray-700 mb-2">Foto Wakil Ketua</label>
                        <input type="file" 
                               id="vice_chairman_photo" 
                               name="vice_chairman_photo" 
                               accept="image/*"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    </div>
                </div>
            </div>

            <!-- Nomor Paslon -->
            <div class="mb-6">
                <label for="number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Paslon</label>
                <input type="text" 
                       id="number" 
                       name="number" 
                       required
                       placeholder="Contoh: 01, 02, etc."
                       class="w-full md:w-1/4 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Visi -->
            <div class="mb-6">
                <label for="vision" class="block text-sm font-medium text-gray-700 mb-2">Visi</label>
                <textarea id="vision" 
                          name="vision" 
                          rows="4" 
                          required
                          placeholder="Tuliskan visi pasangan calon..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <!-- Misi -->
            <div class="mb-6">
                <label for="mission" class="block text-sm font-medium text-gray-700 mb-2">Misi</label>
                <textarea id="mission" 
                          name="mission" 
                          rows="6" 
                          required
                          placeholder="Tuliskan misi pasangan calon..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.index') }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition font-semibold">
                    <i class="fas fa-save mr-2"></i>Simpan Paslon
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
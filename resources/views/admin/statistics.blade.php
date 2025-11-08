@extends('layouts.app')

@section('title', 'Statistik Pemilihan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Statistik Pemilihan</h2>
        <div class="space-x-4">
            <a href="{{ route('admin.index') }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-blue-600">{{ $totalVoters }}</div>
            <div class="text-gray-600">Total Pemilih Terdaftar</div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-vote-yea text-white text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-green-600">{{ $votedCount }}</div>
            <div class="text-gray-600">Sudah Memilih</div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-percentage text-white text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-yellow-600">{{ $votingPercentage }}%</div>
            <div class="text-gray-600">Persentase Partisipasi</div>
        </div>
        
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-clock text-white text-xl"></i>
            </div>
            <div class="text-3xl font-bold text-red-600">{{ $unusedCodes }}</div>
            <div class="text-gray-600">Belum Memilih</div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-4">Progress Pemilihan</h3>
        <div class="w-full bg-gray-200 rounded-full h-6 mb-2">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 h-6 rounded-full transition-all duration-500" 
                 style="width: {{ $votingPercentage }}%"></div>
        </div>
        <div class="flex justify-between text-sm text-gray-600">
            <span>{{ $votedCount }} suara ({{ $votingPercentage }}%)</span>
            <span>{{ $totalVoters }} total pemilih</span>
        </div>
    </div>

    <!-- Candidate Results -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-2xl font-bold text-gray-800 mb-6">Hasil Sementara</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($candidates as $candidate)
            @php
                $percentage = $votedCount > 0 ? round(($candidate->votes_count / $votedCount) * 100, 2) : 0;
            @endphp
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800">Paslon {{ $candidate->number }}</h4>
                        <p class="text-gray-600">{{ $candidate->chairman_name }} & {{ $candidate->vice_chairman_name }}</p>
                    </div>
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $candidate->votes_count }} suara
                    </span>
                </div>
                
                <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full" 
                         style="width: {{ $percentage }}%"></div>
                </div>
                
                <div class="flex justify-between text-sm text-gray-600">
                    <span>{{ $percentage }}%</span>
                    <span>{{ $candidate->votes_count }} suara</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 flex justify-center space-x-4">
        <a href="{{ route('election.results') }}" 
           class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg font-semibold transition">
            <i class="fas fa-chart-bar mr-2"></i>Lihat Hasil Detail
        </a>
        <a href="{{ route('admin.access-codes') }}" 
           class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition">
            <i class="fas fa-key mr-2"></i>Kelola Kode Akses
        </a>
    </div>
</div>
@endsection
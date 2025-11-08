@extends('layouts.app')

@section('title', 'Hasil Pemilihan')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-800 mb-4">HASIL PEMILIHAN OSIS</h2>
        <p class="text-xl text-gray-600">Statistik hasil pemilihan ketua OSIS</p>
        <div class="mt-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded inline-block">
            <i class="fas fa-chart-bar mr-2"></i>
            Total Suara: {{ number_format($totalVotes) }}
        </div>
    </div>

    <!-- Results Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 text-center">Diagram Hasil Pemilihan</h3>
        <div class="h-96">
            <canvas id="resultsChart"></canvas>
        </div>
    </div>

    <!-- Detailed Results -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($candidates as $candidate)
        @php
            // Pastikan votes_count ada dan berupa integer
            $voteCount = isset($candidate->votes_count) ? (int) $candidate->votes_count : 0;
            $percentage = $totalVotes > 0 ? round(($voteCount / $totalVotes) * 100, 2) : 0;
        @endphp
        <div class="bg-white rounded-xl shadow-md p-6 candidate-card">
            <div class="text-center mb-4">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-2 rounded-lg mb-3">
                    <h4 class="text-xl font-bold">PASLON {{ $candidate->number }}</h4>
                </div>
                <h4 class="text-lg font-bold text-gray-800">{{ $candidate->chairman_name }}</h4>
                <p class="text-gray-600">& {{ $candidate->vice_chairman_name }}</p>
            </div>
            
            <div class="text-center mb-4">
                <div class="text-3xl font-bold text-blue-600">{{ number_format($voteCount) }}</div>
                <div class="text-sm text-gray-600">Suara</div>
            </div>
            
            <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-4 rounded-full" 
                     style="width: {{ $percentage }}%"></div>
            </div>
            
            <div class="text-center text-sm font-semibold text-gray-700">
                {{ $percentage }}%
            </div>
        </div>
        @endforeach
    </div>

    <!-- Back Button -->
    <div class="text-center mt-12">
        <a href="{{ route('election.index') }}" 
           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-600 hover:to-purple-700 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Halaman Utama
        </a>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('resultsChart');
    
    if (!ctx) {
        console.error('Canvas element not found');
        return;
    }
    
    const ctx2d = ctx.getContext('2d');
    
    // Data dari PHP
    const candidates = @json($candidates->map(function($candidate) {
        return 'Paslon ' . $candidate->number;
    }));
    
    const votes = @json($candidates->map(function($candidate) {
        return isset($candidate->votes_count) ? (int) $candidate->votes_count : 0;
    }));
    
    const totalVotes = {{ (int) $totalVotes }};
    
    const colors = [
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 99, 132, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(255, 159, 64, 0.7)'
    ];
    
    // Pastikan semua votes adalah number
    const validatedVotes = votes.map(function(vote) {
        return Number(vote) || 0;
    });
    
    new Chart(ctx2d, {
        type: 'bar',
        data: {
            labels: candidates,
            datasets: [{
                label: 'Jumlah Suara',
                data: validatedVotes,
                backgroundColor: colors,
                borderColor: colors.map(function(color) {
                    return color.replace('0.7', '1');
                }),
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const voteCount = Number(context.raw) || 0;
                            let percentage = 0;
                            if (totalVotes > 0) {
                                percentage = (voteCount / totalVotes * 100).toFixed(2);
                            }
                            return 'Suara: ' + voteCount + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            if (value % 1 === 0) {
                                return value;
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Jumlah Suara'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Pasangan Calon'
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection
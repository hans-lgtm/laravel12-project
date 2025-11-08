@extends('layouts.app')

@section('title', 'Pemilihan OSIS')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h2 class="text-4xl font-bold text-gray-800 mb-4">PEMILIHAN KETUA OSIS</h2>
        <p class="text-xl text-gray-600">Pilihlah pasangan calon ketua OSIS yang menurut Anda paling tepat untuk memimpin!</p>
        
        @if($hasVoted)
        <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg inline-block animate-pulse">
            <i class="fas fa-check-circle mr-2"></i>
            <span class="font-semibold">Anda sudah melakukan pemilihan. Terima kasih atas partisipasinya!</span>
        </div>
        @endif
    </div>

    <!-- Admin Access Button -->
    <div class="text-center mb-8">
        <a href="{{ route('admin.index') }}" 
           class="inline-flex items-center bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm transition transform hover:scale-105">
            <i class="fas fa-cog mr-2"></i>Akses Admin
        </a>
    </div>

    <!-- Candidates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($candidates as $candidate)
        <div class="candidate-card bg-white rounded-xl shadow-lg overflow-hidden border-2 border-transparent hover:border-blue-500 transition-all duration-300">
            <!-- Header with Number -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white py-4 text-center relative">
                <div class="text-2xl font-bold">PASLON {{ $candidate->number }}</div>
                <div class="absolute top-2 right-2 bg-white bg-opacity-20 rounded-full p-1">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
            </div>
            
            <!-- Photos -->
            <div class="flex h-48">
                <div class="w-1/2 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center relative">
                    @if($candidate->chairman_photo)
                    <img src="{{ asset('storage/' . $candidate->chairman_photo) }}" 
                         alt="{{ $candidate->chairman_name }}" 
                         class="h-full w-full object-cover">
                    @else
                    <div class="text-center">
                        <i class="fas fa-user text-blue-500 text-4xl mb-2"></i>
                        <p class="text-blue-600 text-sm font-semibold">Ketua</p>
                    </div>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 bg-blue-500 bg-opacity-90 text-white text-center py-1 text-xs font-semibold">
                        Ketua
                    </div>
                </div>
                <div class="w-1/2 bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center relative">
                    @if($candidate->vice_chairman_photo)
                    <img src="{{ asset('storage/' . $candidate->vice_chairman_photo) }}" 
                         alt="{{ $candidate->vice_chairman_name }}" 
                         class="h-full w-full object-cover">
                    @else
                    <div class="text-center">
                        <i class="fas fa-user text-purple-500 text-4xl mb-2"></i>
                        <p class="text-purple-600 text-sm font-semibold">Wakil</p>
                    </div>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 bg-purple-500 bg-opacity-90 text-white text-center py-1 text-xs font-semibold">
                        Wakil
                    </div>
                </div>
            </div>
            
            <!-- Candidate Info -->
            <div class="p-6">
                <div class="mb-4 text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">
                        {{ $candidate->chairman_name }}
                    </h3>
                    <p class="text-lg text-gray-600 font-semibold">
                        & {{ $candidate->vice_chairman_name }}
                    </p>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col space-y-3">
                    <button onclick="showVisionMission({{ $candidate->id }})" 
                            class="flex items-center justify-center px-4 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition transform hover:scale-105 shadow-md">
                        <i class="fas fa-eye mr-2"></i>
                        Lihat Visi & Misi
                    </button>
                    
                    @if(!$hasVoted)
                    <button type="button" 
                            onclick="showVoteConfirmation({{ $candidate->id }}, '{{ $candidate->number }}', '{{ $candidate->chairman_name }}', '{{ $candidate->vice_chairman_name }}')"
                            class="w-full flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition transform hover:scale-105 shadow-md font-semibold">
                        <i class="fas fa-vote-yea mr-2"></i>
                        Pilih Paslon Ini
                    </button>
                    @else
                    <button disabled class="w-full flex items-center justify-center px-4 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed shadow">
                        <i class="fas fa-check-circle mr-2"></i>
                        Sudah Memilih
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Vision Mission Modal -->
<div id="visionMissionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-2xl font-bold text-gray-800">Visi & Misi</h3>
                <button onclick="closeVisionMissionModal()" class="text-gray-500 hover:text-gray-700 transition transform hover:rotate-90">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modalContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Vote Confirmation Modal -->
<div id="voteConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-xl max-w-md w-full transform transition-all duration-300 scale-95">
        <!-- Modal Header -->
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6 rounded-t-xl text-center">
            <i class="fas fa-vote-yea text-4xl mb-3"></i>
            <h3 class="text-2xl font-bold">Konfirmasi Pilihan</h3>
        </div>
        
        <!-- Modal Content -->
        <div class="p-6 text-center">
            <div class="mb-4">
                <p class="text-gray-600 mb-2">Apakah Anda yakin memilih:</p>
                <h4 id="confirmationCandidateNumber" class="text-xl font-bold text-gray-800 mb-1"></h4>
                <p id="confirmationCandidateNames" class="text-lg text-gray-700"></p>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <i class="fas fa-exclamation-triangle text-yellow-500 mr-2"></i>
                <span class="text-yellow-700 text-sm font-semibold">Pilihan tidak dapat diubah setelah dikonfirmasi!</span>
            </div>
        </div>
        
        <!-- Modal Footer -->
        <div class="flex space-x-3 p-6 border-t border-gray-200">
            <button type="button" 
                    onclick="closeVoteConfirmationModal()"
                    class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <form id="voteForm" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition font-semibold shadow-md">
                    <i class="fas fa-check mr-2"></i>Ya, Saya Yakin
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Success Notification -->
@if(session('success'))
<div id="successNotification" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-0">
    <div class="flex items-center">
        <i class="fas fa-check-circle text-xl mr-3"></i>
        <div>
            <p class="font-semibold">Berhasil!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
        <button onclick="closeNotification()" class="ml-4 text-white hover:text-green-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif

@if(session('error'))
<div id="errorNotification" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-all duration-300 translate-x-0">
    <div class="flex items-center">
        <i class="fas fa-exclamation-triangle text-xl mr-3"></i>
        <div>
            <p class="font-semibold">Error!</p>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
        <button onclick="closeNotification()" class="ml-4 text-white hover:text-red-200">
            <i class="fas fa-times"></i>
        </button>
    </div>
</div>
@endif

@push('scripts')
<script>
// Vision Mission Modal Functions
function showVisionMission(candidateId) {
    fetch(`/candidate/${candidateId}/vision-mission`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('modalContent').innerHTML = html;
            document.getElementById('visionMissionModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('visionMissionModal').style.transform = 'scale(1)';
            }, 10);
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat visi & misi.');
        });
}

function closeVisionMissionModal() {
    document.getElementById('visionMissionModal').style.transform = 'scale(0.95)';
    setTimeout(() => {
        document.getElementById('visionMissionModal').classList.add('hidden');
    }, 200);
}

// Vote Confirmation Modal Functions
function showVoteConfirmation(candidateId, candidateNumber, chairmanName, viceChairmanName) {
    // Update modal content
    document.getElementById('confirmationCandidateNumber').textContent = 'PASLON ' + candidateNumber;
    document.getElementById('confirmationCandidateNames').textContent = chairmanName + ' & ' + viceChairmanName;
    
    // Update form action
    document.getElementById('voteForm').action = `/vote/${candidateId}`;
    
    // Show modal
    document.getElementById('voteConfirmationModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('voteConfirmationModal').style.transform = 'scale(1)';
    }, 10);
}

function closeVoteConfirmationModal() {
    document.getElementById('voteConfirmationModal').style.transform = 'scale(0.95)';
    setTimeout(() => {
        document.getElementById('voteConfirmationModal').classList.add('hidden');
    }, 200);
}

// Notification Functions
function closeNotification() {
    const successNotification = document.getElementById('successNotification');
    const errorNotification = document.getElementById('errorNotification');
    
    if (successNotification) {
        successNotification.style.transform = 'translateX(100%)';
        setTimeout(() => successNotification.remove(), 300);
    }
    if (errorNotification) {
        errorNotification.style.transform = 'translateX(100%)';
        setTimeout(() => errorNotification.remove(), 300);
    }
}

// Auto close notifications after 5 seconds
setTimeout(closeNotification, 5000);

// Close modals when clicking outside
document.getElementById('visionMissionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVisionMissionModal();
    }
});

document.getElementById('voteConfirmationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVoteConfirmationModal();
    }
});

// Handle form submission
document.getElementById('voteForm')?.addEventListener('submit', function(e) {
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
    submitBtn.disabled = true;
    
    // Form will submit normally
});
</script>

<style>
.candidate-card {
    transition: all 0.3s ease;
}

.candidate-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

#visionMissionModal, #voteConfirmationModal {
    transition: all 0.3s ease;
}

.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endpush
@endsection
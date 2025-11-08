@if(isset($candidate))
<div>
    <div class="mb-6 text-center">
        <h4 class="text-lg font-semibold text-gray-800 mb-2">Paslon No. {{ $candidate->number }}</h4>
        <h2 class="text-2xl font-bold text-gray-900">{{ $candidate->chairman_name }}</h2>
        <p class="text-xl text-gray-700">& {{ $candidate->vice_chairman_name }}</p>
    </div>
    
    <div class="space-y-6">
        <div>
            <h3 class="text-xl font-bold text-blue-600 mb-3 flex items-center">
                <i class="fas fa-binoculars mr-2"></i>
                Visi
            </h3>
            <div class="bg-blue-50 p-4 rounded-lg">
                <p class="text-gray-700 whitespace-pre-line">{{ $candidate->vision }}</p>
            </div>
        </div>
        
        <div>
            <h3 class="text-xl font-bold text-green-600 mb-3 flex items-center">
                <i class="fas fa-tasks mr-2"></i>
                Misi
            </h3>
            <div class="bg-green-50 p-4 rounded-lg">
                <p class="text-gray-700 whitespace-pre-line">{{ $candidate->mission }}</p>
            </div>
        </div>
    </div>
</div>
@else
<div class="text-center py-8">
    <i class="fas fa-exclamation-triangle text-yellow-500 text-4xl mb-4"></i>
    <p class="text-gray-600">Data paslon tidak ditemukan.</p>
</div>
@endif
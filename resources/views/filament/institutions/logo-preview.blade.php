@if ($getRecord() && $getRecord()->logo)
    <div class="flex justify-center items-center bg-gray-50 p-4 rounded-xl">
        <img src="{{ Storage::url($getRecord()->logo) }}" 
             alt="Logo Institusi" 
             class="max-h-40 object-contain">
    </div>
@else
    <div class="text-center text-gray-500 italic p-4">
        Belum ada logo
    </div>
@endif

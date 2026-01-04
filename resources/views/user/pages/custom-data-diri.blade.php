<x-filament::page>
    <div class="flex justify-end mb-4">
        <x-filament::button tag="a"
            href="{{ \App\Filament\Clusters\Profil\Resources\DataDiriResource::getUrl('create') }}">
            Isi Data Diri
        </x-filament::button>
    </div>

    <div class="flex items-center justify-center h-64 bg-white rounded shadow">
        <div class="text-center">
            <x-heroicon-o-x-circle class="w-12 h-12 mx-auto text-gray-400" />
            <p class="mt-2 text-lg font-medium text-gray-600">Tidak ada data yang ditemukan</p>
        </div>
    </div>
</x-filament::page>

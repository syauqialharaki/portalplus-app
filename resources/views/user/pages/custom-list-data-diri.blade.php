<x-filament::page>
    @foreach ($this->getTableQuery()->get() as $record)
        <x-filament::section heading="Data Diri" class="mb-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 fi-fo-component-ctn">
                <x-detail-item label="Nama Lengkap" :value="$record->user->name" />
                <x-detail-item label="Nama Panggilan" :value="$record->nama_panggilan" />
                <x-detail-item label="Jenis Kelamin" :value="$record->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'" />
                <x-detail-item label="Tempat Lahir" :value="$record->tempat_lahir" />
                <x-detail-item label="Tanggal Lahir" :value="\Carbon\Carbon::parse($record->tanggal_lahir)->translatedFormat('d F Y')" />
                <x-detail-item label="Status Pernikahan" :value="$record->status_pernikahan ?? '-'" />
                <x-detail-item label="No. HP" :value="$record->no_hp" />
                <x-detail-item label="Agama" :value="$record->agama" />
                <x-detail-item label="Program Studi" :value="$record->prodi" />
                <x-detail-item label="Institusi" :value="$record->institusi" />
                <x-detail-item label="Tahun Angkatan" :value="$record->tahun_angkatan" />
            </div>
        </x-filament::section>

        <x-filament::section heading="Kepribadian & Ketertarikan" class="mb-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 fi-fo-component-ctn">
                <x-detail-item label="Hobi" :value="$record->hobi ?? '-'" />
                <x-detail-item label="Minat" :value="$record->minat ?? '-'" />
                <x-detail-item label="Keahlian" :value="$record->keahlian ?? '-'" />
                <x-detail-item label="Cita-Cita" :value="$record->cita_cita ?? '-'" />
            </div>
        </x-filament::section>

        {{-- <x-filament::section heading="Media" class="mb-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 fi-fo-component-ctn">
                <x-detail-item label="Foto Profil" :value="view('components.profile-photo', ['photo' => $record->foto_profil])" />
            </div>
        </x-filament::section> --}}
    @endforeach
</x-filament::page>

@php
    $user = $this->getUser();
    $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : null;
@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            {{-- Avatar --}}
            <div class="shrink-0">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-primary-500 shadow-lg">
                @else
                    <div class="w-24 h-24 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center border-4 border-primary-500 shadow-lg">
                        <span class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Selamat datang, {{ $user->name }}!
                </h2>
                
                <div class="mt-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Jabatan --}}
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                        <x-heroicon-o-briefcase class="w-5 h-5 text-primary-500" />
                        <div>
                            <div class="text-xs text-gray-500">Jabatan</div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $user->position?->name ?? '-' }}
                            </div>
                        </div>
                    </div>

                    {{-- Satuan Kerja --}}
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                        <x-heroicon-o-building-office class="w-5 h-5 text-primary-500" />
                        <div>
                            <div class="text-xs text-gray-500">Satuan Kerja</div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $user->unit?->name ?? '-' }}
                            </div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                        <x-heroicon-o-envelope class="w-5 h-5 text-primary-500" />
                        <div>
                            <div class="text-xs text-gray-500">Email</div>
                            <div class="font-semibold text-gray-900 dark:text-white">
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>
                </div>

                @if ($user->role === 'admin')
                    <div class="mt-3">
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            <x-heroicon-s-shield-check class="w-4 h-4" />
                            Administrator
                        </span>
                    </div>
                @endif
            </div>

            {{-- Edit Profile Button --}}
            <div class="shrink-0">
                <x-filament::button tag="a" href="{{ route('filament.admin.pages.profile') }}" icon="heroicon-o-pencil-square">
                    Edit Profil
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

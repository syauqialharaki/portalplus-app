<x-filament-panels::page.simple>

    <body
        style="background-image: url('/images/bg-login.png');background-size: cover; background-repeat: no-repeat; background-position: center">
        <div class="flex items-center justify-center my-10">
            <div class="flex items-center justify-center gap-6 p-4">
                <img src="{{ asset('storage/logos/institutions/01K96R6V3ZP85JT0SBC60Y6KAT.png') }}" alt="institution-logo" class="h-16 w-auto" />
                <div class="w-px h-16 bg-gray-300"></div>
                <div class="flex flex-col items-center">
                    <img src="/images/brand-logo.png" alt="brand-logo" class="h-10 w-auto" />
                    <p class="text-xs text-gray-500 mt-1 leading-tight">
                    Sistem Kepegawaian STT-NF
                    </p>
                </div>
            </div>
        </div>

        <main>
            {{ \Filament\Support\Facades\FilamentView::renderHook(
                \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                scopes: $this->getRenderHookScopes(),
            ) }}

            <x-filament-panels::form wire:submit="authenticate" id="form">
                {{ $this->form }}

                <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
            </x-filament-panels::form>

            {{ \Filament\Support\Facades\FilamentView::renderHook(
                \Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                scopes: $this->getRenderHookScopes(),
            ) }}
        </main>
        <footer class="absolute inset-x-0 bottom-0 max-w-screen-xl mx-auto p-4">
            <p class="text-center text-xs text-gray-400">
                &copy; {{ now()->year }} PortalPlus&nbsp;&nbsp;-&nbsp;&nbsp;Dibangun oleh Musyaffa Ahmad Syauqi.&nbsp;&nbsp;Versi 1.0.0
            </p>
        </footer>
        @filamentScripts
    </body>
</x-filament-panels::page.simple>

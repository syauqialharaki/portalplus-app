@props(['label', 'value'])

<div class="col-span-1">
    <div class="grid gap-y-2">
        <div class="flex items-center gap-x-3 justify-between">
            <dt class="fi-in-entry-wrp-label inline-flex items-center gap-x-3">
                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                    {{ $label }}
                </span>
            </dt>
        </div>
        <div class="grid auto-cols-fr gap-y-2">
            <div>{{ $value }}</div>
        </div>
    </div>
</div>

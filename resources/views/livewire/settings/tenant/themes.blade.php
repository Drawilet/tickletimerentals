<x-form-control class="max-w-sm mx-auto mt-10">
    <x-label for="theme" value="{{ __('tenant-settings.theme') }}" />
    <select class="select select-bordered" wire:model="data.theme" id="theme-selector">
        @foreach ($themes as $theme)
            <option value="{{ $theme }}">{{ $theme }}</option>
        @endforeach
    </select>
    <x-input-error for="theme" class="mt-2" />
</x-form-control>

<script>
    const themeSelector = document.getElementById('theme-selector');
    themeSelector.addEventListener('change', (e) => {
        const theme = e.target.value;
        document.documentElement.setAttribute('data-theme', theme);
    });
</script>

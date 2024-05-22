<div class="w-full max-w-xs mx-auto">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="grid-state">
        Seleccione
    </label>
    <select class="select select-bordered select-md w-full" wire:model="data.theme">
        @foreach ($themes as $theme)
            <option value="{{ $theme }}">{{ $theme }}</option>
        @endforeach
    </select>
    <x-input-error for="theme" />
</div>

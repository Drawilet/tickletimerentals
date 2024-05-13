<section>
    <x-form-control class="mb-4">
        <x-label for="filter.country_id" value="{{ __('region.country') }}" />
        <select class="select" wire:model='filter.country_id' wire:change='handleCountryChange'>
            <option value="">
                {{ __('region.country-placeholder') }}
            </option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}">{{ $country->name }}</option>
            @endforeach
        </select>
        <x-input-error for="filter.country_id" class="mt-2" />
    </x-form-control>

    <div class="flex justify-between px-2 gap-4">
        <div class="flex-1">
            <ul class="max-h-96 overflow-y-scroll">
                @foreach ($divisions as $division)
                    <label
                        class="flex items-center gap-4 odd:bg-base-100 py-1 px-2 cursor-pointer transition-colors hover:brightness-90"
                        wire:click='addLocation({{ $division->id }})'>
                        <input type="checkbox" class="checkbox-primary" @checked(in_array($division->id, array_column($data['locations'], 'id'))) />
                        {{ $division->name }}
                    </label>
                @endforeach
            </ul>

        </div>

</section>

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
                    @php
                        $locationData = array_filter($occupiedLocations, function ($location) use ($division) {
                            return $location['id'] == $division->id;
                        });
                        $locationData = array_values($locationData);
                        $locationData = $locationData ? $locationData[0] : null;
                    @endphp

                    <label
                        class="flex items-center gap-4  py-1 px-2 cursor-pointer {{ $locationData ? 'bg-primary bg-opacity-25' : 'odd:bg-base-100' }} transition-colors hover:brightness-90">
                        <input type="checkbox" class="checkbox-primary" wire:change='handleDataChange'
                            wire:model='data.locations' value="{{ $division->id }}" />
                        {{ $division->name }}

                        @if ($locationData)
                            <span class="text-xs text-red-500">
                                ({{ $locationData['region_name'] }})
                            </span>
                        @endif
                    </label>
                @endforeach
            </ul>

        </div>

</section>

<section>
    <form class="flex justify-between gap-2 mb-4">
        <x-form-control>
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

        <x-form-control>
            <x-label for="filter.state_id" value="{{ __('region.division') }}" />
            <select class="select" wire:model='filter.division_id' wire:change='handleDivisionChange'>
                <option value="">
                    {{ __('region.division-placeholder') }}
                </option>
                @foreach ($divisions as $division)
                    <option value="{{ $division->id }}">{{ $division->name }}</option>
                @endforeach
            </select>
            <x-input-error for="filter.division_id" class="mt-2" />
        </x-form-control>


    </form>


    <div class="flex justify-between px-2 gap-4">
        <div class="flex-1">
            <h3 class="font-bold">
                {{ __('region.available-cities') }}
            </h3>
            <ul class="max-h-96 overflow-y-scroll">
                @foreach ($cities as $city)
                    @if (!in_array($city->id, array_column($data['cities'], 'id')))
                        <li class="flex justify-between pl-2 odd:bg-base-100 py-1 cursor-pointer transition-colors hover:brightness-90"
                            wire:click='addCity({{ $city->id }})'>
                            {{ $city->name }}
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <div class="flex-1">
            <h3 class="font-bold">
                {{ __('region.selected-cities') }}
            </h3>
            <ul class="max-h-96 overflow-y-scroll">
                @foreach ($data['cities'] as $key => $city)
                    <li class="flex justify-between pl-2 odd:bg-base-100 py-1 cursor-pointer transition-colors hover:brightness-90"
                        wire:click='removeCity({{ $key }})'>
                        {{ $city['name'] }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

</section>

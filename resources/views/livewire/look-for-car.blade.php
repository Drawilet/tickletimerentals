<div class="flex items-center">
    <button class="btn btn-circle" wire:click='$set("modals.search", true)'>
        <x-icons.magnifying-glass />
    </button>

    <dialog class="modal modal-bottom sm:modal-middle" @if ($modals['search']) open @endif>
        <div class="modal-box overflow-hidden">

            <h2 class="text-3xl text-center text-blue-500 mb-4">{{ __('look-for-car.title') }}</h2>

            <x-form-control>
                <x-label for="region_id" value="{{ __('look-for-car.region') }}" />
                <select class="select select-bordered" wire:model="filters.region_id">
                    <option value="{{ null }}">{{ __('look-for-car.pick-region') }}</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error for="region_id" class="mt-2" />
            </x-form-control>

            <x-form-control>
                <x-label for="date" value="{{ __('look-for-car.start_date') }}" />
                <x-input id="date" name="date" type="datetime-local" wire:model="filters.start_date" />
                <x-input-error for="start_date" class="mt-2" />
            </x-form-control>

            <x-form-control>
                <x-label for="end_date" value="{{ __('look-for-car.end_date') }}" />
                <x-input id="end_date" name="end_date" type="datetime-local" wire:model="filters.end_date" />
                <x-input-error for="end_date" class="mt-2" />
            </x-form-control>

            <ul class="flex flex-col gap-2 list-disc mt-4 pl-6">
                @foreach ($cars as $car)
                    <li class="flex-row">
                        {{ $car->name }}
                        @if (in_array($car->id, $notAvailableCars))
                            <span class="badge badge-secondary">
                                {{ __('look-for-car.not-available') }}
                            </span>
                        @endif

                        @if (in_array($car->id, $busyCars))
                            <span class="badge badge-error">
                                {{ __('look-for-car.busy') }}
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </dialog>






</div>

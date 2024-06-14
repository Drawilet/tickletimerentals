<div>
    <x-form-control class="mb-2">
        <x-label for="region_id" value="{{ __('car.region_id') }}" />
        <select wire:model='currentRegion' name="region_id" id="region_id" class="select w-full">
            @foreach ($regions as $region)
                <option value="{{ $region->id }}">{{ $region->name }}</option>
            @endforeach
        </select>
    </x-form-control>

    @isset($currentRegion)
        <table class="table table-xs table-zebra">
            <thead>
                <tr>
                    <th>{{ __('car.rate-day') }}</th>
                    <th>{{ __('car.rate-price') }}</th>
                    <th>{{ __('car.rate-discount') }}</th>
                    <th></th>
            </thead>
            <tbody>
                @foreach ($data['rate_schedule']['region-' . $currentRegion] as $schedule)
                    <tr>
                        <td>
                            <input wire:key='rate-{{ $loop->index }}-days' type="number" class="max-w-20 input"
                                wire:model="data.rate_schedule.region-{{ $currentRegion }}.{{ $loop->index }}.days"
                                placeholder="1" disabled readonly>d
                        </td>
                        <td>
                            $<input wire:key='rate-{{ $loop->index }}-price' type="number" class="max-w-20 input"
                                wire:model="data.rate_schedule.region-{{ $currentRegion }}.{{ $loop->index }}.price"
                                wire:change='handlePriceChange("{{ $loop->index }}")'>

                        </td>
                        <td>
                            <input wire:key='rate-{{ $loop->index }}-discount' type="number" class="max-w-20 input"
                                wire:model="data.rate_schedule.region-{{ $currentRegion }}.{{ $loop->index }}.discount"
                                placeholder="%" @if ($loop->index == 0) readonly  disabled @endif>%
                        </td>
                        <td>
                            <button class="btn btn-error btn-sm" wire:click="remove({{ $loop->index }})"
                                @if ($loop->index == 0) disabled @endif>
                                <x-icons.trash class="size-4" />
                            </button>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td>
                        <input type="number" class="max-w-20 input" wire:model="days" placeholder="1">d
                    </td>
                    <td>
                        $<input type="number" class="max-w-20 input" wire:model="price" wire:change='handlePriceChange'
                            placeholder="0">
                    </td>
                    <td>
                        <input type="number" class="max-w-20 input" wire:model="discount"
                            wire:change='handleDiscountChange' placeholder="%">%
                    </td>
                    <td>
                        <button class="btn btn-primary btn-sm" wire:click="add">
                            <x-icons.plus class="size-4" />
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
        <x-input-error for="days" />
        <x-input-error for="price" />
        <x-input-error for="discount" />
    @endisset
</div>

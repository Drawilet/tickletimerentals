<section>
    <div class="mx-auto flex gap-4 items-center justify-center mb-4">
        <span class="label-text">
            {{ __('region.rate_type') }}:
        </span>
        <span class="label-text">
            {{ __('region.percentage') }}
        </span>
        <input type="checkbox" class="toggle" wire:model='usingPrice' />
        <span class="label-text">
            {{ __('region.price') }}
        </span>
    </div>

    @if (!$usingPrice)
        <x-input type="number" placeholder="{{ __('region.daily_rate') }}" wire:model="rate"
            class="input input-ghost mx-auto max-w-xs block mb-4" wire:model='data.daily_rate'
            wire:change='handleratesChange' />
    @endif

    <table class="table table-sm">
        <thead>
            <tr>
                <th> {{ __('region.rate-range') }} </th>
                <th>{{ __('region.rate-value') }}
                    @if ($usingPrice)
                        ({{ __('region.price') }})
                    @else
                        (%)
                    @endif
                </th>
                <th>{{ __('region.rate-actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['rate_schedule'] as $key => $rate)
                <tr>
                    <td>
                        {{ $rate['min'] }} {{ __('region.to') }} {{ $rate['max'] }} {{ __('region.days') }}
                    </td>
                    <td>
                        @if ($usingPrice)
                            $ {{ $rate['value'] }}
                        @else
                            {{ $rate['value'] }} %
                        @endif
                    </td>
                    <td class="flex gap-2">
                        <button wire:click="edit('{{ $key }}')"
                            class="btn btn-warning"><x-icons.pencil-square /></button>
                        <button wire:click="delete('{{ $key }}')"
                            class="btn btn-error"><x-icons.trash /></button>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td class="flex gap-4 items-center">
                    <x-input type="number" placeholder="{{ __('region.min') }}" wire:model="min" />
                    <span>{{ __('region.to') }}</span>
                    <x-input type="number" placeholder="{{ __('region.max') }}" wire:model="max" />
                </td>
                <td>
                    <x-input type="number" class="input input-ghost" wire:model="value" />
                </td>
                <td>
                    @if ($editingId === null)
                        <x-button wire:click="add()" class="btn btn-primary w-full"><x-icons.plus /> </x-button>
                    @else
                        <x-button wire:click="save" class="btn btn-primary w-full">Save</x-button>
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

</section>

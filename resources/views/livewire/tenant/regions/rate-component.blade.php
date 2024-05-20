<section>

    <x-form-control>
        <x-label for="payment.amount" value="{{ __('region.daily_rate') }}" />
        <x-input type="number" placeholder="$0" wire:model="rate" class="input input-ghost mx-auto max-w-xs block mb-4"
            min="0" wire:model='data.daily_rate' wire:change='handleratesChange' />
        <x-input-error for="data.daily_rate" class="mt-2" />
    </x-form-control>

    @if ($data['daily_rate'])
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

        <table class="table table-sm">
            <thead>
                <tr>
                    <th> {{ __('region.days') }} </th>
                    <th>{{ __('region.price') }} ($)</th>
                    <th>{{ __('region.discount') }} (%)</th>
                    <th>{{ __('region.rate-actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['rate_schedule'] as $key => $rate)
                    <tr>
                        <td>{{ $rate['days'] }} {{ __('region.days') }}</td>
                        <td>${{ $rate['price'] }}</td>
                        <td>{{ $rate['discount'] }}%</td>
                        <td class="flex gap-2">
                            <button wire:click="edit('{{ $key }}')"
                                class="btn btn-warning"><x-icons.pencil-square /></button>
                            <button wire:click="delete('{{ $key }}')"
                                class="btn btn-error"><x-icons.trash /></button>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td>
                        <x-input type="number" wire:model="days" min="2" />
                    </td>
                    <td>
                        <input type="number" class="input input-bordered w-full" wire:model="price"
                            max="{{ $data['daily_rate'] }}" wire:keyup='handlePriceChange'
                            @disabled(!$usingPrice) />
                    </td>
                    <td>
                        <input type="number" class="input input-bordered w-full" wire:model="discount"
                            wire:keyup='handleDiscountChange' @disabled($usingPrice) />
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

        <x-input-error for="days" class="mt-2" />
        <x-input-error for="price" class="mt-2" />
        <x-input-error for="discount" class="mt-2" />
    @endif

</section>

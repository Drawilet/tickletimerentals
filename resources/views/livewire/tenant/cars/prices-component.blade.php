<table class="table table-sm">
    <thead>
        <tr>
            <th> {{ __('car-lang.price-kilometers') }} </th>
            <th>{{ __('car-lang.price') }}</th>
            <th>{{ __('car-lang.price-actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['prices'] as $key => $price)
            <tr>
                <td>
                    @if ($editingId === $key)
                        <x-input type="number" wire:model="kilometers" />
                    @else
                        {{ $price['kilometers'] }}
                    @endif
                </td>
                <td>
                    @if ($editingId === $key)
                        <x-input type="number" wire:model="price" />
                    @else
                        {{ $price['price'] }}
                    @endif
                </td>
                <td class="flex gap-2">
                    <button wire:click="edit('{{ $key }}')"
                        class="btn btn-warning"><x-icons.pencil-square /></button>
                    <button wire:click="delete('{{ $key }}')" class="btn btn-error"><x-icons.trash /></button>
                </td>
            </tr>
        @endforeach
        <tr>
            <td>
                <x-input type="number" class="" wire:model="kilometers" />
            </td>
            <td>
                <x-input type="number" class="input input-ghost" wire:model="price" />
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

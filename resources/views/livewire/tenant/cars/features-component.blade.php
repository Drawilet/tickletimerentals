<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Value</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['features'] as $key => $feature)
            <tr>
                <td>
                    @if ($editingId === $key)
                        <x-input type="text" wire:model="name"/>
                    @else
                        {{ $feature['name'] }}
                    @endif
                </td>
                <td>
                    @if ($editingId === $key)
                        <x-input type="text" wire:model="value"/>
                    @else
                        {{ $feature['value'] }}
                    @endif
                </td>
                <td>

                    <button wire:click="edit('{{ $key }}')" class="btn btn-primary"><x-icons.pencil-square/></button>
                    <button wire:click="delete('{{ $key }}')" class="btn btn-danger"><x-icons.trash/></button>
                </td>
            </tr>
        @endforeach
        <tr>
            <td>
                <x-input type="text" class="" wire:model="name"/>
            </td>
            <td>
                <x-input type="text" class="input input-ghost" wire:model="value"/>
            </td>
            <td>
                @if ($editingId === null)
                    <x-button wire:click="add()" class="btn btn-success">Add</x-button>
                @else
                    <x-button wire:click="save" class="btn btn-success">Save</x-button>
                @endif
            </td>
        </tr>
    </tbody>
</table>

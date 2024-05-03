<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Value</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data['features'] as $feature)
            <tr>
                <td>{{ $feature->name }}</td>
                <td>{{ $feature->value }}</td>
                <td>
                    <button wire:click="edit({{ $feature->id }})" class="btn btn-primary">Edit</button>
                    <button wire:click="delete({{ $feature->id }})" class="btn btn-danger">Delete</button>
                </td>
            </tr>
        @endforeach

        <tr>
            <td>
                <input type="text" class="input input-ghost" wire:model="name">
            </td>
            <td>
                <input type="text" class="input input-ghost" wire:model="value">
            </td>
            <td>

            </td>
        </tr>

    </tbody>
</table>

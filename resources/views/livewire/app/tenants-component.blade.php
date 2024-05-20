<div class="overflow-x-auto mx-auto w-full">
    <table class="table w-full">
        <thead>
            <tr>
                <th>Profile Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tenants as $key => $tenant)
                <tr class="{{ $tenant->suspended ? 'scale-95 opacity-50 border border-primary' : '' }}">
                    <td>
                        <div class="mask mask-squircle w-12 h-12">
                            <img src="{{ $tenant->profile_image }}" alt="Foto" />
                        </div>
                    </td>
                    <td>{{ $tenant->name }}</td>
                    <td>{{ $tenant->email }}</td>
                    <td>
                        <a href="{{ route('app.tenants.show', ['id' => $tenant->id]) }}" class="btn btn-primary">
                            <x-icons.arrow-top-right-on-square />
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

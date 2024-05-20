<dialog id="product_modal" class="modal modal-bottom md:modal-middle" {{ $modals['addProduct'] ? 'open' : '' }}>
    <div class="modal-box">
        <h3 class="font-bold text-lg">{{ __('calendar.add-product') }}</h3>

        <input type="search" class="mt-2 input w-full" placeholder="{{ __('calendar.search-product') }}"
            wire:model="filters.product_name">
        <table class="table w-full">

            <tbody>
                @foreach ($filteredProducts as $product)
                    <tr class="hover cursor-pointer" wire:click="productAction('{{ $product->id }}', 'add')">
                        <td>{{ $product->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="modal-action">
            <button class="btn" wire:click="Modal('addProduct', false)">{{ __('calendar.close') }}</button>
        </div>
    </div>
</dialog>

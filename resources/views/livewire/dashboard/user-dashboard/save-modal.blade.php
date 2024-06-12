<input type="checkbox" class="modal-toggle" @checked($modals['save']) />
<div class="modal" role="dialog">
    <div class="modal-box">
        <div class="">
            <div>
                <h2>
                    {{ !isset($rent['id']) ? __('calendar.new-rent') : __('calendar.update-rent') }}
                </h2>
            </div>

            <div class="flex justify-between items-center">
                <h3 class="text-2xl">
                    @isset($rent['start_date'])
                        {{ \Carbon\Carbon::parse($rent['start_date'])->format('d') }},
                        {{ __('month.' . strtolower(\Carbon\Carbon::parse($rent['start_date'])->format('F'))) }},
                        {{ \Carbon\Carbon::parse($rent['start_date'])->format('Y') }}
                    @endisset

                    @isset($rent['end_date'])
                        - {{ \Carbon\Carbon::parse($rent['end_date'])->format('d') }},
                        {{ __('month.' . strtolower(\Carbon\Carbon::parse($rent['end_date'])->format('F'))) }},
                        {{ \Carbon\Carbon::parse($rent['end_date'])->format('Y') }}
                    @endisset

                </h3>

                @isset($rent['id'])
                    <button class="btn btn-error" wire:click="Modal('delete', true)">
                        <x-icons.trash />
                    </button>
                @endisset

            </div>

            <div class="mt-4 text-sm ">
                {{-- INFORMATION --}}
                <section>
                    <x-form-control>
                        <x-label for="name" value="{{ __('calendar.rent-name') }}" />
                        <x-input id="name" name="name" wire:model="rent.name" wire:loading.attr="disabled"
                            wire:target="saveRent" />
                        <x-input-error for="name" class="mt-2" />
                    </x-form-control>

                    <x-form-control>
                        <x-label for="car_id" value="{{ __('calendar.car') }}" />
                        <select class="select select-bordered" wire:model="rent.car_id" wire:loading.attr="disabled"
                            wire:target="saveRent">
                            <option value="{{ null }}">{{ __('calendar.pick-car') }}</option>
                            @foreach ($cars as $car)
                                <option value="{{ $car->id }}">{{ $car->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error for="car_id" class="mt-2" />
                    </x-form-control>

                    <x-form-control>
                        <x-label for="region_id" value="{{ __('calendar.region') }}" />
                        <select class="select select-bordered" wire:model="rent.region_id" wire:loading.attr="disabled"
                            wire:target="saveRent">
                            <option value="{{ null }}">{{ __('calendar.pick-region') }}</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}">{{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error for="region_id" class="mt-2" />
                    </x-form-control>

                    <x-form-control>
                        <x-label for="customer_id" value="{{ __('calendar.customer') }}" />
                        <div class="flex items-center">
                            <div x-data="{ open: false }" class="flex-grow relative">
                                <input type="text" class="input input-bordered w-full"
                                    wire:model.debounce.500ms="searchTerm" wire:keyup.debounce.500ms="filterUpdated"
                                    wire:target="saveRent" @focus="open = true" @click.away="open = false">
                                <div class="absolute z-10 mt-2 w-full  shadow-md h-44 overflow-y-scroll" x-show="open">
                                    <ul class="p-1 menu dropdown-content bg-base-200 rounded-box ">
                                        @foreach ($customers as $customer)
                                            <li class="cursor-pointer hover:bg-base-300 p-1 w-full"
                                                wire:click="SetCustomer('{{ $customer->id }}')">
                                                {{ $customer->firstname }} {{ $customer->lastname }}</li>
                                        @endforeach
                                        @if ($CUSTOMER_PER_PAGE)
                                            <div x-data="{ shown: false }" x-intersect="shown = true; $wire.loadMore()">
                                                <div x-show="shown" class="flex justify-center items-center mt-5">
                                                    <p>Loading more...</p>
                                                </div>
                                            </div>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                            <button class="btn btn-neutral ml-2" wire:click="Modal('newCustomer', true)">
                                <x-icons.plus />
                            </button>
                        </div>
                        <x-input-error for="customer_id" class="mt-2" />
                    </x-form-control>

                    <x-form-control>
                        <x-label for="date" value="{{ __('calendar.start_date') }}" />
                        <x-input id="date" name="date" type="date" wire:model="rent.start_date"
                            wire:loading.attr="disabled" wire:target="saveRent" />
                        <x-input-error for="start_date" class="mt-2" />
                    </x-form-control>

                    <x-form-control>
                        <x-label for="end_date" value="{{ __('calendar.end_date') }}" />
                        <x-input id="end_date" name="end_date" type="date" wire:model="rent.end_date"
                            wire:loading.attr="disabled" wire:target="saveRent" />
                        <x-input-error for="end_date" class="mt-2" />
                    </x-form-control>

                    <x-form-control>
                        <x-label for="notes" value="{{ __('calendar.notes') }}" />
                        <textarea id="notes" name="notes" class="textarea textarea-bordered" wire:model="rent.notes"
                            wire:loading.attr="disabled" wire:target="saveRent"></textarea>
                        <x-input-error for="notes" class="mt-2" />
                    </x-form-control>

                    <x-form-control>
                        <x-label for="tax_id" value="{{ __('calendar.tax') }}" />
                        <select class="select select-bordered" wire:model="rent.tax_id" wire:loading.attr="disabled"
                            wire:target="saveRent">
                            <option value="{{ null }}">{{ __('calendar.pick-tax') }}</option>
                            @foreach ($taxes as $tax)
                                <option value="{{ $tax->id }}">{{ $tax->code }}
                                    ({{ $tax->rate }}%)
                                </option>
                            @endforeach
                        </select>
                        <x-input-error for="tax_id" class="mt-2" />
                    </x-form-control>

                </section>

                <section class="flex flex-wrap gap-3">
                    @foreach ($rent['photos'] as $key => $file)
                        <div class="relative">
                            <button wire:click="Modal('notes',true,'{{ $key }}')">
                                <img src="{{ $file['url'] }}" alt=""
                                    class="w-full max-w-xs max-h-20 object-cover rounded-md cursor-pointer " />
                            </button>
                            {{-- <button
                                class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 bg-base-200 rounded-full text-red-500"
                                wire:click="Modal('notes', true )">
                                <x-icons.x-circle />
                            </button> --}}
                        </div>
                    @endforeach

                    <input type="file" id="imgs-id" class="hidden" wire:model="photo" name="photo" />

                    <label for="imgs-id" class="border border-dashed rounded-md p-6 cursor-pointer">
                        <x-icons.plus name="plus" class="w-6 h-6" />
                    </label>

                    <div class="w-full" {{-- wire:loading wire:target="uploadedPhotos" --}}>
                    </div>
                </section>



                {{-- PRODUCTS --}}
                <section class="mt-2">
                    <h2 class="text-xl flex items-center">
                        <button class="btn mr-2" wire:click="Modal('addProduct', true)">
                            <x-icons.plus />
                        </button>
                        {{ __('calendar.products') }}
                    </h2>
                    <div class="overflow-x-auto">
                        <table class="table w-full table-zebra ">
                            <thead>
                                <tr>
                                    <th class="w-3/4">{{ __('calendar.product-name') }}</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>

                                    <th></th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rent['products'] as $rent_product)
                                    @php
                                        $product = $products->find($rent_product['product_id']);
                                        $price = $product->price * $rent_product['quantity'];
                                        $id = $product->id;
                                    @endphp
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td class="join">
                                            <button class="btn"
                                                wire:click="productAction('{{ $id }}', 'decrease')">-</button>
                                            <span class="btn">{{ $rent_product['quantity'] }}</span>
                                            <button class="btn"
                                                wire:click="productAction('{{ $id }}', 'add')">+</button>
                                        </td>
                                        <td>x</td>
                                        <td>${{ $product->price }}</td>
                                        <td>=</td>
                                        <td>${{ $price }}</td>
                                        <td>
                                            <button class="btn "
                                                wire:click="productAction('{{ $id }}', 'remove')">
                                                <x-icons.trash />
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

        </div>


        <div class="flex flex-row items-center justify-between  px-6 py-4">

            <table class="">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="pr-8">{{ __('calendar.subtotal') }}</td>
                        <td class="text-right"> ${{ number_format($this->rent['subtotal'], 2) }}</td>
                    </tr>
                    <tr>
                        <td class="pr-8 ">{{ __('calendar.taxes') }}</td>
                        <td class="text-right"> ${{ number_format($this->rent['tax_amount'], 2) }}</td>
                    </tr>
                    <tr class=" text-xl w-full border-t border-base-300 mt-4">
                        <td class="pr-8">{{ __('calendar.total') }}</td>
                        <td class="text-right"> ${{ number_format($this->rent['total'], 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <button class="btn btn-primary px-8" wire:click="saveRent" wire:loading.attr="disabled">
                <span wire:loading wire:target="saveRent">
                    {{ __('auth.cargando') }}...
                </span>
                <span wire:loading.remove wire:target="saveRent">
                    {{ __('calendar.save') }}
                </span>
            </button>


        </div>

        <div class="w-11/12 mx-auto flex md:hidden gap-2">
            <button class="w-1/3 btn btn-neutral " wire:click='Modal("save",false)'>
                {{ __('calendar.close') }}
            </button>

            @isset($rent['id'])
                <button class="w-2/3 btn btn-secondary" wire:click="Modal('payments', true)">
                    {{ __('calendar.show-payments') }}
                </button>
            @endisset

        </div>
    </div>
    @include('livewire.dashboard.user-dashboard.save-modal.products-modal')
    @include('livewire.dashboard.user-dashboard.save-modal.payments-modal')
    @include('livewire.dashboard.user-dashboard.save-modal.new-customer-modal')
    @include('livewire.dashboard.user-dashboard.save-modal.notesPhoto')

    <label class="modal-backdrop" wire:click="Modal('save', false)">Close</label>
</div>

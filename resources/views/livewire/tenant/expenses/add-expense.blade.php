   <x-dialog-modal wire:model.defer="modals.add-expense">
       <x-slot name="title">
           {{ __('expense.add-expense') }}
       </x-slot>

       <x-slot name="content">
           <h2 class="mb-2">
               {{ __('expense.add-detail') }}
           </h2>
           <div class="join w-full gap-2 mb-2">
               <select wire:model='filter.supplier_id' wire:change='filterProducts' class="select select-bordered flex-1">
                   <option value='{{ null }}'>{{ __('expense.supplier') }}</option>
                   @foreach ($suppliers as $supplier)
                       <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                   @endforeach
               </select>
               <select wire:model='filter.product_id' class="select select-bordered flex-1">
                   <option value='{{ null }}'>{{ __('expense.product') }}</option>
                   @isset($products)
                       @foreach ($products as $product)
                           <option value="{{ $product->id }}">{{ $product->name }}</option>
                       @endforeach
                   @endisset
               </select>
               <button class="btn btn-secondary" wire:click='addDetail'>
                   <x-icons.plus class="w-6 h-6" />
               </button>
           </div>

           <span class="opacity-80 mb-4 block">
               {{ __('expense.db-click-to-remove') }}
           </span>

           <table class="table table-xs table-zebra mb-2">
               <thead>
                   <tr>
                       <th></th>
                       <th>{{ __('expense.sku') }}</th>
                       <th>{{ __('expense.concept') }}</th>
                       <th>{{ __('expense.quantity') }}</th>
                       <th>{{ __('expense.unit') }}</th>
                       <th>{{ __('expense.price') }}</th>
                       <th>{{ __('expense.iva') }}</th>
                       <th>{{ __('expense.amount') }}</th>

                   </tr>
               </thead>
               <tbody>
                   @foreach ($data['details'] as $detail)
                       <tr class="hover" wire:dblclick='removeDetail({{ $loop->index }})'>
                           <td>
                               {{ $loop->index + 1 }}
                           </td>
                           <td>
                               {{ $data['details'][$loop->index]['sku'] }}
                           </td>
                           <td>
                               {{ $data['details'][$loop->index]['concept'] }}
                           </td>
                           <td>
                               <input type="text" wire:model='data.details.{{ $loop->index }}.quantity'
                                   class="input max-w-16 px-1" />
                           </td>
                           <td>
                               {{ $data['details'][$loop->index]['unit'] }}
                           </td>
                           <td>
                               <input type="text" wire:model='data.details.{{ $loop->index }}.price'
                                   class="input max-w-16 px-1" />
                           </td>
                           <td>
                               {{ $data['details'][$loop->index]['iva'] }}%
                           </td>
                           <td>
                               @if ($data['details'][$loop->index]['quantity'] && $data['details'][$loop->index]['price'])
                                   ${{ number_format($data['details'][$loop->index]['quantity'] * $data['details'][$loop->index]['price'], 2) }}
                               @endif
                           </td>
                       </tr>
                   @endforeach


               </tbody>
           </table>

           <div class="flex flex-col items-end justify-center">
               <div>
                   <span>{{ __('expense.subtotal') }}</span>
                   <span>${{ number_format($data['subtotal'], 2) }}</span>
               </div>

               <div>
                   <span>{{ __('expense.total') }}</span>
                   <span>${{ number_format($data['total'], 2) }}</span>
               </div>
           </div>


       </x-slot>

       <x-slot name="footer">
           <button wire:click='$set("modals.add-expense", false)' type="button"
               class="btn w-28 mr-2">{{ __('expense.cancel') }}</button>
           <button class="btn btn-primary px-8" wire:click="saveExpense" wire:loading.attr="disabled">
               <span wire:loading wire:target="saveExpense">
                   {{ __('expense.loading') }}...
               </span>
               <span wire:loading.remove wire:target="saveExpense">
                   {{ __('calendar.save') }}
               </span>
           </button>

           @php
               $err = $errors->toArray();
           @endphp
           @if (count($err) > 0)
               <script>
                   function scrollToFirstError() {
                       const errors = @json($err);
                       const keys = Object.keys(errors);
                       const firstKey = keys[0];
                       if (!firstKey) return;

                       const errorElement = document.getElementById(firstKey);
                       if (!errorElement) return;

                       errorElement.parentElement.scrollIntoView({
                           behavior: 'smooth',
                           block: 'center',
                       });
                   }
                   scrollToFirstError();
               </script>
           @endif

       </x-slot>
   </x-dialog-modal>

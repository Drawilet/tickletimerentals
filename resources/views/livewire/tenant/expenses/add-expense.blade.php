   <x-dialog-modal wire:model.defer="modals.add-expense">
       <x-slot name="title">
           {{ __('expense.add-expense') }}
       </x-slot>

       <x-slot name="content">
           <x-form-control class="mb-4">
               <x-label for="" value="{{ __('expense.supplier') }}" />
               <select wire:model='data.supplier_id' class="select select-bordered flex-1 w-full">
                   <option value='{{ null }}'>{{ __('expense.pick-supplier') }}</option>
                   @foreach ($suppliers as $supplier)
                       <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                   @endforeach
               </select>
               <x-input-error for="data.supplier_id" />
           </x-form-control>

           <h2 class="text-xl mb-4">
               {{ __('expense.details') }}
           </h2>

           <table class="table table-xs table-zebra mb-2">
               <thead>
                   <tr>
                       <th></th>
                       <th>{{ __('expense.category') }}</th>
                       <th>{{ __('expense.iva') }}</th>
                       <th>{{ __('expense.amount') }}</th>
                       <th></th>
                   </tr>
               </thead>
               <tbody>
                   @foreach ($data['details'] as $detail)
                       <tr class="hover">
                           <td>
                               {{ $loop->index + 1 }}
                           </td>
                           <td>
                               {{ $categories->where('id', $detail['category_id'])->first()->name }}
                           </td>

                           <td>
                               {{ $data['details'][$loop->index]['iva'] }}%
                           </td>
                           <td>
                               <input type="number" wire:model='data.details.{{ $loop->index }}.price'
                                   class="input max-w-16 px-1" />
                           </td>
                           <td>
                               <button wire:click='removeDetail({{ $loop->index }})' type="button"
                                   class="btn btn-sm btn-ghost">
                                   <x-icons.trash />
                               </button>
                           </td>

                       </tr>
                   @endforeach

                   <tr>
                       <td>
                           {{ count($data['details']) + 1 }}
                       </td>
                       <td>
                           <select wire:model='detail.category_id' wire:change='updateIva'
                               class="select select-bordered flex-1 w-full">
                               <option value="">{{ __('expense.pick-category') }}</option>
                               @foreach ($categories as $category)
                                   <option value="{{ $category->id }}">{{ $category->name }}</option>
                               @endforeach
                           </select>
                       </td>
                       <td>
                           <input type="number" wire:model='detail.iva' class="input max-w-16 px-1" />
                       </td>
                       <td>
                           <input type="number" wire:model='detail.price' class="input max-w-16 px-1" />
                       </td>
                       <td>
                           <button wire:click='addDetail' type="button" class="btn btn-sm btn-ghost">
                               <x-icons.plus />
                           </button>
                       </td>
                   </tr>


               </tbody>
           </table>

           <x-input-error for="data.details" />

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

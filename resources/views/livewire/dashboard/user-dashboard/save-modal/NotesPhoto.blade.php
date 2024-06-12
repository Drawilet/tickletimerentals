@isset($selectedPhoto)
    <div
        class="{{ $modals['notes'] ? 'block' : 'hidden' }} w-11/12 lg:w-96 absolute left-0 top-0 h-full p-4 bg-base-100 text-base-content">
        <h3 class="text-xl text-center">Notas</h3>
        <ul class="menu">
            <div class="flex items-center justify-center max-h-48 overflow-hidden rounded-lg">
                {{-- @dump($rent['photos'][$selectedPhoto]) --}}
                <img src="{{ $rent['photos'][$selectedPhoto]['url'] }}" class="max-h-full max-w-full rounded-lg" />
            </div>
            <label class="divider divider-base-200"></label>
            <li>
                <x-form-control>
                    <x-label for="damage" value="{{ __('calendar.daño') }}" />
                    <input type="checkbox" class="checkbox" wire:model="rent.photos.{{ $selectedPhoto }}.damage">
                    <x-input-error for="damage" class="mt-2" />
                </x-form-control>

                <x-form-control>
                    <x-label for="notes" value="{{ __('calendar.notes') }}" />
                    <textarea id="notes" name="notes" wire:model="rent.photos.{{ $selectedPhoto }}.notes"
                        type="text" class="textarea"></textarea>
                    <x-input-error for="notes" class="mt-2" />
                </x-form-control>
            </li>
        </ul>
    </div>
@endisset

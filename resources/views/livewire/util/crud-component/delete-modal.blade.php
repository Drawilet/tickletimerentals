<x-dialog-modal wire:model="modals.delete">
    <x-slot name="title">

    </x-slot>
    <x-slot name="content">
        <h2>{{ __('show.h1') }}"{{ $data[$mainKey] }}"?</h2>
    </x-slot>
    <x-slot name="footer">
        <button wire:click="Modal('delete', false)" type="button" class="btn w-28">{{ __('show.cancel') }}</button>
        <button wire:click.prevent="delete()" type="button"
            class="btn btn-error w-28 ml-2">{{ __('show.delete') }}</button>

    </x-slot>
</x-dialog-modal>

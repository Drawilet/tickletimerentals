<div class="w-full relative">
    <h2 class="text-2xl font-bold text-center my-5">{{ __('tenant-settings.title') }}</h2>

    @include('livewire.settings.tenant.form')
    @include('livewire.settings.tenant.plans')
    @include('livewire.settings.tenant.transactions')

    <x-button class="block btn btn-primary mt-4 w-full max-w-sm mx-auto"
        wire:click="save">{{ __('tenant-settings.save') }}</x-button>
</div>

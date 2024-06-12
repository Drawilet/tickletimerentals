<div class="overflow-x-auto w-full">
    @include('livewire.tenant.expenses.add-expense')

    <div class="flex h-48 gap-10 justify-center items-center">
        <livewire:livewire-area-chart :area-chart-model="$areaChartModel" key="{{ $areaChartModel->reactiveKey() }}" />
        <button class="btn btn-primary" wire:click="$set('modals.add-expense', true)">
            <x-icons.plus class="size-6" />
            {{ __('expense.add-expense') }}
        </button>
    </div>


    @include('livewire.tenant.expenses.expenses-table')
</div>

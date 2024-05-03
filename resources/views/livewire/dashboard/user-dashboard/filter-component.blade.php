<div>
    <style>
        .checkbox:checked {
            background: currentColor;
        }
    </style>
    <div class="dropdown md:dropdown-end">
        <div tabindex="0" role="button" class="btn m-1 px-10">
            @if (count($cars) == count($filters['cars']))
                {{ __('filter-lang.All') }}
            @else
                {{ count($filters['cars']) }} {{ __('filter-lang.Cars') }}
            @endif
        </div>

        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
            <li>
                <label for="all">
                    <input id="all" type="checkbox" class="checkbox" wire:click="toggleSpace('all')"
                        @checked(count($cars) == count($filters['cars'])) />
                    {{ __('filter-lang.All') }}
                </label>
            </li>
            @foreach ($cars as $car)
                <li>
                    <label for="{{ $car->id }}">
                        <input id="{{ $car->id }}" type="checkbox" class="checkbox"
                            @if (in_array($car->id, $filters['cars'])) style="background: {{ $car->color }}" @endif
                            wire:click="toggleSpace('{{ $car->id }}')" @checked(in_array($car->id, $filters['cars'])) />
                        {{ $car['name'] }}
                    </label>
                </li>
            @endforeach
        </ul>
    </div>
</div>

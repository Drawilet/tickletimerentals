<?php

namespace App\Http\Livewire\Dashboard\UserDashboard;

use App\Http\Traits\WithCrudActions;
use App\Models\Car;
use App\Models\Rent;
use Asantibanez\LivewireCalendar\LivewireCalendar;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CalendarComponent extends LivewireCalendar
{
    use WithCrudActions;
    protected $listeners = ['update-rent' => 'updateRent'];

    public $currentEvents, $currentDate;

    public function getCurrentEvents()
    {
        $this->currentEvents = Rent::where('start_date', '>=', $this->gridStartsAt)
            ->where('end_date', '<=', $this->gridEndsAt)
            ->when(count($this->filters['cars']) > 0, function ($query) {
                return $query->whereIn('car_id', $this->filters['cars']);
            })
            ->get();
    }

    public function updateRent($event)
    {
        $key = $this->currentEvents->search(function ($item) use ($event) {
            return $item->id == $event['id'];
        });

        if ($key !== false) {
            $this->currentEvents[$key] = Rent::find($event['id']);
        } else {
            $this->currentEvents->push(Rent::find($event['id']));
        }
    }

    public function events(): Collection
    {
        if ($this->currentDate != $this->gridStartsAt) {
            $this->currentDate = $this->gridStartsAt;
            $this->getCurrentEvents();
        }

        return collect(
            $this->currentEvents
                ->map(function ($event) {
                    return [
                        'id' => $event->id,
                        'title' => $event->name,
                        'location' => $event->car->name,
                        'description' => $event->notes,
                        'date' => $event->start_date,
                        'color' => $event->car->color,
                        'start_time' => $event->start_time,
                        'isDraft' => isset ($event->payments) && count($event->payments) == 0,
                    ];
                })
                ->sortBy('start_date')
                ->values()
                ->toArray(),
        );
    }

    public function onDayClick($year, $month, $day)
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $day = str_pad($day, 2, '0', STR_PAD_LEFT);

        $this->emit('Modal', 'save', true, [
            'start_date' => $year . '-' . $month . '-' . $day,
            "end_date" => $year . '-' . $month . '-' . $day,
        ]);
    }

    public function onEventClick($eventId)
    {
        $this->emit('Modal', 'save', true, [
            'id' => $eventId,
        ]);
    }

    public $filters = [
        'cars' => [],
    ];

    public function afterMount($extras = [])
    {
        $this->addCrud(Car::class, ['useItemsKey' => false, 'get' => true]);

        $this->filters['cars'] = $this->cars->pluck('id')->toArray();
    }

    public function toggleSpace($spaceId)
    {
        if ($spaceId == 'all') {
            if (count($this->cars) == count($this->filters['cars'])) {
                $this->filters['cars'] = [];
            } else {
                $this->filters['cars'] = $this->cars->pluck('id')->toArray();
            }

            $this->emit('update-filters', $this->filters);
            return;
        }

        if (!in_array($spaceId, $this->filters['cars'])) {
            $this->filters['cars'][] = $spaceId;
        } else {
            $this->filters['cars'] = array_diff($this->filters['cars'], [$spaceId]);
        }
    }
}

<?php

namespace App\Http\Livewire\Tenant;

use App\Http\Livewire\Util\CrudComponent;
use App\Models\Rent;
use App\Models\RentPayment;

class RentPaymentsComponent extends CrudComponent
{
    public $events = ['beforeSave', 'specialValidator'];

    public function beforeSave($data)
    {
        $data['user_id'] = auth()->user()->id;
        return $data;
    }
    public function mount()
    {
        $this->setup(RentPayment::class, [
            'mainKey' => 'id',
            'types' => [
                'rent_id' => [
                    'type' => 'select',
                    'options' => Rent::all()->map(function ($rent) {
                        return [
                            'value' => $rent->id,
                            'label' => $rent->name,
                        ];
                    }),
                ],
                'notes' => [
                    'type' => 'text',
                ],
                'amount' => [
                    'type' => 'number',
                ],
            ],
        ]);
    }

    public function getTotal($rent)
    {
        $total = $rent['price'] ?? 0;
        foreach ($rent['products'] as $data) {
            $total += $this->products->find($data['product_id'])->price * $data['quantity'];
        }
        return $total;
    }

    public function getRemaining($rent)
    {
        $remaining = $this->getTotal($rent);
        foreach ($rent['payments'] as $payment) {
            $remaining -= $payment['amount'];
        }
        return $remaining;
    }

    public function specialValidator($payment)
    {
        $rent = Rent::find($payment['rent_id']);
        $remaining = $this->getRemaining($rent);

        return [
            'amount' => 'numeric|max:' . $remaining,
        ];
    }
}

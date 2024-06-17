<?php

namespace App\Http\Livewire\Settings;

use App\Http\Traits\WithValidations;
use App\Models\Plan;
use App\Models\Tenant;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;

class TenantSettingsComponent extends Component
{
    use WithFileUploads, WithValidations;
    public $plans;

    public $data,
    $initialData = [
        'background_image' => '',
        'profile_image' => '',

        'name' => '',
        'description' => '',
        'phone' => '',
        'email' => '',
        'theme' => '',

        'plan_id' => '',
        'next_plan_id' => '',
    ];
    public $themes = [
        "light",
        "dark",
        "cupcake",
        "bumblebee",
        "emerald",
        "corporate",
        "synthwave",
        "retro",
        "cyberpunk",
        "valentine",
        "halloween",
        "garden",
        "forest",
        "aqua",
        "lofi",
        "pastel",
        "fantasy",
        "wireframe",
        "black",
        "luxury",
        "dracula",
        "cmyk",
        "autumn",
        "business",
        "acid",
        "lemonade",
        "night",
        "coffee",
        "winter",
        "dim",
        "nord",
        "sunset",
      ];

    public $oldData = [];

    public $transactions;

    public function mount()
    {
        $data = Auth()->user()->tenant;
        if ($data) {
            $this->data = $data->load('plan')->toArray();
            $this->transactions = $data->transactions;
        } else {
            $this->data = $this->initialData;
        }

        $this->oldData = $this->data;

        $this->plans = Plan::all();
    }
    public function render()
    {
        return view('livewire.settings.tenant-settings-component');
    }

    public function save()
    {
        if ($this->data['plan_id'] == '') {
            $this->data['plan_id'] = $this->data['next_plan_id'];
            $this->data['next_plan_id'] = null;
        }

        Validator::make($this->data, [
            'profile_image' => isset($this->data['id']) ? '' : 'required|image|max:2048',
            'background_image' => isset($this->data['id']) ? '' : 'required|image|max:2048',

            'name' => $this->validations['text'],
            'description' => $this->validations['textarea'],
            'phone' => $this->validations['tel'],
            'email' => $this->validations['email'],
            'theme' => 'required|in:' . implode(',', $this->themes),
            'plan_id' => 'required|exists:plans,id',
            'next_plan_id' => 'nullable|exists:plans,id',
        ])->validate();

        if (!isset($this->data['subscription_ends_at'])) {
            $this->data['subscription_ends_at'] = now();
        }

        $tenant = Tenant::updateOrCreate(['id' => Auth()->user()->tenant->id ?? null], $this->data);
        $tenant->save();

        foreach (['profile_image', 'background_image'] as $type) {
            if (gettype($this->data[$type]) == 'object') {
                $fileName = $this->data[$type]->getClientOriginalName();
                $path = '/tenant/' . $tenant->id . "/$type";

                $this->data[$type]->storeAs('/public' . $path, $fileName);

                $tenant[$type] = '/storage' . $path . '/' . $fileName;
            }
        }

        $tenant->save();

        $user = Auth()->user();
        if (!$user->tenant) {
            $user->tenant_id = $tenant->id;
            $user->wizard_step++;
            $user->save();

            $user->assignRole('tenant.admin');

            redirect()->route('dashboard.show');
        }

        $this->emit('toast', 'success', 'Data saved successfully');
    }
}

<?php

namespace App\Http\Livewire;

use App\Http\Traits\WithWizardSteps;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WizardComponent extends Component
{
    use WithWizardSteps;
    public $user;
    public $step;
    public $currentRoute;


    public function mount()
    {
        $this->user = Auth::user();
        $tenant = $this->user->tenant;
        if ($tenant && !$this->user->hasRole("tenant.admin"))
            return $this->step = null;

        $step = $this->user->wizard_step;
        $this->step = $this->steps[$step] ?? null;

        $this->currentRoute = request()->route()->getName();
        if ($this->currentRoute == "profile.show")
            $this->step = null;
    }

    public function render()
    {
        return view('livewire.wizard-component');
    }

    public function skip()
    {
        $this->user->wizard_step++;
        $this->user->save();

        $step = $this->steps[$this->user->wizard_step] ?? null;
        if ($step)
            redirect()->route($step['route']);
        else
            redirect()->route('dashboard.show');

    }
}

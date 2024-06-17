<?php

namespace App\Http\Middleware;

use App\Http\Traits\WithWizardSteps;
use Auth;
use Closure;
use Illuminate\Http\Request;

class WizardStepValidation
{
    use WithWizardSteps;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        /* $user = Auth::user();
        $tenant = $user->tenant;
        if ($tenant && !$user->hasRole("tenant.admin"))
            return $next($request);

        $step = $user->wizard_step;
        $step = $this->steps[$step] ?? null;

        $currentRoute = request()->route()->getName();
        if ($currentRoute == "profile.show")
            $step = null;

        if ($step && $currentRoute != $step['route'])
            return redirect()->route($step['route']); */

        return $next($request);
    }
}

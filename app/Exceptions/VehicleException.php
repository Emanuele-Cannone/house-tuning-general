<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class VehicleException extends Exception
{
    /**
     * Render the exception into an HTTP response.
     */
    public function render(): RedirectResponse
    {
        noty()->error('An error has occurred.');
        return Redirect::route('vehicle.create');
    }
}

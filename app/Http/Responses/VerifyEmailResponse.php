<?php

namespace App\Http\Responses;

use App\Support\Toast;
use Illuminate\Http\RedirectResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request): RedirectResponse
    {
        Toast::success('Your email has been verified successfully!');

        return redirect()->intended(route('dashboard'));
    }
}

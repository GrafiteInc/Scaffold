<?php

namespace App\Http\Controllers;

use App\Http\Requests\WaitlistSignupRequest;
use App\Services\WaitlistService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class WaitlistController extends Controller
{
    public function __construct(
        public WaitlistService $service,
    ) {}

    /**
     * Join the waitlist.
     */
    public function store(WaitlistSignupRequest $request): RedirectResponse
    {
        try {
            $this->service->join($request->validated(), $request);

            return redirect()->back()->with('waitlist_joined', true);
        } catch (Throwable $th) {
            Log::error($th->getMessage());

            return redirect()->back()->withInput()->withErrors(['Could not add you to the waitlist, please try again.']);
        }
    }
}

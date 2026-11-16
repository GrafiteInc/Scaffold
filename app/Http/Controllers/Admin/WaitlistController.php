<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WaitlistSignup;
use App\Services\WaitlistService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class WaitlistController extends Controller
{
    public function __construct(
        public WaitlistService $service,
    ) {}

    /**
     * List waitlist signups, optionally filtered by a search term.
     */
    public function index(Request $request): View
    {
        $query = $request->filled('search')
            ? app(WaitlistSignup::class)->search($request->search)
            : WaitlistSignup::query()->latest();

        $signups = $query->paginate(25)->withQueryString();

        $counts = [
            'pending' => WaitlistSignup::pending()->count(),
            'invited' => WaitlistSignup::invited()->count(),
            'registered' => WaitlistSignup::registered()->count(),
        ];

        return view('admin.waitlist.index')->with(compact('signups', 'counts'));
    }

    /**
     * Send the signup an invite to register.
     */
    public function invite(Request $request, WaitlistSignup $signup): RedirectResponse
    {
        if ($signup->registered_at) {
            return redirect()->back()->withErrors([$signup->email.' has already registered.']);
        }

        try {
            $this->service->invite($signup, $request->user());

            return redirect()->back()->with('message', 'Invitation sent to '.$signup->email);
        } catch (Throwable $th) {
            Log::error($th->getMessage());

            return redirect()->back()->withErrors(['Invitation was not sent']);
        }
    }

    /**
     * Download every signup as CSV.
     */
    public function export(): StreamedResponse
    {
        return $this->service->export();
    }

    /**
     * Remove the signup.
     */
    public function destroy(WaitlistSignup $signup): RedirectResponse
    {
        $signup->delete();

        return redirect()->route('admin.waitlist.index')->with('message', 'Successfully deleted');
    }
}

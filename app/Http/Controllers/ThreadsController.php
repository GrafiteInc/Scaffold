<?php

namespace App\Http\Controllers;

use App\Services\ConversationService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    public function __construct(public ConversationService $service) {}

    /**
     * A new chat, beside the list of earlier ones.
     *
     * @return View
     */
    public function index(Request $request)
    {
        abort_unless($this->service->enabled(), 404);

        return view('chat.index', [
            'thread' => null,
            'threads' => $this->threads($request),
        ]);
    }

    /**
     * Pick up an earlier chat where it left off.
     *
     * @return View
     */
    public function show(Request $request, int $thread)
    {
        abort_unless($this->service->enabled(), 404);

        return view('chat.index', [
            'thread' => $request->user()->threads()->findOrFail($thread),
            'threads' => $this->threads($request),
        ]);
    }

    /**
     * @return RedirectResponse
     */
    public function destroy(Request $request, int $thread)
    {
        abort_unless($this->service->enabled(), 404);

        $request->user()->threads()->findOrFail($thread)->delete();

        return redirect()->route('chat')->withMessage('Chat deleted.');
    }

    /**
     * The user's chats, most recent first, optionally filtered by search.
     *
     * @return LengthAwarePaginator
     */
    protected function threads(Request $request)
    {
        return $request->user()->threads()
            ->with(['messages' => fn ($query) => $query->reorder('id', 'desc')->limit(1)])
            ->when($request->filled('search'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('summary', 'like', '%'.$request->search.'%');
            }))
            ->latest('last_message_at')
            ->paginate(25)
            ->withQueryString();
    }
}

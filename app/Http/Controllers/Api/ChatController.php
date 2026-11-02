<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ApiChatRequest;
use App\Http\Resources\ThreadMessageResource;
use App\Http\Resources\ThreadResource;
use App\Models\ThreadMessage;
use App\Services\ConversationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChatController extends ApiController
{
    public function __construct(public ConversationService $service) {}

    /**
     * List chat threads.
     *
     * The user's conversations with the assistant, most recent first.
     *
     * @return AnonymousResourceCollection
     */
    public function threads()
    {
        abort_unless($this->service->enabled(), 404);

        return ThreadResource::collection(
            $this->user()->threads()->latest('last_message_at')->paginate(25)
        );
    }

    /**
     * Send a chat message.
     *
     * Records the message and returns the assistant's reply. Pass `thread_id`
     * to continue a thread, or `new` to start one; with neither, the message
     * joins the active thread unless it has gone idle or changed subject.
     *
     * @return JsonResponse
     */
    public function converse(ApiChatRequest $request)
    {
        abort_unless($this->service->enabled(), 404);

        $reply = $this->service->converse($this->user(), $request->validated('message'), ThreadMessage::CHANNEL_API, [
            'thread' => $request->filled('thread_id') ? $this->user()->threads()->find($request->validated('thread_id')) : null,
            'new' => $request->boolean('new'),
        ]);

        return response()->json([
            'data' => new ThreadMessageResource($reply),
            'thread' => new ThreadResource($reply->thread),
        ]);
    }
}

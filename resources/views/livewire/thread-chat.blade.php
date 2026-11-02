<div class="thread-chat">
    <div class="thread-chat-header">
        <div class="thread-chat-title">
            <span class="fw-semibold">{{ $thread?->title ?? ($thread ? 'New chat' : 'Start a chat') }}</span>
        </div>
        @if ($thread)
            <a href="{{ route('chat') }}" class="btn btn-sm btn-outline-secondary" title="Start a new chat">
                <span class="fas fa-fw fa-plus"></span> New
            </a>
        @endif
    </div>

    <div
        class="thread-chat-messages"
        x-data
        x-init="
            const toBottom = () => $el.scrollTop = $el.scrollHeight;
            toBottom();
            new MutationObserver(toBottom).observe($el, { childList: true, subtree: true });
        "
    >
        @forelse ($messages as $turn)
            <div wire:key="thread-message-{{ $turn->id }}" class="thread-chat-message {{ $turn->isFromUser() ? 'from-user' : 'from-assistant' }}">
                <div class="thread-chat-bubble">
                    @if ($turn->role === \App\Models\ThreadMessage::ROLE_SYSTEM)
                        <div class="thread-chat-kind">
                            <span class="fas fa-fw fa-bell"></span> {{ \Illuminate\Support\Str::ucfirst($turn->meta['kind'] ?? 'notification') }}
                        </div>
                    @endif
                    <div class="thread-chat-content">{!! $turn->html() !!}</div>
                    @if ($turn->toolCalls())
                        <div class="thread-chat-tools">
                            @foreach ($turn->toolCalls() as $call)
                                <span class="badge rounded-pill" title="{{ $call['result'] ?? '' }}">
                                    <span class="fas fa-fw fa-check"></span> {{ \Illuminate\Support\Str::headline($call['name']) }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="thread-chat-meta">
                    <span class="fas fa-fw {{ \App\Models\ThreadMessage::channelIcon($turn->channel) }}"></span>
                    {{ $turn->created_at->timezone($timezone)->format('M j, g:i A') }}
                    @if ($turn->meta['fallback'] ?? false)
                        <span title="The assistant could not be reached.">· failed</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="thread-chat-empty">
                <span class="fas fa-comments fa-2x mb-3"></span>
                <p class="mb-1">Ask anything, or tell me what to do.</p>
                <p class="small mb-0">"What's my email on file?" · "What did we talk about last week?"</p>
            </div>
        @endforelse

        @if ($awaitingReply)
            <div class="thread-chat-message from-assistant">
                <div class="thread-chat-bubble thread-chat-typing" aria-label="The assistant is replying">
                    <span></span><span></span><span></span>
                </div>
            </div>
        @endif
    </div>

    <form wire:submit="send" class="thread-chat-input">
        <div class="input-group">
            <textarea
                wire:model="message"
                class="form-control"
                rows="3"
                placeholder="{{ $thread && ! $thread->isOpen() ? 'Continue this chat...' : 'Message the assistant...' }}"
                aria-label="Message"
                x-on:keydown.enter="if (! $event.shiftKey) { $event.preventDefault(); $wire.send() }"
            ></textarea>
            <button class="btn btn-primary" type="submit" wire:loading.attr="disabled" wire:target="send,respond" title="Send">
                <span class="fas fa-fw fa-paper-plane"></span>
            </button>
        </div>
        @error('message')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </form>
</div>

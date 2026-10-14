@extends('layouts.app')

@section('page-title', $thread?->title ?? 'Chat')

@section('content')
    {{-- v-pre: Vue mounts on #app; keep it from compiling chat content as a template. --}}
    <div class="row" v-pre>
        <div class="col-lg-4 mb-4">
            <div class="d-flex gap-2 mb-3">
                <form class="flex-grow-1" method="GET" action="{{ route('chat') }}">
                    <input type="search" name="search" class="form-control" placeholder="Search chats" value="{{ request('search') }}">
                </form>
                <a class="btn btn-primary" href="{{ route('chat') }}" title="New chat">
                    <span class="fas fa-fw fa-plus"></span>
                </a>
            </div>

            @if ($threads->isEmpty())
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <span>{{ request()->filled('search') ? 'No chats match.' : 'No chats yet.' }}</span>
                    </div>
                </div>
            @else
                <div class="list-group shadow-sm thread-list">
                    @foreach ($threads as $item)
                        @php($last = $item->messages->first())
                        <div @class(['list-group-item', 'd-flex', 'gap-2', 'align-items-start', 'active' => $thread?->is($item)])>
                            <a class="flex-grow-1 text-decoration-none text-reset thread-list-link" href="{{ route('chat.show', $item->id) }}">
                                <div class="fw-semibold text-truncate">{{ $item->title ?? 'Untitled chat' }}</div>
                                <div class="small text-truncate opacity-75">
                                    {{ $item->summary ?? \Illuminate\Support\Str::limit($last?->content, 140) }}
                                </div>
                                <div class="small opacity-50">{{ $item->last_message_at?->diffForHumans() }}</div>
                            </a>
                            <x-f-modal
                                message='<p class="mb-4">Are you sure you want to delete this chat?</p>'
                                content="Delete"
                                method="delete"
                                :route="['chat.destroy', $item->id]"
                                triggerContent="<span class='fas fa-fw fa-trash'></span>"
                                triggerClass="btn btn-sm btn-outline-danger"
                                :options="['class' => 'btn btn-outline-danger float-end']"
                                :disableOnSubmit=true
                            ></x-f-modal>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $threads->links() }}
                </div>
            @endif
        </div>

        <div class="col-lg-8">
            <livewire:thread-chat :thread="$thread" :key="'thread-chat-'.($thread?->id ?? 'new')" />
        </div>
    </div>
@stop

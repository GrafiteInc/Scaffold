<?php

use App\Ai\Tools\RecallThreads;
use App\Ai\Tools\Users\GetMyProfile;
use App\Ai\Tools\Users\UpdateMyProfile;

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Turns the chat (web pages, nav item and API endpoints) on or off.
    |
    */

    'enabled' => (bool) env('CONVERSATION_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Providers and Models
    |--------------------------------------------------------------------------
    |
    | Failover lists, provider => model, as Laravel AI expects. The `agent`
    | list runs the conversation itself; `fast` is for the cheap housekeeping
    | calls — the thread relevance check and summaries. At runtime the agents
    | move AI_DEFAULT_PROVIDER to the front and drop any provider without an
    | API key (see UsesConversationProviders).
    |
    */

    'providers' => [
        'agent' => [
            'anthropic' => env('CONVERSATION_AGENT_MODEL', 'claude-sonnet-5-5'),
            'openai' => env('CONVERSATION_AGENT_FALLBACK_MODEL', 'gpt-6.1-sol'),
        ],
        'fast' => [
            'anthropic' => env('CONVERSATION_FAST_MODEL', 'claude-haiku-5-5'),
            'openai' => env('CONVERSATION_FAST_FALLBACK_MODEL', 'gpt-6-luna'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tools
    |--------------------------------------------------------------------------
    |
    | The tools the Assistant can call. Each is resolved from the container
    | with the `user` and `thread` it's working for, so a tool can also take
    | services in its constructor. Add app tools (and retrieval / RAG tools)
    | here. Web search is added on top of these automatically, see
    | App\Ai\Agents\Assistant::webSearch().
    |
    */

    'tools' => [
        RecallThreads::class,
        GetMyProfile::class,
        UpdateMyProfile::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Thread Boundaries
    |--------------------------------------------------------------------------
    |
    | Only used when a message arrives without a thread (the API without a
    | `thread_id`); the web chat always says which thread it's continuing.
    | A message joins the active thread unless one of these says otherwise.
    | Past `idle_minutes` of silence a new thread always starts. Inside
    | `continuation_seconds` it always continues — rapid back and forth is
    | never a topic change. In between, the relevance check compares the
    | message against the last `relevance_window` messages.
    |
    */

    'idle_minutes' => (int) env('CONVERSATION_IDLE_MINUTES', 45),

    'continuation_seconds' => (int) env('CONVERSATION_CONTINUATION_SECONDS', 120),

    'relevance_window' => 4,

    // Replies this short ("yes", "2", "ok do it") always continue.
    'short_reply_words' => 3,

    // Typed at the start of a message, these close the thread and start
    // fresh. Anything after the word is still handled ("new: buy milk").
    'new_thread_words' => ['new thread', 'start over', '/new', 'new', 'reset'],

    /*
    |--------------------------------------------------------------------------
    | Context
    |--------------------------------------------------------------------------
    |
    | How many prior messages the agent sees. Older turns are carried by the
    | thread summary instead, which is refreshed once a thread passes this.
    |
    */

    'context_messages' => 20,

    'max_steps' => 8,

    'timeout' => 90,

];

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Local Domain Blacklist File
    |--------------------------------------------------------------------------
    |
    | This file will store the list of disposable email domains. You can update
    | it manually or via the `erag:sync-disposable-email-list` artisan command.
    |
    | The Docker build runs the sync, so each deploy ships a fresh list. It is
    | not scheduled: App Platform's filesystem is temporary and the scheduler
    | runs in its own container, so a runtime sync would never reach web.
    |
    */
    'blacklist_file' => storage_path('app/blacklist_file'),

    /*
    |--------------------------------------------------------------------------
    | Remote Source URL (Optional)
    |--------------------------------------------------------------------------
    |
    | If you'd like to fetch a disposable domain list from a remote location,
    | you can set that URL here and call the update command.
    |
    */
    'remote_url' => [
        'https://raw.githubusercontent.com/eramitgupta/disposable-email/main/disposable_email.txt',
    ],

    /*
    |--------------------------------------------------------------------------
    | Sync Request Timeout
    |--------------------------------------------------------------------------
    |
    | Set the timeout, in seconds, for HTTP requests made by the
    | `erag:sync-disposable-email-list` artisan command.
    |
    */
    'sync_timeout' => 30,

    /*
    |--------------------------------------------------------------------------
    | Allowed Domains
    |--------------------------------------------------------------------------
    |
    | Add domains here when you want to force them to pass even if they appear
    | in the built-in or synced disposable domain lists. Comma-separated in
    | DISPOSABLE_EMAIL_WHITELIST.
    |
    */
    'whitelist' => array_values(array_filter(array_map(
        fn (string $domain) => strtolower(trim($domain)),
        explode(',', (string) env('DISPOSABLE_EMAIL_WHITELIST', ''))
    ))),

    /*
    |--------------------------------------------------------------------------
    | Block Subdomains
    |--------------------------------------------------------------------------
    |
    | When enabled, a blocked parent domain also blocks its subdomains.
    | For example, "mail.tempmail.com" is blocked when "tempmail.com" exists.
    |
    */
    'block_subdomains' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable or Disable Caching
    |--------------------------------------------------------------------------
    |
    | Set to true to enable caching of the disposable email list.
    | Set too false to disable caching completely.
    | Default is disable
    */
    'cache_enabled' => false,

    /*
    |--------------------------------------------------------------------------
    | Cache Time To Live (seconds)
    |--------------------------------------------------------------------------
    |
    | Set a custom time to live for the cached disposable email list.
    | This value is in seconds. Default is 60 (1 minute).
    |
    */
    'cache_ttl' => 60,
];

<?php

/**
 * A cache-busting token for the service worker, derived from the Vite build
 * manifest so it changes exactly when the built assets change. Kept local on
 * purpose: this is read on every /service-worker.js request, so it must not
 * depend on the network.
 */
if (! function_exists('build_version')) {
    function build_version()
    {
        $manifest = public_path('build/manifest.json');

        if (is_file($manifest)) {
            return md5_file($manifest);
        }

        return md5(config('laravelpwa.version', '1'));
    }
}

/**
 * The built Vite files (entries and their CSS), as root-relative URLs, so the
 * service worker can precache them on install. Same rule as build_version():
 * local file only, and an empty list when nothing has been built.
 */
if (! function_exists('build_assets')) {
    function build_assets($manifest = null)
    {
        $manifest = $manifest ?? public_path('build/manifest.json');

        if (! is_file($manifest)) {
            return [];
        }

        $entries = json_decode(file_get_contents($manifest), true);

        if (! is_array($entries)) {
            return [];
        }

        return collect($entries)
            ->flatMap(fn ($entry) => array_merge([$entry['file'] ?? null], $entry['css'] ?? []))
            ->filter()
            ->map(fn ($file) => '/build/'.ltrim($file, '/'))
            ->unique()
            ->values()
            ->all();
    }
}

/**
 * The CDN files for the form fields in laravelpwa.warm_fields, which the
 * service worker fetches while the browser is idle so an editor opens from
 * cache the first time. Field classes report protocol-relative URLs.
 */
if (! function_exists('pwa_warm_assets')) {
    function pwa_warm_assets()
    {
        return collect(config('laravelpwa.warm_fields', []))
            ->flatMap(function ($field) {
                try {
                    return array_merge($field::stylesheets([]), $field::scripts([]));
                } catch (Throwable $exception) {
                    return [];
                }
            })
            ->map(fn ($url) => str_starts_with($url, '//') ? 'https:'.$url : $url)
            ->filter(fn ($url) => str_starts_with($url, 'https://'))
            ->unique()
            ->values()
            ->all();
    }
}

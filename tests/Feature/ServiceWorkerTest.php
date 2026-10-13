<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServiceWorkerTest extends TestCase
{
    public function test_service_worker_is_served_as_javascript()
    {
        $response = $this->get('/service-worker.js');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/javascript');
        $response->assertSee('var staticCacheName = "pwa-v-', false);
    }

    /**
     * Wrapping form posts, Livewire updates or API writes in the worker would
     * break them, so only GETs are ever intercepted.
     */
    public function test_service_worker_only_intercepts_get_requests()
    {
        $response = $this->get('/service-worker.js');

        $response->assertSee('event.request.method !== "GET"', false);
    }

    public function test_service_worker_falls_back_to_the_offline_page()
    {
        $response = $this->get('/service-worker.js');

        $response->assertSee('var offlineUrl = "/offline"', false);
        $response->assertSee('caches.match(offlineUrl)', false);
    }

    public function test_service_worker_enables_navigation_preload()
    {
        $response = $this->get('/service-worker.js');

        $response->assertSee('navigationPreload.enable()', false);
        $response->assertSee('event.preloadResponse', false);
    }

    /**
     * CDN files get their own cache so a deploy, which rotates the pwa-* cache,
     * doesn't throw them away.
     */
    public function test_service_worker_caches_allowlisted_cdn_hosts()
    {
        $response = $this->get('/service-worker.js');

        $response->assertSee('"cdn-v1"', false);
        $response->assertSee('"cdn.jsdelivr.net"', false);
        $response->assertDontSee('hcaptcha');
        $response->assertDontSee('stripe');
        $response->assertDontSee('usefathom');
    }

    public function test_service_worker_knows_the_field_files_to_warm()
    {
        $response = $this->get('/service-worker.js');

        $response->assertSee('dropzone@5.9.3', false);
        $response->assertSee('"warm"', false);
    }

    public function test_service_worker_handles_web_push()
    {
        $response = $this->get('/service-worker.js');

        $response->assertSee('addEventListener("push"', false);
        $response->assertSee('addEventListener("notificationclick"', false);
    }

    /**
     * The worker precaches this page and serves it with no network and no
     * session, so it must stand alone.
     */
    public function test_offline_page_is_standalone()
    {
        $response = $this->get('/offline');

        $response->assertOk();
        $response->assertSee("You're offline", false);
        $response->assertDontSee('csrf-token', false);
        $response->assertDontSee('/build/', false);
    }
}

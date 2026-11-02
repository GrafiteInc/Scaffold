<?php

namespace Tests\Feature\Helpers;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BuildVersionHelperTest extends TestCase
{
    public function test_it_returns_a_stable_value()
    {
        $this->assertNotEmpty(build_version());
        $this->assertSame(build_version(), build_version());
    }

    /**
     * This is read on every /service-worker.js request, so it must never depend
     * on the network — the service worker has to be servable when things are
     * already going wrong.
     */
    public function test_it_makes_no_http_request()
    {
        Http::fake();

        build_version();

        Http::assertNothingSent();
    }

    public function test_build_assets_lists_entries_and_their_css()
    {
        $manifest = tempnam(sys_get_temp_dir(), 'manifest');

        file_put_contents($manifest, json_encode([
            'resources/js/app.js' => [
                'file' => 'assets/app-abc.js',
                'isEntry' => true,
                'css' => ['assets/app-def.css'],
            ],
            'resources/sass/light-app.scss' => [
                'file' => 'assets/light-app-ghi.css',
                'isEntry' => true,
            ],
        ]));

        try {
            $this->assertSame([
                '/build/assets/app-abc.js',
                '/build/assets/app-def.css',
                '/build/assets/light-app-ghi.css',
            ], build_assets($manifest));
        } finally {
            unlink($manifest);
        }
    }

    public function test_build_assets_is_empty_without_a_build()
    {
        $this->assertSame([], build_assets('/nonexistent/manifest.json'));
    }

    public function test_warm_assets_are_absolute_https_urls()
    {
        $assets = pwa_warm_assets();

        $this->assertContains('https://cdn.jsdelivr.net/npm/dropzone@5.9.3/dist/min/dropzone.min.js', $assets);

        foreach ($assets as $url) {
            $this->assertStringStartsWith('https://', $url);
        }
    }

    /**
     * Both are read on every /service-worker.js request too.
     */
    public function test_asset_lists_make_no_http_request()
    {
        Http::fake();

        build_assets();
        pwa_warm_assets();

        Http::assertNothingSent();
    }
}

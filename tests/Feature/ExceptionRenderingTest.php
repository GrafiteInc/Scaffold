<?php

namespace Tests\Feature;

use Tests\GuestTestCase;

class ExceptionRenderingTest extends GuestTestCase
{
    public function test_missing_api_route_renders_json_without_accept_header()
    {
        $response = $this->get('api/does-not-exist');

        $response->assertNotFound();
        $response->assertExactJson(['message' => 'Page not found']);
    }

    public function test_missing_ajax_route_renders_json()
    {
        $response = $this->get('ajax/does-not-exist');

        $response->assertNotFound();
        $response->assertExactJson(['message' => 'Page not found']);
    }

    public function test_missing_web_route_renders_html()
    {
        $response = $this->get('does-not-exist');

        $response->assertNotFound();
        $this->assertStringContainsString('text/html', $response->headers->get('Content-Type'));
    }
}

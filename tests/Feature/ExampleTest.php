<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_homepage_loads(): void
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }
}

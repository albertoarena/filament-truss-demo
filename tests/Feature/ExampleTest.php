<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The root route exists to send a visitor to the panel, which is the only
     * thing in this application worth looking at. The stock version of this test
     * expected a 200 and had been failing since that redirect was added.
     */
    public function test_the_root_route_sends_a_visitor_to_the_panel(): void
    {
        $this->get('/')->assertRedirect('/admin');
    }
}

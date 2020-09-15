<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    /** @test */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    /** @test */
    }public function testPost()
    {   
        // $response->assertStatus(200);
    }
}

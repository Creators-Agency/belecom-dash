<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StockControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     * @test
     * @return void
     */

    public function index()
    {
        $response = $this->get('/stock');

        $response->assertStatus(200);
    }

    public function addNewItem()
    {
        $response = $this->get('/new/item');

        $response->assertStatus(200);
    }

    public function addNewLocation()
    {
        $response = $this->get('/new/item');

        $response->assertStatus(200);
    }

    public function addNewType()
    {
        $response = $this->get('/new/item');

        $response->assertStatus(200);
    }
}

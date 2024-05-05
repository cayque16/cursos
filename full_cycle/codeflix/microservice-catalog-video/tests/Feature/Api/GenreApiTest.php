<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Genre;
use Illuminate\Http\Response;
use Tests\TestCase;

class GenreApiTest extends TestCase
{
    protected $endpoint = '/api/genres';

    public function testListAllEmpty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(0, 'data');
    }

    public function testListAll()
    {
        Genre::factory()->count(20)->create();

        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonStructure([
            'data',
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'per_page',
                'to',
                'from',
            ]
        ]);
    }

    public function testStore()
    {
        $categories = Category::factory()->count(10)->create();

        $response = $this->postJson($this->endpoint, [
            'name' => 'new genre',
            'is_active' => true,
            'categories_ids' => $categories->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
            ]
        ]);
    }

    public function testStoreValidations()
    {
        $categories = Category::factory()->count(2)->create();

        $payload = [
            'name' => '',
            'is_active' => true,
            'categories_ids' => $categories->pluck('id')->toArray()
        ];

        $response = $this->postJson($this->endpoint, $payload);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name'
            ]
        ]);
    }

    public function testShowNotFound()
    {
        $response = $this->getJson("{$this->endpoint}/fake_id");

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testShow()
    {
        $genre = Genre::factory()->create();

        $response = $this->getJson("{$this->endpoint}/{$genre->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'is_active',
            ]
        ]);
    }
}

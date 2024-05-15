<?php

namespace Tests\Feature\Api;

use App\Models\Video;
use Illuminate\Http\Response;
use Tests\TestCase;

class VideoApiTest extends TestCase
{
    protected $endpoint = '/api/videos';

    public function testEmpty()
    {
        $response = $this->getJson($this->endpoint);

        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @dataProvider dataProviderPagination
     */
    public function testPagination(
        int $total,
        int $totalCurrentPage,
        int $page,
        int $perPage,
        string $filter = '',
    ) {
        Video::factory()->count($total)->create();

        if ($filter) {
            Video::factory()->count($total)->create([
                'title' => $filter
            ]);
        }

        $params = http_build_query([
            'page' => $page,
            'total_page' => $perPage,
            'order' => 'DESC',
            'filter' => $filter,
        ]);
        
        $response = $this->getJson("$this->endpoint?$params");
        
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount($totalCurrentPage, 'data');
        $response->assertJsonPath('meta.current_page', $page);
        $response->assertJsonPath('meta.per_page', $perPage);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'year_launched',
                    'opened',
                    'rating',
                    'duration',
                    'created_at',
                ]
            ],
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

    protected function dataProviderPagination(): array
    {
        return [
            'test empty' => [
                'total' => 0,
                'totalCurrentPage' => 0,
                'page' => 1,
                'perPage' => 15,
            ],
            'test with total two pages' => [
                'total' => 20,
                'totalCurrentPage' => 15,
                'page' => 1,
                'perPage' => 15,
            ],
            'test page two' => [
                'total' => 20,
                'totalCurrentPage' => 5,
                'page' => 2,
                'perPage' => 15,
            ],
            'test page four' => [
                'total' => 40,
                'totalCurrentPage' => 10,
                'page' => 4,
                'perPage' => 10,
            ],
            'test with filter' => [
                'total' => 10,
                'totalCurrentPage' => 10,
                'page' => 1,
                'perPage' => 10,
                'filter' => 'test',
            ],
        ];
    }
}

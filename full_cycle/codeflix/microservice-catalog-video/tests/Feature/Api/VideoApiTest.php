<?php

namespace Tests\Feature\Api;

use App\Models\CastMember;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoApiTest extends TestCase
{
    protected $endpoint = '/api/videos';
    protected $serializedFields =  [
        'id',
        'title',
        'description',
        'year_launched',
        'opened',
        'rating',
        'duration',
        'created_at',
    ];

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
                '*' => $this->serializedFields
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

    public function testShow()
    {
        $video = Video::factory()->create();

        $response = $this->getJson("$this->endpoint/{$video->id}");
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => $this->serializedFields,
        ]);
    }

    public function testShowNotFound()
    {
        $response = $this->getJson("$this->endpoint/fake_id");

        $response->assertNotFound();
    }

    public function testStore()
    {
        $videoFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $imageFile = UploadedFile::fake()->image('image.png');

        $categoriesIds = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count(3)->create()->pluck('id')->toArray();
        $castMembersIds = CastMember::factory()->count(3)->create()->pluck('id')->toArray();

        $data = [
            'title' => 'test title',
            'description' => 'test desc',
            'year_launched' => 2000,
            'duration' => 120,
            'opened' => true,
            'rating' => 'L',
            'categories' => $categoriesIds,
            'genres' => $genresIds,
            'cast_members' => $castMembersIds,
            'video_file' => $videoFile,
            'trailer_file' => $videoFile,
            'banner_file' => $imageFile,
            'thumb_file' => $imageFile,
            'thumb_half_file' => $imageFile,
        ];

        $response = $this->postJson($this->endpoint, $data);
        $response->assertCreated();
        $response->assertJsonStructure(['data' => $this->serializedFields]);
        $this->assertDatabaseCount('videos', 1);
        $this->assertDatabaseHas('videos', ['id' => $response->json('data.id')]);

        $this->assertEquals($categoriesIds, $response->json('data.categories'));
        $this->assertEquals($genresIds, $response->json('data.genres'));
        $this->assertEquals($castMembersIds, $response->json('data.cast_members'));

        Storage::assertExists($response->json('data.video'));
        Storage::assertExists($response->json('data.trailer'));
        Storage::assertExists($response->json('data.banner'));
        Storage::assertExists($response->json('data.thumb'));
        Storage::assertExists($response->json('data.thumb_half'));

        Storage::deleteDirectory($response->json('data.id'));
    }

    public function testStoreValidation()
    {
        $response = $this->postJson($this->endpoint, []);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors([
            'title',
            'description',
            'year_launched',
            'duration',
            'opened',
            'rating',
            'categories',
            'genres',
            'cast_members',
        ]);
    }

    public function testUpdate()
    {
        $video = Video::factory()->create();

        $videoFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $imageFile = UploadedFile::fake()->image('image.png');

        $categoriesIds = Category::factory()->count(3)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count(3)->create()->pluck('id')->toArray();
        $castMembersIds = CastMember::factory()->count(3)->create()->pluck('id')->toArray();

        $data = [
            'title' => 'title update',
            'description' => 'desc update',
            'categories' => $categoriesIds,
            'genres' => $genresIds,
            'cast_members' => $castMembersIds,
            'video_file' => $videoFile,
            'trailer_file' => $videoFile,
            'banner_file' => $imageFile,
            'thumb_file' => $imageFile,
            'thumb_half_file' => $imageFile,
        ];

        $response = $this->putJson("$this->endpoint/{$video->id}", $data);
        $response->assertCreated();
        $response->assertJsonStructure(['data' => $this->serializedFields]);
        $this->assertDatabaseCount('videos', 1);
        $this->assertDatabaseHas('videos', [
            'id' => $response->json('data.id'),
            'title' => $data['title'],
            'description' => $data['description'],
        ]);

        $this->assertEquals($categoriesIds, $response->json('data.categories'));
        $this->assertEquals($genresIds, $response->json('data.genres'));
        $this->assertEquals($castMembersIds, $response->json('data.cast_members'));

        Storage::assertExists($response->json('data.video'));
        Storage::assertExists($response->json('data.trailer'));
        Storage::assertExists($response->json('data.banner'));
        Storage::assertExists($response->json('data.thumb'));
        Storage::assertExists($response->json('data.thumb_half'));

        Storage::deleteDirectory($response->json('data.id'));
    }
}

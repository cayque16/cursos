<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Models\CastMember;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video as VideoModel;
use App\Repositories\Eloquent\VideoEloquentRepository;
use Core\Domain\Entity\Video as VideoEntity;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\VideoRepositoryInterface;
use Tests\TestCase;

class VideoEloquentRepositoryTest extends TestCase
{
    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new VideoEloquentRepository(new VideoModel());
    }

    public function testImplementsInterface()
    {
        $this->assertInstanceOf(
            VideoRepositoryInterface::class,
            $this->repository
        );
    }

    public function testInsert()
    {
        $entity = new VideoEntity(
            title: 'Test',
            description: 'Test',
            yearLaunched: 2026,
            rating: Rating::L,
            duration: 1,
            opened: true
        );

        $this->repository->insert($entity);

        $this->assertDatabaseHas('videos', [
            'id' => $entity->id,
        ]);
    }

    public function testInsertWithRelationships()
    {
        $categories = Category::factory()->count(4)->create();
        $genres = Genre::factory()->count(4)->create();
        $castMembers = CastMember::factory()->count(4)->create();

        $entity = new VideoEntity(
            title: 'Test',
            description: 'Test',
            yearLaunched: 2026,
            rating: Rating::L,
            duration: 1,
            opened: true
        );

        foreach ($categories as $category) {
            $entity->addCategoryId($category->id);
        }

        foreach ($genres as $genre) {
            $entity->addGenreId($genre->id);
        }

        foreach ($castMembers as $castMember) {
            $entity->addCastMemberId($castMember->id);
        }

        $entityInDb = $this->repository->insert($entity);

        $this->assertDatabaseHas('videos', [
            'id' => $entity->id,
        ]);

        $this->assertDatabaseCount('category_video', 4);
        $this->assertDatabaseCount('genre_video', 4);
        $this->assertDatabaseCount('cast_member_video', 4);

        $this->assertEquals($categories->pluck('id')->toArray(), $entityInDb->categoriesId);
        $this->assertEquals($genres->pluck('id')->toArray(), $entityInDb->genresId);
        $this->assertEquals($castMembers->pluck('id')->toArray(), $entityInDb->castMembersId);
    }

    public function testNotFoundVideo()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->findById('fake_value');
    }

    public function testFindById()
    {
        $video = VideoModel::factory()->create();
        
        $response = $this->repository->findById($video->id);

        $this->assertEquals($video->id, $response->id());
        $this->assertEquals($video->title, $response->title);
    }

    public function testFindAll()
    {
        VideoModel::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertCount(10, $response);
    }

    public function testFindAllWithFilter()
    {
        VideoModel::factory()->count(10)->create();
        VideoModel::factory()->count(10)->create([
            'title' => 'Test'
        ]);

        $response = $this->repository->findAll(
            filter: 'Test'
        );

        $this->assertCount(10, $response);
        $this->assertDatabaseCount('videos', 20);
    }

    public function testPagination()
    {
        VideoModel::factory()->count(50)->create();

        $response = $this->repository->paginate(
            page: 1,
            totalPage: 10
        );

        $this->assertCount(10, $response->items());
        $this->assertEquals(50, $response->total());
        $this->assertEquals(1, $response->currentPage());
        $this->assertEquals(10, $response->perPage());
    }
}

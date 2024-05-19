<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use App\Enums\ImageTypes;
use App\Models\CastMember;
use App\Models\Category;
use App\Models\Genre;
use App\Models\Video as VideoModel;
use App\Repositories\Eloquent\VideoEloquentRepository;
use Core\Domain\Entity\Video as VideoEntity;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\Domain\ValueObject\Uuid;
use Tests\TestCase;

class VideoEloquentRepositoryTest extends TestCase
{
    protected VideoEloquentRepository $repository;

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
            'title' => 'Test',
        ]);

        $response = $this->repository->findAll(
            filter: 'Test'
        );

        $this->assertCount(10, $response);
        $this->assertDatabaseCount('videos', 20);
    }

    /**
     * @dataProvider dataProviderPagination
     */
    public function testPagination(
        int $page,
        int $totalPage,
        int $total = 50,
    ) {
        VideoModel::factory()->count($total)->create();

        $response = $this->repository->paginate(
            page: $page,
            totalPage: $totalPage
        );

        $this->assertCount($totalPage, $response->items());
        $this->assertEquals($total, $response->total());
        $this->assertEquals($page, $response->currentPage());
        $this->assertEquals($totalPage, $response->perPage());
    }

    public function dataProviderPagination()
    {
        return [
            'dataSet#1' => [
                'page' => 1,
                'totalPage' => 10,
                'total' => 100,
            ],
            'dataSet#2' => [
                'page' => 2,
                'totalPage' => 15,
            ],
            'dataSet#3' => [
                'page' => 3,
                'totalPage' => 15,
            ],
        ];
    }

    public function testUpdateNotFoundId()
    {
        $this->expectException(NotFoundException::class);

        $entity = new VideoEntity(
            title: 'Test',
            description: 'Test',
            yearLaunched: 2026,
            rating: Rating::L,
            duration: 1,
            opened: true
        );

        $this->repository->update($entity);
    }

    public function testUpdate()
    {
        $categories = Category::factory()->count(10)->create();
        $genres = Genre::factory()->count(10)->create();
        $castMembers = CastMember::factory()->count(10)->create();

        $videoDb = VideoModel::factory()->create();

        $this->assertDatabaseHas('videos', [
            'title' => $videoDb->title,
        ]);

        $entity = new VideoEntity(
            id: new Uuid($videoDb->id),
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

        $entityInDb = $this->repository->update($entity);

        $this->assertDatabaseHas('videos', [
            'title' => 'Test',
        ]);

        $this->assertDatabaseCount('category_video', 10);
        $this->assertDatabaseCount('genre_video', 10);
        $this->assertDatabaseCount('cast_member_video', 10);

        $this->assertEquals($categories->pluck('id')->toArray(), $entityInDb->categoriesId);
        $this->assertEquals($genres->pluck('id')->toArray(), $entityInDb->genresId);
        $this->assertEquals($castMembers->pluck('id')->toArray(), $entityInDb->castMembersId);
    }

    public function testDeleteNotFound()
    {
        $this->expectException(NotFoundException::class);

        $this->repository->delete('fake_value');
    }

    public function testDelete()
    {
        $video = VideoModel::factory()->create();

        $this->repository->delete($video->id);

        $this->assertSoftDeleted('videos', [
            'id' => $video->id,
        ]);
    }

    public function testInsertWithMediaVideo()
    {
        $entity = new VideoEntity(
            title: 'Test',
            description: 'Test',
            yearLaunched: 2026,
            rating: Rating::L,
            duration: 1,
            opened: true,
            videoFile: new Media(
                filePath: 'test.mp4',
                mediaStatus: MediaStatus::PROCESSING,
            ),
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('medias_video', 0);

        $this->repository->updateMedia($entity);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'file_path' => 'test.mp4',
            'media_status' => MediaStatus::PROCESSING,
        ]);

        $entity->setVideoFile(
            new Media(
                filePath: 'test2.mp4',
                mediaStatus: MediaStatus::COMPLETE,
                encodePath: 'test2.xpto'
            )
        );

        $entityDb = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('medias_video', 1);
        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'file_path' => 'test2.mp4',
            'media_status' => MediaStatus::COMPLETE,
            'encode_path' => 'test2.xpto',
        ]);
        $this->assertNotNull($entityDb->videoFile());
    }

    public function testInsertWithMediaTrailer()
    {
        $entity = new VideoEntity(
            title: 'Test',
            description: 'Test',
            yearLaunched: 2026,
            rating: Rating::L,
            duration: 1,
            opened: true,
            trailerFile: new Media(
                filePath: 'test.mp4',
                mediaStatus: MediaStatus::PROCESSING,
            ),
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('medias_video', 0);

        $this->repository->updateMedia($entity);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'file_path' => 'test.mp4',
            'media_status' => MediaStatus::PROCESSING,
        ]);

        $entity->setTrailerFile(
            new Media(
                filePath: 'test2.mp4',
                mediaStatus: MediaStatus::COMPLETE,
                encodePath: 'test2.xpto'
            )
        );

        $entityDb = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('medias_video', 1);
        $this->assertDatabaseHas('medias_video', [
            'video_id' => $entity->id(),
            'file_path' => 'test2.mp4',
            'media_status' => MediaStatus::COMPLETE,
            'encode_path' => 'test2.xpto',
        ]);
        $this->assertNotNull($entityDb->trailerFile());
    }

    public function testInsertWithImageBanner()
    {
        $entity = new VideoEntity(
            title: 'Test',
            description: 'Test',
            yearLaunched: 2026,
            rating: Rating::L,
            duration: 1,
            opened: true,
            bannerFile: new Image(
                path: 'test.mp4',
            ),
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('images_video', 0);

        $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);
        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'test.mp4',
            'type' => ImageTypes::BANNER->value,
        ]);

        $entity->setBannerFile(
            new Image(
                path: 'test2.mp4',
            )
        );

        $entityDb = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);
        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'test2.mp4',
            'type' => ImageTypes::BANNER->value,
        ]);
        $this->assertNotNull($entityDb->bannerFile());
    }

    public function testInsertWithImageThumb()
    {
        $entity = new VideoEntity(
            title: 'Test',
            description: 'Test',
            yearLaunched: 2026,
            rating: Rating::L,
            duration: 1,
            opened: true,
            thumbFile: new Image(
                path: 'test.mp4',
            ),
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('images_video', 0);

        $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);
        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'test.mp4',
            'type' => ImageTypes::THUMB->value,
        ]);

        $entity->setThumbFile(
            new Image(
                path: 'test2.mp4',
            )
        );

        $entityDb = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);
        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'test2.mp4',
            'type' => ImageTypes::THUMB->value,
        ]);
        $this->assertNotNull($entityDb->thumbFile());
    }

    public function testInsertWithImageThumbHalf()
    {
        $entity = new VideoEntity(
            title: 'Test',
            description: 'Test',
            yearLaunched: 2026,
            rating: Rating::L,
            duration: 1,
            opened: true,
            thumbHalfFile: new Image(
                path: 'test.mp4',
            ),
        );

        $this->repository->insert($entity);

        $this->assertDatabaseCount('images_video', 0);

        $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);
        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'test.mp4',
            'type' => ImageTypes::THUMB_HALF->value,
        ]);

        $entity->setThumbHalfFile(
            new Image(
                path: 'test2.mp4',
            )
        );

        $entityDb = $this->repository->updateMedia($entity);

        $this->assertDatabaseCount('images_video', 1);
        $this->assertDatabaseHas('images_video', [
            'video_id' => $entity->id(),
            'path' => 'test2.mp4',
            'type' => ImageTypes::THUMB_HALF->value,
        ]);

        $this->assertNotNull($entityDb->thumbHalfFile());
    }
}

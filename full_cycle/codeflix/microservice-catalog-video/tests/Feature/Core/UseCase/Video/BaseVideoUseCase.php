<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\{
    CastMember,
    Category,
    Genre
};
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use Core\UseCase\Interfaces\TransactionInterface;
use Illuminate\Http\UploadedFile;
use Tests\Stubs\{
    UploadFilesStub,
    VideoEventStub
};
use Tests\TestCase;

use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Support\Facades\Event;

abstract class BaseVideoUseCase extends TestCase
{
    abstract function useCase(): string;
    abstract function inputDTO(
        array $categories = [],
        array $genres = [],
        array $castMembers = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $bannerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalfFile = null,
    ): object;

    /**
     * @dataProvider provider
     */
    public function testCreateOrUpdate(
        int $categories,
        int $genres,
        int $castMembers,
        bool $withMediaVideo = false,
        bool $withMediaTrailer = false,
        bool $withMediaThumb = false,
        bool $withMediaThumbHalf = false,
        bool $withMediaBanner = false,
    ) {
        $stu = $this->makeSut();

        $categoriesIds = Category::factory()->count($categories)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count($genres)->create()->pluck('id')->toArray();
        $castMembersIds = CastMember::factory()->count($castMembers)->create()->pluck('id')->toArray();

        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');

        $file = [
            'tmp_name' => $fakeFile->getPathname(),
            'name' => $fakeFile->getFilename(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
        ];

        $input = $this->inputDTO(
            categories: $categoriesIds,
            genres: $genresIds,
            castMembers: $castMembersIds,
            videoFile: $withMediaVideo ? $file : null,
            trailerFile: $withMediaTrailer ? $file : null,
            bannerFile: $withMediaBanner ? $file : null,
            thumbFile: $withMediaThumb ? $file : null,
            thumbHalfFile: $withMediaThumbHalf ? $file : null,
        );

        $response = $stu->execute($input);

        $this->assertEquals($input->title, $response->title);
        $this->assertEquals($input->description, $response->description);

        $this->assertCount($categories, $response->categories);
        $this->assertCount($genres, $response->genres);
        $this->assertCount($castMembers, $response->castMembers);

        $this->assertTrue($withMediaVideo ? $response->videoFile !== null : $response->videoFile === null);
        $this->assertTrue($withMediaTrailer ? $response->trailerFile !== null : $response->trailerFile === null);
        $this->assertTrue($withMediaBanner ? $response->bannerFile !== null : $response->bannerFile === null);
        $this->assertTrue($withMediaThumb ? $response->thumbFile !== null : $response->thumbFile === null);
        $this->assertTrue($withMediaThumbHalf ? $response->thumbHalfFile !== null : $response->thumbHalfFile === null);
    }
    
    protected function provider(): array
    {
        return [
            'Test with all IDs and media video' => [
                'categories' => 3,
                'genres' => 3,
                'castMembers' => 3,
                'withMediaVideo' => true,
                'withMediaTrailer' => false,
                'withMediaThumb' => false,
                'withMediaThumbHalf' => false,
                'withMediaBanner' => false,
            ],
            'Test with categories and genres and without files' => [
                'categories' => 3,
                'genres' => 3,
                'castMembers' => 0,
            ],
            'Test with all IDs and all medias' => [
                'categories' => 2,
                'genres' => 2,
                'castMembers' => 2,
                'withMediaVideo' => true,
                'withMediaTrailer' => true,
                'withMediaThumb' => true,
                'withMediaThumbHalf' => true,
                'withMediaBanner' => true,
            ],
            'Test without IDs and all medias' => [
                'categories' => 2,
                'genres' => 2,
                'castMembers' => 2,
                'withMediaVideo' => true,
                'withMediaTrailer' => true,
                'withMediaThumb' => true,
                'withMediaThumbHalf' => true,
                'withMediaBanner' => true,
            ],
        ];
    }

    protected function makeSut()
    {
        return new ($this->useCase())(
            $this->app->make(VideoRepositoryInterface::class),
            $this->app->make(TransactionInterface::class),
            new UploadFilesStub(),
            new VideoEventStub(),

            $this->app->make(CategoryRepositoryInterface::class),
            $this->app->make(GenreRepositoryInterface::class),
            $this->app->make(CastMemberRepositoryInterface::class),
        );
    }

    public function testTransactionException()
    {
        Event::listen(TransactionBeginning::class, function () {
            throw new Exception('begin transaction');
        });

        try {
            $sut = $this->makeSut();
            $sut->execute($this->inputDTO());

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
        }
    }

    public function testUploadFilesException()
    {
        Event::listen(UploadFilesStub::class, function () {
            throw new Exception('upload files');
        });

        try {
            $sut = $this->makeSut();
            $input = $this->inputDTO(
                videoFile: [
                    'name' => 'video.mp4',
                    'type' => 'video.mp4',
                    'tmp_name' => '/tmp/video.mp4',
                    'error' => 0,
                ]
            );

            $sut->execute($input);

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
        }
    }

    public function testEventException()
    {
        Event::listen(VideoEventStub::class, function () {
            throw new Exception('event');
        });

        try {
            $sut = $this->makeSut();
            $input = $this->inputDTO(
                videoFile: [
                    'name' => 'video.mp4',
                    'type' => 'video.mp4',
                    'tmp_name' => '/tmp/video.mp4',
                    'error' => 0,
                ]
            );

            $sut->execute($input);

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
        }
    }
}

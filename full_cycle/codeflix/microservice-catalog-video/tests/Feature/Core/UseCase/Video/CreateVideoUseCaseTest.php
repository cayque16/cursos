<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\CastMember;
use App\Models\Category;
use App\Models\Genre;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoInputDto;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\CreateVideoUseCase;
use Illuminate\Http\UploadedFile;
use Tests\Stubs\UploadFilesStub;
use Tests\Stubs\VideoEventStub;
use Tests\TestCase;

class CreateVideoUseCaseTest extends TestCase
{
    /**
     * @dataProvider provider
     */
    public function testCreate(
        int $categories,
        int $genres,
        int $castMembers,
        bool $withMediaVideo = false,
        bool $withMediaTrailer = false,
        bool $withMediaThumb = false,
        bool $withMediaThumbHalf = false,
        bool $withMediaBanner = false,
    ) {
        $useCase = new CreateVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class),
            $this->app->make(TransactionInterface::class),
            new UploadFilesStub(),
            new VideoEventStub(),

            $this->app->make(CategoryRepositoryInterface::class),
            $this->app->make(GenreRepositoryInterface::class),
            $this->app->make(CastMemberRepositoryInterface::class),
        );

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

        $input = new CreateVideoInputDto(
            title: 'test',
            description: 'test',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE18,
            categories: $categoriesIds,
            genres: $genresIds,
            castMembers: $castMembersIds,
            videoFile: $withMediaVideo ? $file : null,
            trailerFile: $withMediaTrailer ? $file : null,
            bannerFile: $withMediaBanner ? $file : null,
            thumbFile: $withMediaThumb ? $file : null,
            thumbHalfFile: $withMediaThumbHalf ? $file : null,
        );

        $response = $useCase->execute($input);

        $this->assertEquals($input->title, $response->title);
        $this->assertEquals($input->description, $response->description);
        $this->assertEquals($input->yearLaunched, $response->yearLaunched);
        $this->assertEquals($input->duration, $response->duration);
        $this->assertEquals($input->opened, $response->opened);
        $this->assertEquals($input->rating, $response->rating);

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
}

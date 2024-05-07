<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoInputDto;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoOutputDto;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\CreateVideoUseCase;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;
use stdClass;
use Tests\TestCase;

class CreateVideoUseCaseUnitTest extends TestCase
{
    private function getUseCase()
    {
        return new CreateVideoUseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockStorage(),
            eventManager: $this->createMockEventManage(),

            repositoryCategory: $this->createMockCategoryRepository(),
            repositoryGenre: $this->createMockGenreRepository(),
            repositoryCastMember: $this->createMockCastMemberRepository(),
        );
    }

    public function testConstructor()
    {
        $this->getUseCase();

        $this->assertTrue(true);
    }

    public function testExecuteInputOutput()
    {
        $useCase = $this->getUseCase();

        $response = $useCase->execute($this->createMockInputDto());

        $this->assertInstanceOf(CreateVideoOutputDto::class, $response);
    }

    public function testExceptionCategoriesIds()
    {
        $this->expectException(NotFoundException::class);

        ($this->getUseCase())->execute(
            input: $this->createMockInputDto(categoriesIds: ['uuid-1'])
        );
    }

    /**
     * @dataProvider dataProviderFiles
     */
    public function testUploadFiles(
        array $video,
        array $trailer,
        array $thumb,
        array $thumbHalf,
        array $banner,
    ) {
        $response = ($this->getUseCase())->execute(
            input: $this->createMockInputDto(
                videoFile: $video['value'],
                trailerFile: $trailer['value'],
                thumbFile: $thumb['value'],
                thumbHalfFile: $thumbHalf['value'],
                bannerFile: $banner['value'],
            )
        );

        $this->assertEquals($response->videoFile, $video['expected']);
        $this->assertEquals($response->trailerFile, $trailer['expected']);
        $this->assertEquals($response->thumbFile, $thumb['expected']);
        $this->assertEquals($response->thumbHalfFile, $thumbHalf['expected']);
        $this->assertEquals($response->bannerFile, $banner['expected']);
    }

    public function dataProviderFiles(): array
    {
        return [
            [
                'video' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'trailer' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'thumbHalf' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
            ],
            [
                'video' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
            ],
            [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
            ],
            [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => null, 'expected' => null],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => null, 'expected' => null],
            ],
        ];
    }

    private function createMockEntity()
    {
        $mock =  Mockery::mock(Video::class, [
            'title',
            'description',
            2023,
            90,
            true,
            Rating::RATE14,
        ]);

        return $mock;
    }

    private function createMockRepository()
    {
        $mock =  Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mock->shouldReceive('insert')->andReturn($this->createMockEntity());
        $mock->shouldReceive('updateMedia');

        return $mock;
    }

    private function createMockCategoryRepository($response = [])
    {
        $mock =  Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);

        $mock->shouldReceive('getIdsListIds')->andReturn($response);

        return $mock;
    }

    private function createMockGenreRepository($response = [])
    {
        $mock =  Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        $mock->shouldReceive('getIdsListIds')->andReturn($response);

        return $mock;
    }

    private function createMockCastMemberRepository($response = [])
    {
        $mock =  Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);

        $mock->shouldReceive('getIdsListIds')->andReturn($response);

        return $mock;
    }

    private function createMockTransaction()
    {
        $mock = Mockery::mock(stdClass::class, TransactionInterface::class);

        $mock->shouldReceive('commit');
        $mock->shouldReceive('rollBack');

        return $mock;
    }

    private function createMockStorage()
    {
        $mock = Mockery::mock(stdClass::class, FileStorageInterface::class);

        $mock->shouldReceive('store')->andReturn('path/file.ext');

        return $mock;
    }

    private function createMockEventManage()
    {
        $mock =  Mockery::mock(stdClass::class, VideoEventManagerInterface::class);

        $mock->shouldReceive('dispatch');

        return $mock;
    }

    private function createMockInputDto(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMembersIds = [],
        ?array $thumbFile = null,
        ?array $thumbHalfFile = null,
        ?array $bannerFile = null,
        ?array $trailerFile = null,
        ?array $videoFile = null,
    ) {
        return Mockery::mock(CreateVideoInputDto::class, [
            'title',
            'description',
            2023,
            90,
            true,
            Rating::RATE14,
            $categoriesIds,
            $genresIds,
            $castMembersIds,
            $thumbFile,
            $thumbHalfFile,
            $bannerFile,
            $trailerFile,
            $videoFile,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}

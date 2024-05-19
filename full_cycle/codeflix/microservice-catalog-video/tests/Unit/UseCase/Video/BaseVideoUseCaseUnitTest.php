<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Mockery;
use stdClass;
use Tests\TestCase;

abstract class BaseVideoUseCaseUnitTest extends TestCase
{
    abstract protected function nameActionRepository(): string;

    abstract protected function getUseCase(): string;

    abstract protected function createMockInputDto(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMembersIds = [],
        ?array $thumbFile = null,
        ?array $thumbHalfFile = null,
        ?array $bannerFile = null,
        ?array $trailerFile = null,
        ?array $videoFile = null,
    );

    protected function createUseCase(
        int $timesCallMethodActionRepository = 1,
        int $timesCallMethodUpdateMediaRepository = 1,
        int $timesCallMethodCommitTransaction = 1,
        int $timesCallMethodRollbackTransaction = 0,
        int $timesCallMethodStoreFileStorage = 0,
        int $timesCallMethodDispatchEventManager = 0,
    ) {
        return new ($this->getUseCase())(
            repository: $this->createMockRepository(
                timesCallAction: $timesCallMethodActionRepository,
                timesCallUpdateMedia: $timesCallMethodUpdateMediaRepository
            ),
            transaction: $this->createMockTransaction(
                timesCallCommit: $timesCallMethodCommitTransaction,
                timesCallRollback: $timesCallMethodRollbackTransaction
            ),
            storage: $this->createMockStorage($timesCallMethodStoreFileStorage),
            eventManager: $this->createMockEventManage($timesCallMethodDispatchEventManager),

            repositoryCategory: $this->createMockCategoryRepository(),
            repositoryGenre: $this->createMockGenreRepository(),
            repositoryCastMember: $this->createMockCastMemberRepository(),
        );
    }

    public function testConstructor()
    {
        $this->createUseCase(0, 0, 0);

        $this->assertTrue(true);
    }

    public function testExceptionCategoriesIds()
    {
        $this->expectException(NotFoundException::class);

        ($this->createUseCase(0, 0, 0))->execute(
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
        int $timesStoreFileStorage,
        int $timesDispatch = 0
    ) {
        $response = ($this->createUseCase(
            timesCallMethodStoreFileStorage: $timesStoreFileStorage,
            timesCallMethodDispatchEventManager: $timesDispatch
        ))->execute(
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
                'timesStorage' => 5,
                'timesDispatch' => 1,
            ],
            [
                'video' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'timesStorage' => 3,
                'timesDispatch' => 1,
            ],
            [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => ['tmp' => 'tmp/file.mp4'], 'expected' => 'path/file.ext'],
                'timesStorage' => 2,
            ],
            [
                'video' => ['value' => null, 'expected' => null],
                'trailer' => ['value' => null, 'expected' => null],
                'thumb' => ['value' => null, 'expected' => null],
                'thumbHalf' => ['value' => null, 'expected' => null],
                'banner' => ['value' => null, 'expected' => null],
                'timesStorage' => 0,
            ],
        ];
    }

    private function createEntity()
    {
        return new Video(
            title: 'title',
            description: 'description',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE10
        );
    }

    private function createMockRepository(
        int $timesCallAction,
        int $timesCallUpdateMedia,
    ) {
        $mock = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mock->shouldReceive($this->nameActionRepository())
            ->times($timesCallAction)
            ->andReturn($this->createEntity());
        $mock->shouldReceive('updateMedia')
            ->times($timesCallUpdateMedia);
        $mock->shouldReceive('findById')
            ->andReturn($this->createEntity());

        return $mock;
    }

    private function createMockCategoryRepository($response = [])
    {
        $mock = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);

        $mock->shouldReceive('getIdsListIds')->andReturn($response);

        return $mock;
    }

    private function createMockGenreRepository($response = [])
    {
        $mock = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);

        $mock->shouldReceive('getIdsListIds')->andReturn($response);

        return $mock;
    }

    private function createMockCastMemberRepository($response = [])
    {
        $mock = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);

        $mock->shouldReceive('getIdsListIds')->andReturn($response);

        return $mock;
    }

    private function createMockTransaction(
        int $timesCallCommit,
        int $timesCallRollback
    ) {
        $mock = Mockery::mock(stdClass::class, TransactionInterface::class);

        $mock->shouldReceive('commit')->times($timesCallCommit);
        $mock->shouldReceive('rollBack')->times($timesCallRollback);

        return $mock;
    }

    private function createMockStorage(int $times)
    {
        $mock = Mockery::mock(stdClass::class, FileStorageInterface::class);

        $mock->shouldReceive('store')
            ->times($times)
            ->andReturn('path/file.ext');

        return $mock;
    }

    private function createMockEventManage(int $times)
    {
        $mock = Mockery::mock(stdClass::class, VideoEventManagerInterface::class);

        $mock->shouldReceive('dispatch')->times($times);

        return $mock;
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}

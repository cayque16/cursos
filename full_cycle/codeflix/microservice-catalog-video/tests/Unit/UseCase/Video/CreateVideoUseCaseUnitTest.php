<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
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
    public function testConstructor()
    {
        new CreateVideoUseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockStorage(),
            eventManager: $this->createMockEventManage(),

            repositoryCategory: $this->createMockCategoryRepository(),
            repositoryGenre: $this->createMockGenreRepository(),
            repositoryCastMember: $this->createMockCastMemberRepository(),
        );

        $this->assertTrue(true);
    }

    public function testExecuteInputOutput()
    {
        $useCase = new CreateVideoUseCase(
            repository: $this->createMockRepository(),
            transaction: $this->createMockTransaction(),
            storage: $this->createMockStorage(),
            eventManager: $this->createMockEventManage(),

            repositoryCategory: $this->createMockCategoryRepository(),
            repositoryGenre: $this->createMockGenreRepository(),
            repositoryCastMember: $this->createMockCastMemberRepository(),
        );

        $response = $useCase->execute($this->createMockInputDto());

        $this->assertInstanceOf(CreateVideoOutputDto::class, $response);
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

    private function createMockInputDto()
    {
        return Mockery::mock(CreateVideoInputDto::class, [
            'title',
            'description',
            2023,
            90,
            true,
            Rating::RATE14,
            [],
            [],
            []
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}

<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\DTO\Genre\UpdateGenre\GenreUpdateInputDto;
use Core\UseCase\DTO\Genre\UpdateGenre\GenreUpdateOutputDto;
use Core\UseCase\Genre\UpdateGenreUseCase;
use Core\UseCase\Interfaces\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class UpdateGenreUseCaseUnitTest extends TestCase
{
    public function testUpdate()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $useCase = new UpdateGenreUseCase(
            $this->mockRepository($uuid),
            $this->mockTransaction(),
            $this->mockCategoryRepository([$uuid])
        );

        $response = $useCase->execute($this->mockUpdateInputDto($uuid, [$uuid]));

        $this->assertInstanceOf(GenreUpdateOutputDto::class, $response);
    }

    public function testUpdateCategoriesNotFound()
    {
        $this->expectException(NotFoundException::class);

        $uuid = (string) RamseyUuid::uuid4();

        $useCase = new UpdateGenreUseCase(
            $this->mockRepository($uuid, 0),
            $this->mockTransaction(),
            $this->mockCategoryRepository([$uuid])
        );

        $useCase->execute($this->mockUpdateInputDto($uuid, [$uuid, 'teste']));
    }

    private function mockEntity(string $uuid)
    {
        $mockEntity = Mockery::mock(EntityGenre::class, [
            'teste', new Uuid($uuid), true, [],
        ]);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-a H:i:s'));
        $mockEntity->shouldReceive('update')->times(1);
        $mockEntity->shouldReceive('addCategory');
        $mockEntity->shouldReceive('clearCategories');

        return $mockEntity;
    }

    private function mockRepository(string $uuid, int $timesCalled = 1)
    {
        $mockEntity = $this->mockEntity($uuid);
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
            ->once()
            ->with($uuid)
            ->andReturn($mockEntity);
        $mockRepository->shouldReceive('update')
            ->times($timesCalled)
            ->andReturn($mockEntity);

        return $mockRepository;
    }

    private function mockTransaction()
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        return $mockTransaction;
    }

    private function mockUpdateInputDto(string $uuid, array $categories)
    {
        $mockUpdateInputDto = Mockery::mock(GenreUpdateInputDto::class, [
            $uuid, 'name to update', $categories,
        ]);

        return $mockUpdateInputDto;
    }

    private function mockCategoryRepository(array $uuid)
    {
        $mockCategoryRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockCategoryRepository->shouldReceive('getIdsListIds')
            ->once()
            ->andReturn($uuid);

        return $mockCategoryRepository;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

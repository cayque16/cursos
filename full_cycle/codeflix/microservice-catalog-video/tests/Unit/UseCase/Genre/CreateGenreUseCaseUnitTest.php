<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\DTO\Genre\CreateGenre\GenreCreateInputDto;
use Core\UseCase\DTO\Genre\CreateGenre\GenreCreateOutputDto;
use Core\UseCase\Genre\CreateGenreUseCase;
use Core\UseCase\Interfaces\TransactionInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class CreateGenreUseCaseUnitTest extends TestCase
{
    public function testCreate()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $useCase = new CreateGenreUseCase(
            $this->mockRepository($uuid),
            $this->mockTransaction(),
            $this->mockCategoryRepository([$uuid])
        );

        $response = $useCase->execute($this->mockCreateInputDto([$uuid]));

        $this->assertInstanceOf(GenreCreateOutputDto::class, $response);
    }

    public function testCreateCategoriesNotFound()
    {
        $this->expectException(NotFoundException::class);
        
        $uuid = (string) RamseyUuid::uuid4();

        $useCase = new CreateGenreUseCase(
            $this->mockRepository($uuid),
            $this->mockTransaction(),
            $this->mockCategoryRepository([$uuid])
        );

        $useCase->execute($this->mockCreateInputDto([$uuid, 'teste']));
    }

    private function mockEntity(string $uuid)
    {
        $mockEntity = Mockery::mock(EntityGenre::class,[
            'teste', new Uuid($uuid), true, []
        ]);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-a H:i:s'));

        return $mockEntity;
    }

    private function mockRepository(string $uuid)
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')->andReturn($this->mockEntity($uuid));

        return $mockRepository;
    }

    private function mockTransaction()
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        return $mockTransaction;
    }

    private function mockCreateInputDto(array $uuid)
    {
        $mockCreateInputDto = Mockery::mock(GenreCreateInputDto::class, [
            'name', $uuid, true
        ]);

        return $mockCreateInputDto;
    }

    private function mockCategoryRepository(array $uuid)
    {
        $mockCategoryRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockCategoryRepository->shouldReceive('getIdsListIds')->andReturn($uuid);

        return $mockCategoryRepository;
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}

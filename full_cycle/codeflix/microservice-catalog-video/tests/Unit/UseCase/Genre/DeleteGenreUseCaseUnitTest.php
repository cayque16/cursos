<?php

namespace Tests\Unit\UseCase\Genre;

use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\DeleteGenre\DeleteGenreOutputDto;
use Core\UseCase\DTO\Genre\GenreInputDto;
use Core\UseCase\Genre\DeleteGenreUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class DeleteGenreUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')
                        ->once()
                        ->with($uuid)
                        ->andReturn(true);

        $useCase = new DeleteGenreUseCase($mockRepository);

        $mockInputDto = Mockery::mock(GenreInputDto::class, [$uuid]);

        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(DeleteGenreOutputDto::class, $response);
        $this->assertTrue($response->success);
    }

    public function testDeleteFail()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')
                        ->times(1)
                        ->with($uuid)
                        ->andReturn(false);

        $useCase = new DeleteGenreUseCase($mockRepository);

        $mockInputDto = Mockery::mock(GenreInputDto::class, [$uuid]);

        $response = $useCase->execute($mockInputDto);

        $this->assertFalse($response->success);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}

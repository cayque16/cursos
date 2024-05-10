<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\DTO\Video\DeleteVideo\DeleteVideoInputDto;
use Core\UseCase\DTO\Video\DeleteVideo\DeleteVideoOutputDto;
use Core\UseCase\Video\DeleteVideoUseCase;
use Mockery;
use stdClass;
use Tests\TestCase;

class DeleteVideoUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {
        $useCase = new DeleteVideoUseCase(
            repository: $this->mockRepository(),
        );

        $response = $useCase->execute(
            input: $this->mockInputDto(),
        );

        $this->assertInstanceOf(DeleteVideoOutputDto::class, $response);
    }

    private function mockInputDto()
    {
        $mock = Mockery::mock(DeleteVideoInputDto::class, [Uuid::random()]);

        return $mock;
    }

    private function mockRepository()
    {
        $mock = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mock->shouldReceive('delete')
            ->once()
            ->andReturn(true);
            
        return $mock;
    }
}

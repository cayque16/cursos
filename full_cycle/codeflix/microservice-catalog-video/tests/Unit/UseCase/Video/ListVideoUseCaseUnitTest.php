<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;
use Core\UseCase\DTO\Video\ListVideoInputDto;
use Core\UseCase\DTO\Video\ListVideoOutputDto;
use Core\UseCase\Video\ListVideoUseCase;
use Mockery;
use stdClass;
use Tests\TestCase;

class ListVideoUseCaseUnitTest extends TestCase
{
    public function testList()
    {
        $uuid = Uuid::random();

        $useCase = new ListVideoUseCase(
            repository: $this->mockRepository(),
        );

        $response = $useCase->execute($this->mockInputDto($uuid));

        $this->assertInstanceOf(ListVideoOutputDto::class, $response);
    }

    private function mockInputDto(string $id)
    {
        return Mockery::mock(ListVideoInputDto::class, [
            $id,
        ]);
    }

    private function mockRepository()
    {
        $mock = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mock->shouldReceive('findById')
            ->once()
            ->andReturn($this->createEntity());

        return $mock;
    }

    private function createEntity(): Video
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
}

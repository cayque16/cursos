<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\ListVideos\ListVideosInputDto;
use Core\UseCase\Video\ListVideosUseCase;
use Mockery;
use stdClass;
use Tests\TestCase;
use Tests\Unit\UseCase\UseCaseTrait;

class ListVideosUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function testListPaginate()
    {
        $useCase = new ListVideosUseCase(
            repository: $this->mockRepository(),
        );

        $response = $useCase->execute(
            input: $this->createMockInputDto()
        );

        $this->assertInstanceOf(PaginationInterface::class, $response);

        Mockery::close();
    }

    private function createMockInputDto()
    {
        $mock = Mockery::mock(ListVideosInputDto::class, [
            '',
            'DESC',
            1,
            15,
        ]);

        return $mock;
    }

    private function mockRepository()
    {
        $mock = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);

        $mock->shouldReceive('paginate')
            ->once()
            ->andReturn($this->mockPagination());

        return $mock;
    }
}

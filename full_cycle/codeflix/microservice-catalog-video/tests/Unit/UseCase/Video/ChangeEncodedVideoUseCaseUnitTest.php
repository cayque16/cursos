<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\ChangeEncoded\ChangeEncodedInputDto;
use Core\UseCase\DTO\Video\ChangeEncoded\ChangeEncodedOutputDto;
use Core\UseCase\Video\ChangeEncodedVideoUseCase;
use Mockery;
use stdClass;
use Tests\TestCase;

class ChangeEncodedVideoUseCaseUnitTest extends TestCase
{
    public function testSpies()
    {
        $input = new ChangeEncodedInputDto(
            id: '123',
            encodedPath: 'path/video-encoded.ext'
        );

        $mockRepository = Mockery::mock(stdClass::class, VideoRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
            ->times(1)
            ->with($input->id)
            ->andReturn($this->createEntity());

        $mockRepository->shouldReceive('updateMedia')
            ->times(1);

        $useCase = new ChangeEncodedVideoUseCase(
            repository: $mockRepository,
        );

        $response = $useCase->execute(input: $input);

        $this->assertInstanceOf(ChangeEncodedOutputDto::class, $response);

        Mockery::close();
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

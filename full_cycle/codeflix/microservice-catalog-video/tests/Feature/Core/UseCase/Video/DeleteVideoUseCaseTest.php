<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\DeleteVideo\DeleteVideoInputDto;
use Core\UseCase\Video\DeleteVideoUseCase;
use Tests\TestCase;

class DeleteVideoUseCaseTest extends TestCase
{
    public function testDelete()
    {
        $useCase = new DeleteVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $video = Video::factory()->create();

        $response = $useCase->execute(new DeleteVideoInputDto($video->id));

        $this->assertTrue($response->success);
    }

    public function testDeleteIdNotFound()
    {
        $this->expectException(NotFoundException::class);

        $useCase = new DeleteVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $useCase->execute(new DeleteVideoInputDto('fake_id'));
    }
}

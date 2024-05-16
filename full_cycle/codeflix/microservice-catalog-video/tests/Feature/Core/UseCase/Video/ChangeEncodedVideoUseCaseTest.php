<?php

namespace Tests\Feature\Core\UseCase\Video;

use App\Models\Video;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\ChangeEncoded\ChangeEncodedInputDto;
use Core\UseCase\Video\ChangeEncodedVideoUseCase;
use Tests\TestCase;

class ChangeEncodedVideoUseCaseTest extends TestCase
{
    public function testIfUpdateMediaInDatabase()
    {
        $video = Video::factory()->create();

        $useCase = new ChangeEncodedVideoUseCase(
            $this->app->make(VideoRepositoryInterface::class)
        );

        $input = new ChangeEncodedInputDto(
            id: $video->id,
            encodedPath: 'path-id/video_encoded.ext'
        );

        $useCase->execute($input);

        $this->assertDatabaseHas('medias_video', [
            'video_id' => $input->id,
            'encode_path' => $input->encodedPath,
        ]);
    }
}

<?php

namespace Tests\Feature\Core\UseCase\Video;

use Core\Domain\Enum\Rating;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoInputDto;
use Core\UseCase\Video\CreateVideoUseCase;
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Support\Facades\Event;
use Tests\Stubs\UploadFilesStub;

class CreateVideoUseCaseTest extends BaseVideoUseCase
{
    public function useCase(): string
    {
        return CreateVideoUseCase::class;
    }

    public function inputDTO(
        array $categories = [],
        array $genres = [],
        array $castMembers = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $bannerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalfFile = null
    ): object {
        $input = new CreateVideoInputDto(
            title: 'test',
            description: 'test',
            yearLaunched: 2020,
            duration: 120,
            opened: true,
            rating: Rating::RATE18,
            categories: $categories,
            genres: $genres,
            castMembers: $castMembers,
            videoFile: $videoFile,
            trailerFile: $trailerFile,
            bannerFile: $bannerFile,
            thumbFile: $thumbFile,
            thumbHalfFile: $thumbHalfFile,
        );

        return $input;
    }

    public function testTransactionException()
    {
        Event::listen(TransactionBeginning::class, function () {
            throw new Exception('begin transaction');
        });

        try {
            $sut = $this->makeSut();
            $sut->execute($this->inputDTO());

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
        }
    }

    public function testUploadFilesException()
    {
        Event::listen(UploadFilesStub::class, function () {
            throw new Exception('upload files');
        });

        try {
            $sut = $this->makeSut();
            $input = $this->inputDTO(
                videoFile: [
                    'name' => 'video.mp4',
                    'type' => 'video.mp4',
                    'tmp_name' => '/tmp/video.mp4',
                    'error' => 0,
                ]
            );

            $sut->execute($input);

            $this->assertTrue(false);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
        }
    }
}
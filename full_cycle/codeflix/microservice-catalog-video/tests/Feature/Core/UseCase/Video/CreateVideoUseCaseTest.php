<?php

namespace Tests\Feature\Core\UseCase\Video;

use Core\Domain\Enum\Rating;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoInputDto;
use Core\UseCase\Video\CreateVideoUseCase;
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Support\Facades\Event;

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
}
<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\Enum\Rating;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoInputDto;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoOutputDto;
use Core\UseCase\Video\CreateVideoUseCase;
use Mockery;

class CreateVideoUseCaseUnitTest extends BaseVideoUseCaseUnitTest
{
    public function testExecuteInputOutput()
    {
        $useCase = $this->createUseCase(1, 1);

        $response = $useCase->execute($this->createMockInputDto());

        $this->assertInstanceOf(CreateVideoOutputDto::class, $response);
    }

    protected function nameActionRepository(): string
    {
        return 'insert';
    }

    protected function getUseCase(): string
    {
        return CreateVideoUseCase::class;
    }

    protected function createMockInputDto(
        array $categoriesIds = [],
        array $genresIds = [],
        array $castMembersIds = [],
        ?array $thumbFile = null,
        ?array $thumbHalfFile = null,
        ?array $bannerFile = null,
        ?array $trailerFile = null,
        ?array $videoFile = null,
    ) {
        return Mockery::mock(CreateVideoInputDto::class, [
            'title',
            'description',
            2023,
            90,
            true,
            Rating::RATE14,
            $categoriesIds,
            $genresIds,
            $castMembersIds,
            $thumbFile,
            $thumbHalfFile,
            $bannerFile,
            $trailerFile,
            $videoFile,
        ]);
    }
}

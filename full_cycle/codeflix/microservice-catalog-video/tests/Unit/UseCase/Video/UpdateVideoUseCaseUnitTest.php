<?php

namespace Tests\Unit\UseCase\Video;

use Core\Domain\ValueObject\Uuid;
use Core\UseCase\DTO\Video\UpdateVideo\UpdateVideoInputDto;
use Core\UseCase\DTO\Video\UpdateVideo\UpdateVideoOutputDto;
use Core\UseCase\Video\UpdateVideoUseCase;
use Mockery;

class UpdateVideoUseCaseUnitTest extends BaseVideoUseCaseUnitTest
{
    public function testExecuteInputOutput()
    {
        $useCase = $this->createUseCase();

        $response = $useCase->execute($this->createMockInputDto());

        $this->assertInstanceOf(UpdateVideoOutputDto::class, $response);
    }

    protected function nameActionRepository(): string
    {
        return 'update';
    }

    protected function getUseCase(): string
    {
        return UpdateVideoUseCase::class;
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
        return Mockery::mock(UpdateVideoInputDto::class, [
            Uuid::random(),
            'title',
            'description',
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

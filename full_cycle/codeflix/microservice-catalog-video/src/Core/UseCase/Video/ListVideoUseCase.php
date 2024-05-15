<?php

namespace Core\UseCase\Video;

use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\ListVideoInputDto;
use Core\UseCase\DTO\Video\ListVideoOutputDto;

class ListVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository
    ) { }

    public function execute(ListVideoInputDto $input): ListVideoOutputDto
    {
        $entity = $this->repository->findById($input->id);

        return new ListVideoOutputDto(
            $entity->id(),
            $entity->title,
            $entity->description,
            $entity->yearLaunched,
            $entity->duration,
            $entity->opened,
            $entity->rating,
            $entity->categoriesId,
            $entity->genresId,
            $entity->castMembersId,
            $entity->createdAt(),
            $entity->videoFile()?->filePath,
            $entity->thumbFile()?->path,
            $entity->thumbHalfFile()?->path,
            $entity->bannerFile()?->path,
            $entity->trailerFile()?->filePath,
        );
    }
}

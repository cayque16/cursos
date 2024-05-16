<?php

namespace Core\UseCase\Video;

use Core\Domain\Enum\MediaStatus;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Media;
use Core\UseCase\DTO\Video\ChangeEncoded\ChangeEncodedInputDto;
use Core\UseCase\DTO\Video\ChangeEncoded\ChangeEncodedOutputDto;

class ChangeEncodedVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
    ) {}

    public function execute(ChangeEncodedInputDto $input): ChangeEncodedOutputDto
    {
        $entity = $this->repository->findById($input->id);

        $entity->setVideoFile(
            new Media(
                filePath: $entity->videoFile()?->filePath ?? '',
                mediaStatus: MediaStatus::COMPLETE,
                encodePath: $input->encodedPath
            )
        );

        $this->repository->updateMedia($entity);

        return new ChangeEncodedOutputDto(
            id: $entity->id,
            encodedPath: $input->encodedPath
        );
    }
}

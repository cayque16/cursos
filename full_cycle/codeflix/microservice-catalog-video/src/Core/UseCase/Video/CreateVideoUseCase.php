<?php

namespace Core\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoInputDto;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoOutputDto;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Throwable;

class CreateVideoUseCase
{
    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected VideoEventManagerInterface $eventManager,
    ) { }

    public function execute(CreateVideoInputDto $input): CreateVideoOutputDto
    {
        $entity = $this->createEntity($input);

        try {
            $entityDb = $this->repository->insert($entity);

            if ($pathMedia = $this->storeMedia($entity->id(), $input->videoFile)) {
                $this->eventManager->dispatch(new VideoCreatedEvent($entity));
            }

            $this->transaction->commit();

            return new CreateVideoOutputDto();
        } catch (Throwable $th) {
            $this->transaction->rollback();

            throw $th;
        }
    }

    private function createEntity(CreateVideoInputDto $input): Video
    {
        $entity = new Video(
            title: $input->title,
            description: $input->description,
            yearLaunched: $input->yearLaunched,
            duration: $input->duration,
            opened: $input->opened,
            rating: $input->rating,
        );

        foreach ($input->categories as $categoryId) {
            $entity->addCategoryId($categoryId);
        }

        foreach ($input->genres as $genreId) {
            $entity->addGenreId($genreId);
        }

        foreach ($input->castMembers as $castMemberId) {
            $entity->addCastMemberId($castMemberId);
        }

        return $entity;
    }

    private function storeMedia(string $path, ?array $media = null): string
    {
        return $media ?
            $this->storage->store($path, $media) :
            '';
    }
}

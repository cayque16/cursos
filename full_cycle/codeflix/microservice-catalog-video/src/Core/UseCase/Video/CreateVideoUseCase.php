<?php

namespace Core\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Enum\MediaStatus;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Image;
use Core\Domain\ValueObject\Media;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoInputDto;
use Core\UseCase\DTO\Video\CreateVideo\CreateVideoOutputDto;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Throwable;

class CreateVideoUseCase
{
    protected Video $entity;

    public function __construct(
        protected VideoRepositoryInterface $repository,
        protected TransactionInterface $transaction,
        protected FileStorageInterface $storage,
        protected VideoEventManagerInterface $eventManager,
        protected CategoryRepositoryInterface $repositoryCategory,
        protected GenreRepositoryInterface $repositoryGenre,
        protected CastMemberRepositoryInterface $repositoryCastMember,
    ) { }

    public function execute(CreateVideoInputDto $input): CreateVideoOutputDto
    {
        $this->entity = $this->createEntity($input);

        try {
            $entityDb = $this->repository->insert($this->entity);

            $this->storageFiles($input);
            $this->repository->updateMedia($this->entity);

            $this->transaction->commit();

            return $this->output($this->entity);
        } catch (Throwable $th) {
            $this->transaction->rollback();

            // if (isset($pathMedia)) $this->storage->delete($pathMedia);

            throw $th;
        }
    }

    private function createEntity(CreateVideoInputDto $input): Video
    {
        $this->validateAllIds($input);
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

    protected function storageFiles(object $input): void
    {
        if ($pathVideoFile = $this->storageFile($this->entity->id(), $input->videoFile)) {
            $media = new Media(
                filePath: $pathVideoFile,
                mediaStatus: MediaStatus::PROCESSING
            );
            $this->entity->setVideoFile($media);

            $this->eventManager->dispatch(new VideoCreatedEvent($this->entity));
        }
        if ($pathThumbFile = $this->storageFile($this->entity->id(), $input->thumbFile)) {
            $media = new Image(
                path: $pathThumbFile,
            );
            $this->entity->setThumbFile($media);
        }
        if ($pathThumbHalfFile = $this->storageFile($this->entity->id(), $input->thumbHalfFile)) {
            $media = new Image(
                path: $pathThumbHalfFile,
            );
            $this->entity->setThumbHalfFile($media);
        }
        if ($pathBannerFile = $this->storageFile($this->entity->id(), $input->bannerFile)) {
            $media = new Image(
                path: $pathBannerFile,
            );
            $this->entity->setBannerFile($media);
        }
        if ($pathTrailerFile = $this->storageFile($this->entity->id(), $input->trailerFile)) {
            $media = new Media(
                filePath: $pathTrailerFile,
                mediaStatus: MediaStatus::PROCESSING
            );
            $this->entity->setTrailerFile($media);
        }
    }

    private function storageFile(string $path, ?array $media = null): null|string
    {
        return $media ?
            $this->storage->store($path, $media) :
            null;
    }

    protected function validateAllIds(object $input)
    {
        $this->validateIds(
            ids: $input->categories,
            repository: $this->repositoryCategory,
            singularLabel: 'Category',
            pluralLabel: 'Categories'
        );

        $this->validateIds(
            ids: $input->genres,
            repository: $this->repositoryGenre,
            singularLabel: 'Genre',
        );

        $this->validateIds(
            ids: $input->castMembers,
            repository: $this->repositoryCastMember,
            singularLabel: 'Cast Member',
        );
    }

    protected function validateIds(
        array $ids = [],
        $repository,
        string $singularLabel,
        ?string $pluralLabel = null
    ) {
        $idsDb = $repository->getIdsListIds($ids);
        
        $arrayDiff = array_diff($ids, $idsDb);
        
        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? $pluralLabel ?? $singularLabel . 's' : $singularLabel,
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function output(Video $entity): CreateVideoOutputDto
    {
        return new CreateVideoOutputDto(
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
            $entity->videoFile()?->filePath,
            $entity->thumbFile()?->path,
            $entity->thumbHalfFile()?->path,
            $entity->bannerFile()?->path,
            $entity->trailerFile()?->filePath,
        );
    }
}

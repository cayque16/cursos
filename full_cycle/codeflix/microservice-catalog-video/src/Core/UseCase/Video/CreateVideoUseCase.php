<?php

namespace Core\UseCase\Video;

use Core\Domain\Entity\Video;
use Core\Domain\Events\VideoCreatedEvent;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
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
        protected CategoryRepositoryInterface $repositoryCategory,
        protected GenreRepositoryInterface $repositoryGenre,
        protected CastMemberRepositoryInterface $repositoryCastMember,
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

        $this->validateCategoriesId($input->categories);
        foreach ($input->categories as $categoryId) {
            $entity->addCategoryId($categoryId);
        }

        $this->validateGenresId($input->genres);
        foreach ($input->genres as $genreId) {
            $entity->addGenreId($genreId);
        }

        $this->validateCastMembersId($input->castMembers);
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

    private function validateCategoriesId(array $categories = [])
    {
        $categoriesDb = $this->repositoryCategory->getIdsListIds($categories);
        
        $arrayDiff = array_diff($categories, $categoriesDb);
        
        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function validateGenresId(array $genres = [])
    {
        $genresDb = $this->repositoryGenre->getIdsListIds($genres);
        
        $arrayDiff = array_diff($genres, $genresDb);
        
        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Genres' : 'Genre',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }

    private function validateCastMembersId(array $castMembers = [])
    {
        $castMembersDb = $this->repositoryCastMember->getIdsListIds($castMembers);
        
        $arrayDiff = array_diff($castMembers, $castMembersDb);
        
        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'CastMembers' : 'CastMember',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }
}

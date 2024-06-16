<?php

namespace Core\UseCase\Video;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\DTO\Video\ListVideoInputDto;
use Core\UseCase\DTO\Video\ListVideoOutputDto;

class ListVideoUseCase
{
    public function __construct(
        private VideoRepositoryInterface $repository,
        private CategoryRepositoryInterface $repositoryCategory,
        private GenreRepositoryInterface $repositoryGenre,
        private CastMemberRepositoryInterface $repositoryCastMember,
    ) {
    }

    public function execute(ListVideoInputDto $input): ListVideoOutputDto
    {
        $entity = $this->repository->findById($input->id);
        $categoriesGross = $this->repositoryCategory->lstCategoryWithIdAndName($entity->categoriesId);
        
        $categories = [];
        foreach ($categoriesGross as $id => $name) {
            $categories[] = [
                'id' => $id,
                'name' => $name,
            ];
        }

        $genresGross = $this->repositoryGenre->lstGenreWithIdAndName($entity->genresId);
        
        $genres = [];
        foreach ($genresGross as $id => $name) {
            $genres[] = [
                'id' => $id,
                'name' => $name,
            ];
        }
        
        $castMembersGross = $this->repositoryCastMember->lstCastMemberWithIdAndName($entity->castMembersId);
        
        $castMembers = [];
        foreach ($castMembersGross as $id => $name) {
            $castMembers[] = [
                'id' => $id,
                'name' => $name,
            ];
        }

        return new ListVideoOutputDto(
            $entity->id(),
            $entity->title,
            $entity->description,
            $entity->yearLaunched,
            $entity->duration,
            $entity->opened,
            $entity->rating,
            $categories,
            $genres,
            $castMembers,
            $entity->createdAt(),
            $entity->videoFile()?->filePath,
            $entity->thumbFile()?->path,
            $entity->thumbHalfFile()?->path,
            $entity->bannerFile()?->path,
            $entity->trailerFile()?->filePath,
        );
    }
}

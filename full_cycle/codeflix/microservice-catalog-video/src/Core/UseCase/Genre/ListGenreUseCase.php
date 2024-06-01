<?php

namespace Core\UseCase\Genre;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\UseCase\DTO\Genre\GenreInputDto;
use Core\UseCase\DTO\Genre\GenreOutputDto;

class ListGenreUseCase
{
    public function __construct(
        protected GenreRepositoryInterface $repository,
        protected CategoryRepositoryInterface $repositoryCategory
    ) {
    }

    public function execute(GenreInputDto $input): GenreOutputDto
    {
        $genre = $this->repository->findById(id: $input->id);
        $categoriesGross = $this->repositoryCategory->lstCategoryWithIdAndName($genre->categories);
        
        $categories = [];
        foreach ($categoriesGross as $id => $name) {
            $categories[] = [
                'id' => $id,
                'name' => $name,
            ];
        }
        
        return new GenreOutputDto(
            id: (string) $genre->id,
            name: $genre->name,
            is_active: $genre->isActive,
            created_at: $genre->createdAt(),
            categories: $categories,
        );
    }
}

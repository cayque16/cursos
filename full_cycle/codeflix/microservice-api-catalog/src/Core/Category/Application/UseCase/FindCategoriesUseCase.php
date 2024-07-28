<?php

namespace Core\Category\Application\UseCase;

use Core\Category\Application\DTO\InputCategoriesDTO;
use Core\Category\Application\DTO\OutputCategoriesDTO;
use Core\Category\Application\DTO\OutputCategoryDTO;
use Core\Category\Domain\Repository\CategoryRepositoryInterface;

class FindCategoriesUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    ) { }

    public function execute(InputCategoriesDTO $input): OutputCategoriesDTO
    {
        $categories = $this->repository->findAll($input->filter);

        $items = [];
        foreach ($categories as $category) {
            array_push($items, new OutputCategoryDTO(
                id: $category->id(),
                name: $category->name,
                created_at: $category->createdAt(),
                description: $category->description ?? '',
                is_active: $category->isActive,
            ));
        }

        return new OutputCategoriesDTO(
            items: $items,
            total: count($categories)
        );
    }
}

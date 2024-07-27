<?php

namespace Core\Category\Application\UseCase;

use Core\Category\Application\DTO\InputCategoryDTO;
use Core\Category\Application\DTO\OutputCategoryDTO;
use Core\Category\Domain\Repository\CategoryRepositoryInterface;

class GetCategoryUseCase
{
    public function __construct(
        protected CategoryRepositoryInterface $repository
    ) { }

    public function execute(InputCategoryDTO $input): OutputCategoryDTO
    {
        $category = $this->repository->findOne($input->id);

        return new OutputCategoryDTO(
            id: $category->id(),
            name: $category->name,
            created_at: $category->createdAt(),
            description: $category->description ?? '',
            is_active: $category->isActive,
        );
    }
}

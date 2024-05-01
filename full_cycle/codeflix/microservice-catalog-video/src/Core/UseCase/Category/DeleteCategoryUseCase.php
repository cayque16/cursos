<?php

namespace Core\UseCase\Category;

use CategoryRepositoryInterface;
use Core\UseCase\DTO\Category\CategoryInputDto;
use Core\UseCase\DTO\Category\DeleteCategory\CategoryDeleteOutputDto;

class DeleteCategoryUseCase 
{
    public function __construct(
        private CategoryRepositoryInterface $repository
    ) {}

    public function execute(CategoryInputDto $input): CategoryDeleteOutputDto
    {
        $response = $this->repository->delete($input->id);

        return new CategoryDeleteOutputDto(
            success: $response
        );
    }
}

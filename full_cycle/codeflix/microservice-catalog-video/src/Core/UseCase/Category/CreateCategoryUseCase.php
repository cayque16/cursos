<?php

namespace Core\UseCase\Category;

use CategoryRepositoryInterface;
use Core\Domain\Entity\Category;

class CreateCategoryUseCase 
{
    protected $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute()
    {
        $category = new Category(name: 'teste');

        $this->repository->insert($category);
    }
}

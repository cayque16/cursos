<?php

namespace Core\Category\Domain\Repository;

use Core\Category\Domain\Entities\Category;

interface CategoryRepositoryInterface
{
    public function findOne(string $id): Category;
    public function findAll(?string $filter): array;
}

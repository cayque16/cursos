<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\PaginationInterface;

interface CategoryRepositoryInterface
{
    public function insert(Category $category): Category;
    public function findById(string $id): Category;
    public function getIdsListIds(array $categoriesId = []): array;
    public function findAll(string $filter = '', $order = 'DESC'): array;
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface;
    public function update(Category $category): Category;
    public function delete(string $id): bool;
    public function toCategory(object $data): Category;
}

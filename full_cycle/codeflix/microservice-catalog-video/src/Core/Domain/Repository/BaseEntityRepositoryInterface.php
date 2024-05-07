<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\BaseEntity;
use Core\Domain\Repository\PaginationInterface;

interface BaseEntityRepositoryInterface
{
    public function insert(BaseEntity $category): BaseEntity;
    public function findById(string $id): BaseEntity;
    public function getIdsListIds(array $categoriesId = []): array;
    public function findAll(string $filter = '', $order = 'DESC'): array;
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface;
    public function update(BaseEntity $category): BaseEntity;
    public function delete(string $id): bool;
    public function toBaseEntity(object $data): BaseEntity;
}

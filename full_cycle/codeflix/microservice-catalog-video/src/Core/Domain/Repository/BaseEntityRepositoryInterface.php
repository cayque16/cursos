<?php

namespace Core\Domain\Repository;

use Core\Domain\Entity\BaseEntity;
use Core\Domain\Repository\PaginationInterface;

interface BaseEntityRepositoryInterface
{
    public function insert(BaseEntity $entity): BaseEntity;
    public function findById(string $id): BaseEntity;
    public function findAll(string $filter = '', $order = 'DESC'): array;
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface;
    public function update(BaseEntity $entity): BaseEntity;
    public function delete(string $id): bool;
    public function toBaseEntity(object $data): BaseEntity;
}

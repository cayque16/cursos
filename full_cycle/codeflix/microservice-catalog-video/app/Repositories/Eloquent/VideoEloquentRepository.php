<?php

namespace App\Repositories\Eloquent;

use App\Models\Video as VideoModel;
use Core\Domain\Entity\Video as VideoEntity;
use Core\Domain\Entity\BaseEntity;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;

class VideoEloquentRepository implements VideoRepositoryInterface
{
    public function __construct(
        protected VideoModel $model,
    ) {}

    public function insert(BaseEntity $category): BaseEntity
    {

    }
    
    public function findById(string $id): BaseEntity
    {

    }
    
    public function getIdsListIds(array $categoriesId = []): array
    {

    }
    
    public function findAll(string $filter = '', $order = 'DESC'): array
    {

    }
    
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface
    {

    }
    
    public function update(BaseEntity $category): BaseEntity
    {

    }
    
    public function delete(string $id): bool
    {

    }
    
    public function toBaseEntity(object $data): BaseEntity
    {

    }
    
    public function updateMedia(BaseEntity $entity): BaseEntity
    {

    }
}

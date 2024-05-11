<?php

namespace App\Repositories\Eloquent;

use App\Models\Video as VideoModel;
use Core\Domain\Entity\Video as VideoEntity;
use Core\Domain\Entity\BaseEntity;
use Core\Domain\Enum\Rating;
use Core\Domain\Repository\PaginationInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\Domain\ValueObject\Uuid;

class VideoEloquentRepository implements VideoRepositoryInterface
{
    public function __construct(
        protected VideoModel $model,
    ) {}

    public function insert(BaseEntity $entity): BaseEntity
    {
        $entityDb = $this->model->create([
            'id' => $entity->id(),
            'title' => $entity->title,
            'description' => $entity->description,
            'year_launched' => $entity->yearLaunched,
            'rating' => $entity->rating,
            'duration' => $entity->duration,
            'opened' => $entity->opened,
        ]);

        return $this->toBaseEntity($entityDb);
    }
    
    public function findById(string $id): BaseEntity
    {

    }
    
    public function getIdsListIds(array $entitiesId = []): array
    {

    }
    
    public function findAll(string $filter = '', $order = 'DESC'): array
    {

    }
    
    public function paginate(string $filter = '', $order = 'DESC', int $page = 1, $totalPage = 15): PaginationInterface
    {

    }
    
    public function update(BaseEntity $entity): BaseEntity
    {

    }
    
    public function delete(string $id): bool
    {

    }
    
    public function toBaseEntity(object $data): BaseEntity
    {
        return new VideoEntity(
            id: new Uuid($data->id),
            title: $data->title,
            description: $data->description,
            yearLaunched: (int) $data->year_launched,
            rating: $data->rating,
            duration: (bool) $data->duration,
            opened: $data->opened,
        );
    }
    
    public function updateMedia(BaseEntity $entity): BaseEntity
    {

    }
}
